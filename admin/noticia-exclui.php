<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/../vendor/autoload.php';

ControleDeAcesso::exigirLogin();

$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($id);

try {
    $tipoUsuario = TipoUsuario::from($_SESSION['tipo']);
    $usuarioId = (int) $_SESSION['id'];
    $noticiaServico = new NoticiaServico();
    $dadosNoticia = $noticiaServico->buscarPorId($tipoUsuario, $usuarioId, $id);

    if ($dadosNoticia === null) {
        Utils::alertaErro("Notícia não encontrada ou você não tem permissão para excluí-la.");
    }

    $noticiaServico->excluir($tipoUsuario, $usuarioId, $id);
    header("location:noticias.php");
    exit;
} catch (Throwable $e) {
    Utils::registrarLog($e);
    Utils::alertaErro("Não foi possível excluir a notícia. Tente novamente");
}
