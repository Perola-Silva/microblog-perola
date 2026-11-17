# Prompts da conversa e objetivos

## 1. Notícias em destaque na página inicial
**Prompt:** Leia o arquivo agents.md para entender o contexto do projeto e seus padrões. Após isso acesse o arquivo index.php que se encontra na raiz e acesse a classe ServicoNoticia para buscar os dados de noticias. Insira APENAS as noticias que são destques na div com artigo, após isso coloque este prompr no arquivo prompts.md. aguarde o proximo comando.

**Objetivo:** Exibir na página inicial somente notícias destacadas e registrar o pedido.

## 2. Listagem pública de notícias
**Prompt:** Agora acesse o arquivo includes/todas.php e programe este include para exibir os dados de todas as noticias

**Objetivo:** Substituir os exemplos estáticos do include por uma listagem dinâmica de notícias.

## 3. Reutilização do método de listagem
**Prompt:** E realmente preciso criar outro metodo? não pode aproveitar o listarTodos no momento de exibir os dados tanto no arquivo includes/todas.php e no arquivo index.php?

**Objetivo:** Avaliar a reutilização de `listarTodos()` em páginas públicas, considerando diferenças de campos e permissões.

## 4. Detalhes da notícia
**Prompt:** Agora modifique o arquivo noticia.php para exibir a noticia selecionada conforme o id do link dinamico. Coloque todos os seus detalhes conforme o `<article>` já criado data (formatada para dia/mês/ano), autor, imagem e texto, crie um ou mais metodos em NoticiaServico

**Objetivo:** Carregar e exibir os detalhes da notícia selecionada pelo ID.

## 5. Diagnóstico do log de erros
**Prompt:** verifique o arquivo log de erros e verifique o que está errado e o corrija

**Objetivo:** Identificar a causa das falhas registradas e corrigi-la.

## 6. Nome do autor após exclusão do usuário
**Prompt:** modifique os campos onde aparece o nome do usuario que cadastrou a noticia, na area publica do site mude para Equipe Microblog caso o usuario esteja como deletado, e dentro da area administrativa conste como usuario deletado

**Objetivo:** Definir textos diferentes para autoria ausente nas áreas pública e administrativa.

## 7. Categorias no menu
**Prompt:** Agora verifique o arquivo, includes/cabecalho.php e programe para exibir todas as categorias no dropdown, não esqueça do link dinamico com o id para a pagina noticias-por-categoria.php

**Objetivo:** Carregar categorias do banco no menu e gerar links com o ID correspondente.

## 8. Notícias por categoria
**Prompt:** agora programe a pagina noticias-por-categoria.php para exibir as noticias da categoria selecionada puxando pelo link dinamico

**Objetivo:** Filtrar e exibir notícias da categoria indicada pelo link.

## 9. Análise da busca
**Prompt:** Eu gostaria de fazer um mecanismo de pesquisa no site para localizar noticias, verifique os seguintes arquivos resultados.php, includes/cabecalho.php e o js/busca.js para verificar qual seria o melhor caminho para relizar isso, validar se será necessario criar outro metodo na classe NoticiaServico, ou se será necessario a criação de uma classe apenas para a busca por favor aguarde o proximo comando antes de escrever o codigo.

**Objetivo:** Analisar a arquitetura de busca e recomendar se a lógica deve ficar em `NoticiaServico` ou em uma classe própria, sem implementar ainda.

## 10. Implementação da busca
**Prompt:** por favor siga com as recomendações que foram validadas

**Objetivo:** Implementar a busca incremental usando `NoticiaServico`, `resultados.php` e `js/busca.js`.

## 11. Exibição compacta da busca
**Prompt:** Pode exibir apenas os titulos das noticias? junto ao resumo fica muito conteudo

**Objetivo:** Simplificar cada sugestão de busca para mostrar somente o título da notícia.

## 12. Atualização do contexto e registro
**Prompt:** verifique o arquivo AGENTS.md e registre os padroes identificados lá caso veja necessidade para aumentar o contexo em proximas interações, apos isso registre todos os prompts listados utilizados e seus objetivos no arquivo prompts.md

**Objetivo:** Atualizar o contexto de desenvolvimento e documentar os pedidos desta conversa com seus objetivos.
