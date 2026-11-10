<?php
namespace Microblog\Services;

use Exception;
use Microblog\Database\ConexaoBD;
use Microblog\Helpers\Utils;
use Microblog\Models\Usuario;
use PDO;
use PDOException;

final class UsuarioServico
{
    private PDO $conexao;

    /* Ao criar um objeto UsuarioServico, o construtor fica responsavel por chamar/executar a conexão com o banco de dados (proveniente do metodo estático getConexao).
    Os dados da conexão ficarão guardados na propriedade conexão. */
    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT id, nome, email, tipo FROM usuarios ORDER BY nome";

        try {
            $consulta = $this->conexao->query($sql);
            return $consulta->fetchAll();
        } catch(PDOException $e) {
            // Registrar/logar os detalhes do erro em logs/erro.log
            Utils::registrarLog($e);

            // Lançando uma mensagem de arro amigável (sem detalhes)
            // Essa mensagem será posteriormente capturada e exibida no Front-End
            throw new Exception("Erro ao listar os usuários");
        }
    }

    public function inserir(Usuario $usuario): void
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email,:senha, :tipo)";

        try {
            // Repare abaixo o acesso aos dados do objeto $usuario a través de seus getters. No caso do getTipoUsuario, como se trata de uma ENUM, acessamos seu valor usando ->value

            $consulta = $this->conexao->prepare($sql);

            $consulta->bindValue(":nome", $usuario->getNome());
            $consulta->bindValue(":email", $usuario->getEmail());
            $consulta->bindValue(":senha", $usuario->getSenha());
            $consulta->bindValue(":tipo", $usuario->getTipoUsuario()->value);

            $consulta->execute();
        } catch(PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir usuário");
        }
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id);
            $consulta->execute();

            // Ternário conciso/abreviado (se for TRUE, retorna ele mesmo senão null)
            return $consulta->fetch() ?: null;

            //Ternário tradicional
            // return $consulta->fetch() ? $consulta->fetch() : null;
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar usuário por ID.");
        }
    }

    public function atualizar(Usuario $usuario): void
    {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha,  tipo = :tipo WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $usuario->getNome());
            $consulta->bindValue(":email", $usuario->getEmail());
            $consulta->bindValue(":senha", $usuario->getSenha());
            $consulta->bindValue(":tipo", $usuario->getTipoUsuario()->value);
            $consulta->bindValue(":id", $usuario->getId());

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar usuário");
        }
    }

    public function excluir(int $id): void {
        $sql = "DELETE FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id);
            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir usuario");
        }
    }
}
