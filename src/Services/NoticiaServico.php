<?php
namespace Microblog\Services;

use Exception;
use Microblog\Database\ConexaoBD;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Models\Noticia;
use PDO;
use PDOException;

final class NoticiaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }


    public function listarTodos(TipoUsuario $tipoUsuario, int $usuarioId): array
    {
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            $sql = "SELECT noticias.id, noticias.titulo, noticias.data, noticias.destaque,
                           usuarios.nome AS autor
                    FROM noticias
                    LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id
                    ORDER BY noticias.data DESC";

            try {
                $consulta = $this->conexao->query($sql);
                return $consulta->fetchAll();
            } catch (PDOException $e) {
                Utils::registrarLog($e);
                throw new Exception("Erro ao listar notícias");
            }
        }

        $sql = "SELECT id, titulo, data, destaque
                FROM noticias
                WHERE usuario_id = :usuario_id
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":usuario_id", $usuarioId);
            $consulta->execute();
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias");
        }
    }

    public function buscarPorId(TipoUsuario $tipoUsuario, int $usuarioId, int $noticiaId): ?array
    {
        $sql = "SELECT noticias.id, noticias.data, noticias.titulo, noticias.texto,
                       noticias.resumo, noticias.imagem, noticias.destaque,
                       noticias.usuario_id, noticias.categoria_id,
                       usuarios.nome AS autor
                FROM noticias
                LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id
                WHERE noticias.id = :noticia_id";

        if ($tipoUsuario !== TipoUsuario::ADMIN) {
            $sql .= " AND noticias.usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":noticia_id", $noticiaId);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId);
            }

            $consulta->execute();
            return $consulta->fetch() ?: null;
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar notícia por ID");
        }
    }

    public function inserir(Noticia $noticia): void
    {
        $sql = "INSERT INTO noticias (
            titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id
        ) VALUES(
            :titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id
        )";

        try {
            $consulta = $this->conexao->prepare($sql);

            $consulta->bindValue(":titulo", $noticia->getTitulo());
            $consulta->bindValue(":texto", $noticia->getTexto());
            $consulta->bindValue(":resumo", $noticia->getResumo());
            $consulta->bindValue(":imagem", $noticia->getImagem());
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value);
            $consulta->bindValue(":usuario_id", $noticia->getUsuarioId());
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId());

            $consulta->execute();

        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir notícia");
        }        
    }

    public function atualizar(TipoUsuario $tipoUsuario, int $usuarioId, Noticia $noticia): void
    {
        $sql = "UPDATE noticias
                SET titulo = :titulo,
                    texto = :texto,
                    resumo = :resumo,
                    imagem = :imagem,
                    destaque = :destaque,
                    categoria_id = :categoria_id
                WHERE id = :id";

        if ($tipoUsuario !== TipoUsuario::ADMIN) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $noticia->getTitulo());
            $consulta->bindValue(":texto", $noticia->getTexto());
            $consulta->bindValue(":resumo", $noticia->getResumo());
            $consulta->bindValue(":imagem", $noticia->getImagem());
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value);
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId());
            $consulta->bindValue(":id", $noticia->getId());

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId);
            }

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar notícia");
        }
    }

    public function excluir(TipoUsuario $tipoUsuario, int $usuarioId, int $noticiaId): void
    {
        $sql = "DELETE FROM noticias WHERE id = :noticia_id";

        if ($tipoUsuario !== TipoUsuario::ADMIN) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":noticia_id", $noticiaId);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId);
            }

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir notícia");
        }
    }
}