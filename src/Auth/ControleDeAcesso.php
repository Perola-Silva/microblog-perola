<?php

namespace Microblog\Auth;

use Microblog\Enums\TipoUsuario;

/* Sessões no PHP
Funcionalidade usada para controlar o acesso a determinadas páginas e recursos de um site/aplicação

Exemplo: áreas administrativas, painel de controle, áre de cliente/aluno.

Nessas areas o acesso depende de alguma forma de autenticação, como login e senha, biometria, por dois fatores, etc.
*/

final class ControleDeAcesso
{
    // Impedir a criação de objeto/instancia

    private function __construct() {}

    // Inicia a sessão se ela ainda não estiver ativa
    private static function iniciarSessao(): void
    {
        // Se o status da sessão NÃO FOR ativa (ou, se a sessão está inativa)
        if(session_status() !== PHP_SESSION_ACTIVE){
            // Então iniciamos a sessão
            session_start();
        }
    }

    private static function encerrarSessao(): void
    {
        $_SESSION = []; // limpar os dados da sessão
        session_destroy();  // destrói/remove quaisquer dados da sessão no navegador
    }

    public static function exigirLogin(): void
    {
        self::iniciarSessao();

        // Se NÃO EXISTIR na sessão uma variavel chamada id, significa que o usuaio NÃO ESTÁ logado
        if(!isset($_SESSION['id'])){
            // Encerrar sessão

            // redirecionar para a pagina de login
            header("location:../login.php?acesso_proibido");
            exit;
        }
    }

    public static function login(int $id, string $nome, string $tipo): void
    {
        self::iniciarSessao();

        // Cria as variaveis de sessão com os dados de quem está logado
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['tipo'] = $tipo;
    }

    public static function logout(): void
    {
        self::iniciarSessao();
        self::encerrarSessao();
        header("location:../login.php?logout");
    }

    public static function usuarioEhAdmin(): bool
    {
        self::iniciarSessao();
        // Se variavel de sesão for 'tipo' existe e o valor dela é 'admin'
        // Então significa que o usuario logado é um administrador
        return isset($_SESSION['tipo']) && $_SESSION['tipo'] === TipoUsuario::ADMIN->value;
    }
}