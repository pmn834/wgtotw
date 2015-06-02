<?php

namespace Anax\UserNavbarAuth; 

/**
 * Class to handle user authentication, with login/logout and status.
 *
 */
class UserNavbarAuth implements \Anax\DI\IInjectionAware {
    
    use \Anax\DI\TInjectable;
    
    /**
     * Get the correct user navbar depending on
     * current login status.
     * 
     * @param
     * @return void
     */
    public function getUserNavbar() {
        if ($this->UserauthenticationController->isAuthenticated()) {
        $this->userNavbar->configure(ANAX_APP_PATH . 'config/auth_user_navbar.php');
        }
        else {
            $this->userNavbar->configure(ANAX_APP_PATH . 'config/user_navbar.php');
        }
    }
    
}