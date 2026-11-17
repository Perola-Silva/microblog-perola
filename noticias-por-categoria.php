<?php

use Microblog\Helpers\Utils;
use Microblog\Services\CategoriaServico;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

Utils::verificarId($_GET['id'] ?? null);
$categoriaId = (int) $_GET['id'];
$dadosCategoria = null;
$listaDeNoticias = [];
$mensagemErro = "";

try {
    $categoriaServico = new CategoriaServico();
    $dadosCategoria = $categoriaServico->buscarPorId($categoriaId);

    if ($dadosCategoria !== null) {
        $noticiaServico = new NoticiaServico();
        $listaDeNoticias = $noticiaServico->listarPorCategoria($categoriaId);
    }
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar as notícias desta categoria.";
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">

    <article class="col-12">
        <?php if ($mensagemErro !== ""): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php elseif ($dadosCategoria === null): ?>
            <div class="alert alert-info text-center">
                A categoria solicitada não foi encontrada.
            </div>
        <?php else: ?>
            <h2 class="text-center">
                Notícias sobre <span class="badge bg-primary"><?= Utils::escaparHtml($dadosCategoria['nome']) ?></span>
            </h2>

            <div class="row my-1">
                <div class="col-12 px-md-1">
                    <div class="list-group">
                        <?php if ($listaDeNoticias === []): ?>
                            <p class="text-center text-muted">Nenhuma notícia encontrada nesta categoria.</p>
                        <?php else: ?>
                            <?php foreach ($listaDeNoticias as $noticia): ?>
                                <a href="noticia.php?id=<?= Utils::escaparHtml($noticia['id']) ?>" class="list-group-item list-group-item-action">
                                    <h3 class="fs-6"><?= Utils::escaparHtml($noticia['titulo']) ?></h3>
                                    <p>
                                        <time datetime="<?= Utils::escaparHtml($noticia['data']) ?>">
                                            <?= Utils::escaparHtml(date('d/m/Y', strtotime($noticia['data']))) ?>
                                        </time>
                                        - <?= Utils::escaparHtml($noticia['autor'] ?? 'Equipe Microblog') ?>
                                    </p>
                                    <p><?= Utils::escaparHtml($noticia['resumo']) ?></p>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </article>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
