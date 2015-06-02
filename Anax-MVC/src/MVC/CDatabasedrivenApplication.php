<?php

namespace Anax\MVC;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CDatabasedrivenApplication
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Construct.
     *
     * @param array $di dependency injection of service container.
     */
    public function __construct($di)
    {
        // Add CForm
        $di->set('form', '\Mos\HTMLForm\CForm');
        
        // Add FormController
        $di->set('FormController', function () use ($di) {
            $controller = new \Anax\HTMLForm\FormController();
            $controller->setDI($di);
            return $controller;
        });
        
        // Add CDatabaseBasic
        $di->setShared('db', function() {
            $db = new \Anax\Database\CDatabaseBasic();
            $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
            $db->connect();
            return $db;
        });
        
        $di->setShared('userNavbar', function () use ($di) {
            $navbar = new \Anax\Navigation\CNavbar();
            $navbar->setDI($di);
            return $navbar; 
        });

        $di->set('QuestionController', function() use ($di) {
            $controller = new \Anax\Question\QuestionController();
            $controller->setDI($di);
            return $controller;
        });

        $di->set('CommentController', function() use ($di) {
            $controller = new \Anax\Comment\CommentController();
            $controller->setDI($di);
            return $controller;
        });

        $di->set('UserController', function() use ($di) {
            $controller = new \Anax\User\UserController();
            $controller->setDI($di);
            return $controller;
        });

        $di->setShared('flashmessage', function() {
            $flashmessage = new \Anax\CFlashmessage\CFlashmessage('nofa');
            return $flashmessage;
        });
        
        $di->setShared('UserauthenticationController', function() use ($di) {
            $user_authentication = new \Anax\UserAuthentication\UserAuthenticationController();
            $user_authentication->setDI($di);
            return $user_authentication;
        });

        $di->set('user_navbar_auth', function () use ($di) {
            $nb = new \Anax\UserNavbarAuth\UserNavbarAuth();
            $nb->setDI($di);
            return $nb;
        });
        
        $this->di = $di;
    }
}
