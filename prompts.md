# Prompts para o gerenciamento de noticias

1. Pedir pra IA analisar o projeto no estado atual

Analise todas as classes utilizadas até o momento (contidas na pasta src), em especial
a classe NoticiaServico e a classe Noticia.

Veja também as páginas administrativas, em especial usuários.php e categorias.php.

Identifique padrões, como nomes de métodos, propriedades, variáveis, arquivos, namespaces.

Aguarde o próximo comando.


---

2. Criação do método listarTodos

Crie na classe NoticiaServico o método listarTodos, de maneira semelhante ao que foi feito em CategoriaServico e UsuarioServico.

Atenção: a consulta SQL deve no caso de usuários admin, carregar todas as noticias incluindo o nome do autor delas. Se for um usuário editor, a consulta deve carregar apenas as noticias dele próprio, sem incluir o nome do autor.










Tarefas:

- visualizar noticias e nome do autor delas (admin)
- visualizar noticias próprias (editor)

---

## Prompts desta conversa

### 1. Análise do projeto

Analise todas as classes utilizadas até o momento (contidas na pasta src), em especial a classe NoticiaServico e a classe Noticia. Veja também as páginas administrativas, em especial as páginas que gerenciam usuarios categorias e noticias. Identifique padrões, como nomes de métodos, arquivos, propriedades, variáveis, arquivos, namespaces. Aguarde o proximo comando.

### 2. Criação do AGENTS.md

Gere um arquivo AGENTS.md contendo todas as informações essenciais a respeito do desenvolvimento e características deste projeto. A ideia é que este arquivo sirva de referência para outras IAs ou caso ocorra a troca de máquina/VSCode, situação em que não há um histórico de conversação.

### 3. Busca de notícia por ID

Programe em NoticiaServico o metodo buscarPorId. leve em consideração que o SQL deve ser ajustado para, quando for usuarios do tipo admin, carregar/exibir a noticia de qualquer pessoa com o id usuario e o id da noticia. se for usuário do tipo editor ele pode apenas carregar/exibir suas próprias noticias. Faça isso e aguarde o proximo comando.

### 4. Carregamento da página de atualização

Analise a página admin/noticias.php e veja se o link dinamico para a pagina admin/noticia-atualiza.php está correto. Em seguida, programe a pagina admin/noticia-atualiza.php permitindo que ela exiba os dados da noticia a ser atualizada (conforme link que o usuario adcionar em noticias.php)

### 5. Detalhes do carregamento da página de atualização

Analise a página admin/noticias.php e veja se o link dinamico para a pagina admin/noticia-atualiza.php está correto. Em seguida, programe a pagina admin/noticia-atualiza.php permitindo que ela exiba os dados da noticia a ser atualizada (conforme link que o usuario adcionar em noticias.php)

atenção o selct de categorias deve carregar todas as categorias existentes e deve trazer selecionada a categoria da noticia escolhida

não implemente ainda o codigo para atualizar noticia foque apenas no carregamento e exibição dos dados da pagina

### 6. Atualização no serviço de notícias

programe o metodo atualiza no arquivo NoticiaServico, escreva no mesmo padrão dos outros arquivos de serviço escritos anteriormente para manter um padrão, não esqueca das validações de erros, apenas crie o metodo e aguarde o proximo comando por favor

### 7. Atualização no formulário de notícia

Agora impemente o metodo atualiza na pagina admin/noticia-atualiza.php

### 8. Registro dos prompts

atualize o arquivo prompts.md para adicionar todos os prompts desta conversa

### 9. Método de exclusão de notícia

Com base nos metodos de classes anteriores crie o metodo excluir no arquivo NoticiaServico, não implemente a exclusão ainda, aguarde o proximo comando

### 10. Página de exclusão de notícia

na pasta admin modifique o arquivo noticia-exclui.php, usando a mesma estrutura dos arquivos de exclusão anteriores como o categoria-exclui.php

### 11. Atualização do registro de prompts

insisra os prompts utilizados no arquivo prompt.md