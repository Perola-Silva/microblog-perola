<?php

use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

$termo = Utils::normalizarEntrada($_POST['busca'] ?? '');

if (mb_strlen($termo) < 2) {
    ?>
    <p class="mb-0 text-muted">Digite pelo menos 2 caracteres para pesquisar.</p>
    <?php
    exit;
}

if (mb_strlen($termo) > 100) {
    http_response_code(422);
    ?>
    <p class="mb-0 text-danger">A pesquisa deve ter no máximo 100 caracteres.</p>
    <?php
    exit;
}

try {
    $noticiaServico = new NoticiaServico();
    $listaDeResultados = $noticiaServico->buscarPorTermo($termo);
} catch (Throwable $e) {
    Utils::registrarLog($e);
    http_response_code(500);
    ?>
    <p class="mb-0 text-danger">Não foi possível realizar a pesquisa.</p>
    <?php
    exit;
}
?>

<h2 class="fs-6">
    Resultados:
    <span class="badge rounded-pill text-bg-success"><?= count($listaDeResultados) ?></span>
</h2>
<?php if ($listaDeResultados === []): ?>
    <p class="mb-0 text-muted">Nenhuma notícia encontrada.</p>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($listaDeResultados as $noticia): ?>
            <a class="list-group-item list-group-item-action" href="noticia.php?id=<?= Utils::escaparHtml($noticia['id']) ?>">
                <?= Utils::escaparHtml($noticia['titulo']) ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
