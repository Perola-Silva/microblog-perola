<?php

use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

Utils::verificarId($_GET['id'] ?? null);
$noticiaId = (int) $_GET['id'];
$mensagemErro = "";
$dadosNoticia = null;

try {
    $noticiaServico = new NoticiaServico();
    $dadosNoticia = $noticiaServico->buscarPublicaPorId($noticiaId);
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar a notícia.";
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">
    <?php if ($mensagemErro !== ""): ?>
        <div class="col-12">
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        </div>
    <?php elseif ($dadosNoticia === null): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                A notícia solicitada não foi encontrada.
            </div>
        </div>
    <?php else: ?>
        <article class="col-12">
            <h2><?= Utils::escaparHtml($dadosNoticia['titulo']) ?></h2>
            <p class="font-weight-light">
                <time datetime="<?= Utils::escaparHtml($dadosNoticia['data']) ?>">
                    <?= Utils::escaparHtml(date('d/m/Y', strtotime($dadosNoticia['data']))) ?>
                </time> -
                <span><?= Utils::escaparHtml($dadosNoticia['autor'] ?? 'Equipe Microblog') ?></span>
            </p>
            <img src="images/<?= Utils::escaparHtml($dadosNoticia['imagem']) ?>" alt="" class="float-start pe-2 img-fluid">
            <p class="ajusta-texto"><?= Utils::escaparHtml($dadosNoticia['texto']) ?></p>
        </article>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
