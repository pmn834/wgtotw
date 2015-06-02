<?php
/**
 * Config-file for navigation bar.
 *
 */
 
 $userAcronym = $this->di->UserauthenticationController->getAcronym();
 
return [

    // Use for styling the menu
    'class' => 'userNavbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'login_name'  => [
            'text'  => 'Inloggad som ' . $userAcronym,
            'url'   => $this->di->get('url')->create('') . '/my_pages',
            'title' => ''
        ],
        
        // This is a menu item
        'my_pages'  => [
            'text'  => 'Mina sidor',
            'url'   => $this->di->get('url')->create('') . '/my_pages',
            'title' => 'Gå till Mina sidor'
        ],
 
        // This is a menu item
        'logout' => [
            'text'  =>'Logga ut',
            'url'   => $this->di->get('url')->create('') . '/Userauthentication/logout',
            'title' => 'Logga ut från Mina Sidor'
        ],
    ],


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
