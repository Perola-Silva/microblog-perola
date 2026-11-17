# Resumo do histórico das branches

Atualizado em 17/11/2026. Este resumo considera as branches locais e as referências remotas `origin/*` disponíveis neste clone. Os pontos abaixo destacam as mudanças de cada branch em relação à anterior.

## Histórico por branch

### `main` e `00_versao-inicial` — versão-base

As duas referências apontam para o mesmo commit (`793d41f`). Esta etapa estabelece a base do projeto Microblog, com as páginas PHP, os arquivos SQL e a estrutura inicial usada nas aulas seguintes.

### `01_aula-0311` — autoload e listagem de usuários

Foi configurado o autoload PSR-4 e iniciado o namespace `Microblog`. A branch introduziu `UsuarioServico` e a listagem dinâmica de usuários no painel administrativo. Commits de referência: `13ac36d`, `5c44c84`, `fce5644`, `f3f79ad`, `9a71df8` e `03f5fa6`.

### `02_aula0411` — cadastro e edição de usuários

Foram adicionados os fluxos de inserção, busca por ID, carregamento e edição de usuários. O ID do modelo passou a ser opcional para permitir novos cadastros, e houve tratamento de erros nos formulários. Também foi criado o primeiro `resumo.md`. Commits de referência: `c1bdf8f`, `12e97c5`, `76113f2`, `c27729b`, `faeeaf4`, `c8d7412`, `5034811`, `a94e248` e `2ca9964`.

### `03_aula0511` — atualização de usuários

Foi concluído o fluxo de atualização de usuários, com validação dos dados e correção do tratamento de senha. A branch também registrou a evolução do projeto em documentação e no log. Commits de referência: `fe68bfe`, `cb8aa47`, `3295e1b`, `f33d443` e `63398be`.

### `04_exercicio-categorias` — CRUD de categorias

O painel passou a listar, inserir, buscar, atualizar e excluir categorias. Também foram adicionadas mensagens de erro e contagens dinâmicas na listagem. Commits de referência: `6cf4fa0`, `dc7af98`, `8461cb7`, `ac13ec7`, `e2b685a`, `c986b51`, `569c663`, `1044ce0`, `5e065dd` e `afa3e93`.

### `05_aula-1011` — sessões e início da autenticação

Foram implementados o controle de acesso baseado em sessão, a proteção do cabeçalho administrativo e o feedback/validação do login. A exclusão de usuários também foi integrada. Commits de referência: `2fb7352`, `cc1d71a`, `7f58396` e `ce76853`.

### `06_aula-1211` — login e permissões por perfil

O login passou a buscar usuários por e-mail, e o fluxo de autenticação foi completado com verificações de administrador. Páginas e menus de usuários e categorias foram protegidos; também foram feitos o carregamento e a atualização do perfil e a prevenção de autoexclusão. Um commit ajustou a senha local do banco. Commits de referência: `a0efa7a`, `ec2892e`, `e2dcce2`, `621cd55`, `edc4e1c`, `bd9ddde`, `541aba2`, `c721c6c`, `a8057fb`, `391ff6a`, `785f614` e `ca7d65d`.

### `07_aula-1311` — registro histórico sem referência Git disponível

A versão anterior deste resumo atribuía a esta branch o reforço da autenticação e permissões, além dos primeiros fluxos de gerenciamento de notícias. Porém, `07_aula-1311` não aparece entre as branches locais ou remotas deste clone, e os commits citados na versão anterior não puderam ser confirmados nas referências atuais. Esse registro é mantido como histórico herdado, não como atribuição verificada.

### `08_aula1311` — gerenciamento de notícias por perfil

Foi introduzida a gestão de notícias de acordo com o perfil do usuário, separando o acesso de editores e administradores. Commit de referência: `27e2b16`.

### `09_aula1611` — edição e exclusão de notícias

Foram organizados os arquivos de imagem, adicionados dados ao guia de desenvolvimento e implementados o carregamento e a atualização de notícias. A edição e a exclusão passaram a respeitar as permissões por usuário, e o tipo de usuário foi inicializado no fluxo de edição. Commits de referência: `1596bb2`, `0c04850`, `b6c6701`, `74942f3`, `00a5f2c` e `540d9c2`.

### `10_aula1711` — páginas públicas de notícias e categorias

As páginas públicas passaram a exibir notícias em destaque e listagens do banco. Foram implementados os detalhes públicos da notícia, o fallback de autoria após exclusão do usuário, o menu dinâmico de categorias e a listagem por categoria. O upload ganhou alternativa quando `fileinfo` não está habilitada. Commits de referência: `85ba825`, `04a8c77`, `94cea0f`, `b1ed1b8`, `cd09bce` e `823a656`.

### `10_desafio1711` — busca dinâmica

A branch atualiza a busca incremental no cabeçalho. `NoticiaServico` pesquisa título, resumo e texto; `resultados.php` retorna sugestões com links para as notícias, e `js/busca.js` atualiza a interface enquanto o usuário digita. As sugestões exibem apenas os títulos. Commit de referência: `5625863`.

## Estado consolidado

O projeto evoluiu de páginas PHP iniciais para um portal com autenticação e permissões por perfil, gerenciamento administrativo de usuários, categorias e notícias, e páginas públicas conectadas ao banco. O caminho principal de evolução é `main`/`00` → `01` → `02` → `03` → `04` → `05` → `06` → `08` → `09` → `10_aula` → `10_desafio`. A branch `07_aula-1311` consta apenas no resumo antigo e não está disponível nas referências Git consultadas.
