<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\Destaque;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Noticia;
use Microblog\Services\CategoriaServico;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/../vendor/autoload.php';

ControleDeAcesso::exigirLogin();

$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($id);

$mensagemErro = "";
$dadosNoticia = null;
$tipoUsuario = null;
$listaDeCategorias = [];
$categoriasCarregadas = false;
$usuarioId = (int) $_SESSION['id'];

try {
    $tipoUsuario = TipoUsuario::from($_SESSION['tipo']);

    $noticiaServico = new NoticiaServico();
    $dadosNoticia = $noticiaServico->buscarPorId($tipoUsuario, $usuarioId, $id);

    if ($dadosNoticia === null) {
        Utils::alertaErro("Notícia não encontrada ou você não tem permissão para acessá-la.");
    }

    $categoriaServico = new CategoriaServico();
    $listaDeCategorias = $categoriaServico->listarTodos();
    $categoriasCarregadas = true;
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = "Não foi possível carregar os dados da notícia";
}

$titulo = $dadosNoticia['titulo'] ?? '';
$texto = $dadosNoticia['texto'] ?? '';
$resumo = $dadosNoticia['resumo'] ?? '';
$categoriaId = $dadosNoticia['categoria_id'] ?? '';
$destaque = $dadosNoticia['destaque'] ?? Destaque::NAO->value;
$imagem = $dadosNoticia['imagem'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dadosNoticia !== null && $categoriasCarregadas) {
    try {
        $titulo = Utils::normalizarEntrada($_POST['titulo'] ?? '');
        $texto = Utils::normalizarEntrada($_POST['texto'] ?? '');
        $resumo = Utils::normalizarEntrada($_POST['resumo'] ?? '');
        $categoriaId = Utils::normalizarEntrada($_POST['categoria'] ?? '', 'inteiro');
        $destaque = Utils::normalizarEntrada($_POST['destaque'] ?? '');

        Validacoes::validarTitulo($titulo);
        Validacoes::validarTexto($texto);
        Validacoes::validarResumo($resumo);
        Validacoes::validarCategoria($categoriaId);
        Validacoes::validarDestaque($destaque);

        $destaqueEnum = Destaque::from($destaque);

        $arquivoEnviado = $_FILES['imagem'] ?? null;
        $imagemSemAlteracao = $arquivoEnviado === null ||
            (is_array($arquivoEnviado) && ($arquivoEnviado['error'] ?? null) === UPLOAD_ERR_NO_FILE);

        if (!$imagemSemAlteracao) {
            $imagem = Utils::upload($arquivoEnviado);
        }

        $noticia = new Noticia(
            $titulo,
            $texto,
            $resumo,
            $imagem,
            $destaqueEnum,
            (int) ($dadosNoticia['usuario_id'] ?? $usuarioId),
            $categoriaId,
            (int) $dadosNoticia['id'],
            $dadosNoticia['data']
        );

        $noticiaServico->atualizar($tipoUsuario, $usuarioId, $noticia);

        header("location:noticias.php");
        exit;
    } catch (InvalidArgumentException $e) {
        $mensagemErro = $e->getMessage();
    } catch (Throwable $e) {
        Utils::registrarLog($e);
        $mensagemErro = "Não foi possível atualizar a notícia. Tente novamente.";
    }
}

require_once __DIR__ . '/../includes/cabecalho-admin.php';
?>

<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">
        <h2 class="text-center">Atualizar dados da notícia</h2>

        <?php if ($mensagemErro !== ""): ?>
            <div class="alert alert-danger text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php endif; ?>

        <?php if ($dadosNoticia !== null && $categoriasCarregadas): ?>
            <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" for="categoria">Categoria:</label>
                    <select class="form-select" name="categoria" id="categoria">
                        <option value="" <?= $dadosNoticia['categoria_id'] === null ? 'selected' : '' ?>></option>
                        <?php foreach ($listaDeCategorias as $itemCategoria): ?>
                            <option
                                value="<?= Utils::escaparHtml($itemCategoria['id']) ?>"
                                <?= (int) $itemCategoria['id'] === (int) $categoriaId ? 'selected' : '' ?>>
                                <?= Utils::escaparHtml($itemCategoria['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="titulo">Título:</label>
                    <input
                        value="<?= Utils::escaparHtml($titulo) ?>"
                        class="form-control"
                        type="text"
                        id="titulo"
                        name="titulo">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="texto">Texto:</label>
                    <textarea class="form-control" name="texto" id="texto" cols="50" rows="6"><?= Utils::escaparHtml($texto) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="resumo">Resumo (máximo de 300 caracteres):</label>
                    <span id="maximo" class="badge bg-danger">0</span>
                    <textarea class="form-control" name="resumo" id="resumo" cols="50" rows="2" maxlength="300"><?= Utils::escaparHtml($resumo) ?></textarea>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-5">
                            <label for="imagem-existente" class="form-label">Imagem atual:</label>
                            <input
                                value="<?= Utils::escaparHtml($imagem) ?>"
                                class="form-control"
                                type="text"
                                id="imagem-existente"
                                readonly
                                disabled>
                        </div>
                        <div class="col-7 text-end">
                            <img
                                src="../images/<?= Utils::escaparHtml($imagem) ?>"
                                alt="Imagem da notícia"
                                class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="imagem" class="form-label">Caso queira mudar, selecione outra imagem:</label>
                    <input class="form-control" type="file" id="imagem" name="imagem" accept="image/jpeg, image/gif, image/png, image/webp">
                </div>
                <div class="mb-3">
                    <p>Deixar a notícia em destaque?</p>
                    <input
                        type="radio"
                        class="btn-check"
                        name="destaque"
                        id="option1"
                        autocomplete="off"
                        value="<?= Destaque::NAO->value ?>"
                        <?= $destaque === Destaque::NAO->value ? 'checked' : '' ?>>
                    <label class="btn btn-outline-danger" for="option1">Não</label>
                    <input
                        type="radio"
                        class="btn-check"
                        name="destaque"
                        id="option2"
                        autocomplete="off"
                        value="<?= Destaque::SIM->value ?>"
                        <?= $destaque === Destaque::SIM->value ? 'checked' : '' ?>>
                    <label class="btn btn-outline-success" for="option2">Sim</label>
                </div>
                <button class="btn btn-primary" name="atualizar">
                    <i class="bi bi-arrow-clockwise"></i> Atualizar
                </button>
            </form>
        <?php endif; ?>
    </article>
</div>

<script src="js/contador.js"></script>
<?php require_once __DIR__ . '/../includes/rodape-admin.php'; ?>
