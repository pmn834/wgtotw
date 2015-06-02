<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // Startpage menu item
        'home'  => [
            'text'  => '<i class="fa fa-home"></i> Startsida',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Webbplatsens startsida'
        ],

        // Redovisningar menu item
        'redovisning' => [
            'text'  =>'<i class="fa fa-newspaper-o"></i> Redovisning',
            'url'   => $this->di->get('url')->create('redovisning'),
            'title' => 'Redovisning av de olika kursmomenten'
        ],
        
        // Theme menu item
        'theme' => [
            'text'  =>'<i class="fa fa-puzzle-piece"></i> Tema',
            'url'   => $this->di->get('url')->create('theme.php'),
            'title' => 'Tema för Anax-MVC'
        ],
        
        // Users menu item
        'users' => [
            'text'  =>'<i class="fa fa-user"></i> Users',
            'url'   => $this->di->get('url')->create('users.php'),
            'title' => 'Test av användarhantering'
        ],
        
        // Users menu item
        'flash' => [
            'text'  =>'<i class="fa fa-git-square"></i> Flash',
            'url'   => $this->di->get('url')->create('flash'),
            'title' => 'Flashmessage för Anax MVC'
        ],

        // Source menu item
        'source' => [
            'text'  =>'<i class="fa fa-file-code-o"></i> Källkod',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Granska källkod'
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
