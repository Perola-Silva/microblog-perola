# Guia de desenvolvimento do Microblog

Este arquivo registra a arquitetura, as convenções e o estado atual do projeto para orientar futuras alterações, inclusive em novos ambientes de desenvolvimento. Ao implementar uma funcionalidade, confirme o comportamento no código atual: algumas telas continuam sendo protótipos estáticos.

## Visão geral

Microblog é um portal didático de notícias feito em PHP, com páginas públicas e um painel administrativo. O código de domínio está em `src/`, as páginas PHP são organizadas na raiz e em `admin/`, e a interface usa Bootstrap, Bootstrap Icons e folhas/scripts locais.

## Ambiente e execução

- Projeto preparado para execução local com Apache (por exemplo, XAMPP), PHP e MySQL/MariaDB.
- O `README.md` documenta PHP 8.5+, as extensões obrigatórias `pdo_mysql` e `mbstring`, e `fileinfo` como recomendada (o upload de imagens usa `getimagesize()` como alternativa se ela estiver ausente).
- O autoload PSR-4 está configurado em `composer.json`: `Microblog\` aponta para `src/`.
- As páginas carregam classes com `vendor/autoload.php`. Depois de alterar o mapeamento do Composer, regenere o autoload com `composer dump-autoload`. Não edite arquivos gerados em `vendor/` manualmente.
- A conexão é configurada em `src/Database/ConexaoBD.php`; ela usa PDO para o banco `microblog_2026`. Ajuste as configurações locais nesse arquivo conforme necessário.
- Para preparar o banco local, importe `_materiais/microblog_2026_completo.sql` no MySQL/MariaDB. `_materiais/microblog_2026_inicial.sql` também está disponível como estrutura/dados iniciais.
- `logs/erros.log` é o destino de log configurado pela aplicação. Garanta que o processo PHP possa gravar nesse local.
- Não há configuração de testes automatizados, lint ou scripts de aplicação definidos no `composer.json`. Faça validações PHP locais nas páginas e fluxos alterados quando possível.

## Estrutura

- `src/Models/`: modelos de domínio (`Usuario`, `Categoria`, `Noticia`).
- `src/Services/`: consultas e operações de banco (`UsuarioServico`, `CategoriaServico`, `NoticiaServico`).
- `src/Database/`: conexão PDO (`ConexaoBD`).
- `src/Enums/`: tipos de usuário e destaque.
- `src/Helpers/`: normalização, escaping, logs, upload e validações (`Utils`, `Validacoes`).
- `src/Auth/`: controle de sessão e autorização (`ControleDeAcesso`).
- `admin/`: páginas e ações do painel; scripts específicos em `admin/js/`.
- `includes/`: cabeçalhos, rodapés e blocos compartilhados.
- `images/`: imagens do site e uploads de notícias.
- `css/`, `js/`: estilos e scripts do portal público.
- `_materiais/`: dumps SQL e material de apoio didático.

## Arquitetura e convenções de código

- O projeto usa PHP com namespaces sob `Microblog\...`; mantenha o caminho da classe alinhado ao namespace e ao autoload PSR-4.
- Classes e enums usam PascalCase; métodos, propriedades e variáveis usam camelCase. Nomes de classes, métodos, variáveis e arquivos são predominantemente em português.
- Páginas PHP administrativas usam nomes kebab-case e verbos como `insere`, `atualiza` e `exclui` (por exemplo, `noticia-insere.php`).
- Modelos são classes com propriedades privadas tipadas, construtores e getters `get...()`. Em geral, IDs são `?int` e opcionais no final do construtor. Os serviços recebem modelos em operações de escrita.
- Serviços declaram uma propriedade `PDO $conexao`, inicializada por `ConexaoBD::getConexao()`. Consultas usam prepared statements e parâmetros nomeados quando recebem entrada. Leituras atualmente retornam arrays associativos e escritas retornam `void`.
- Serviços registram erros PDO usando `Utils::registrarLog($e)` e lançam uma exceção com mensagem genérica para a camada de apresentação. Páginas normalmente mostram mensagens amigáveis, sem expor detalhes internos.
- `Destaque` e `TipoUsuario` são enums string; converta valores recebidos de formulários com `tryFrom()` para validação ou `from()` depois da validação.
- Em formulários, normalize com `Utils::normalizarEntrada()`, valide com os métodos específicos de `Validacoes`, preserve valores submetidos quando apropriado, escape toda saída dinâmica com `Utils::escaparHtml()` e redirecione após sucesso.
- Nas páginas administrativas, carregue o autoload antes de usar classes e aplique `ControleDeAcesso::exigirLogin()` ou `exigirAdmin()` antes de operações protegidas. O cabeçalho administrativo também verifica login, mas a autorização deve estar explícita na página conforme o padrão existente.
- Ao lidar com sessão e formulários, mantenha o tratamento de erros existente: erros de validação (`InvalidArgumentException`) são apresentados ao usuário; falhas inesperadas são registradas e recebem mensagem genérica.
- Para mudanças manuais, prefira alterações pequenas e coerentes com os padrões existentes. Atualize documentação diretamente relacionada quando o comportamento ou a configuração mudar.

## Domínio e banco de dados

O dump completo contém as tabelas:

- `usuarios`: `id`, `nome`, `email` (único), `senha` (hash) e `tipo` (`admin` ou `editor`).
- `categorias`: `id` e `nome`.
- `noticias`: `id`, `data` (padrão `CURRENT_TIMESTAMP`), `titulo`, `texto`, `resumo`, `imagem`, `destaque` (`sim` ou `nao`), `usuario_id` e `categoria_id`.

As referências da notícia para usuário e categoria aceitam `NULL`; as chaves estrangeiras usam `ON DELETE SET NULL`. A conexão PDO configura `utf8mb4`, modo de erro por exceção e `PDO::FETCH_ASSOC`.

`_materiais/modelagem-fisica.md` ainda documenta um banco `microblog_2025`; para o estado atual, use como referência o banco `microblog_2026` e os dumps correspondentes.

## Estado funcional conhecido

### Autenticação e permissões

- `ControleDeAcesso` mantém `id`, `nome` e `tipo` na sessão; oferece `exigirLogin()`, `login()`, `logout()`, `usuarioEhAdmin()` e `exigirAdmin()`.
- `login.php` busca usuário por e-mail e verifica senhas com `password_verify()`. Novas senhas são codificadas com `password_hash()` via `Utils::codificarSenha()`.
- Editores podem acessar o painel, atualizar o próprio perfil e gerenciar as próprias notícias.
- Administradores podem também gerenciar usuários e categorias e visualizar notícias de todos os autores.

### Usuários e categorias

- `UsuarioServico` e `CategoriaServico` implementam listagem, inserção, busca por ID, atualização e exclusão.
- As páginas administrativas de usuários e categorias têm fluxos de listagem, cadastro, atualização e exclusão. O gerenciamento dessas entidades é restrito a administradores; `meu-perfil.php` é acessível a qualquer usuário autenticado.
- A exclusão da própria conta é impedida em `admin/usuario-exclui.php`.

### Notícias e páginas públicas

- `NoticiaServico::listarTodos()` lista todas as notícias com autor para administradores e somente as notícias do usuário corrente para editores. `NoticiaServico::inserir()` grava uma notícia.
- `Noticia` representa título, texto, resumo, imagem, destaque, autor, categoria e, opcionalmente, ID e data. `Validacoes` valida campos relacionados a notícias; `Utils::upload()` valida e salva imagens em `images/`.
- O cadastro administrativo de notícias carrega categorias, valida os dados e faz upload da imagem.
- `NoticiaServico::buscarPorId()` carrega uma notícia pelo ID para administradores e restringe editores às próprias notícias; `NoticiaServico::atualizar()` grava os campos editáveis da notícia usando seu ID; `NoticiaServico::excluir()` executa a exclusão por ID. `admin/noticia-atualiza.php` valida e salva o formulário, preservando a imagem atual quando não há novo upload. `admin/noticia-exclui.php` valida o ID e a permissão do usuário antes de excluir.
- `NoticiaServico::listarDestaques()` retorna somente notícias destacadas para `index.php`; `listarTodasPublicas()` alimenta `includes/todas.php`; `listarPorCategoria()` atende `noticias-por-categoria.php`; `buscarPublicaPorId()` carrega os detalhes públicos da notícia; e `buscarPorTermo()` pesquisa notícias por título, resumo e texto.
- As páginas públicas `index.php`, `noticia.php`, `noticias-por-categoria.php` e o bloco `includes/todas.php` carregam dados do banco. A exclusão do usuário deixa `noticias.usuario_id` nulo (`ON DELETE SET NULL`): páginas públicas exibem “Equipe Microblog” e a listagem administrativa exibe “Usuário deletado” nesse caso.
- A busca pública é incremental: o formulário em `includes/cabecalho.php` chama `js/busca.js`, que consulta `resultados.php`; o endpoint retorna um fragmento HTML seguro com até 10 títulos, enquanto o serviço pesquisa título, resumo e texto.
- `admin/js/confirm.js` implementa confirmação visual para links `.excluir`; isso não substitui validação/autorização no servidor.

## Cuidados para mudanças

- Não assuma que protótipos estáticos ou itens descritos em material didático já estão integrados ao banco; confira a implementação antes de estender.
- Não exponha erros PDO, caminhos locais, hashes de senha ou dados de sessão na interface.
- Use consultas parametrizadas para entradas externas e escape valores antes de inseri-los no HTML.
- Preserve as diferenças de permissão entre editor e administrador no servidor, não apenas ocultando links.
- Ao alterar schema, mantenha o SQL, os serviços, os modelos e os formulários coerentes. Os dumps em `_materiais/` são referências para o banco local.
- Evite adicionar dependências ou ferramentas de teste sem necessidade; atualmente o Composer contém apenas a configuração de autoload do projeto.
