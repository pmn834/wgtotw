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
            'text'  => '<i class="fa fa-chevron-left"></i> Tillbaka',
            'url'   => $this->di->get('url')->createRelative(''),
            'title' => 'Tillbaka till webbplatsens startsida'
        ],
        
        // Show all users menu item
        'tema'  => [
            'text'  => '<i class="fa fa-users"></i> Visa alla',
            'url'   => $this->di->get('url')->create('users/list'),
            'title' => 'Visa lista med alla användare'
        ],

        // Add new user menu item
        'add_user' => [
            'text'  =>'<i class="fa fa-user-plus"></i> Ny användare',
            'url'   => $this->di->get('url')->create('users/add'),
            'title' => 'Lägg till en ny användare'
        ],

        // Users in trashcan menu item
        'trashcan' => [
            'text'  =>'<i class="fa fa-trash-o"></i>',
            'url'   => $this->di->get('url')->create('users/trash'),
            'title' => 'Visa användare placerade i papperskorgen'
        ],
        
        // Deactivated users menu item
        'deactivated' => [
            'text'  =>'<i class="fa fa-ban"></i>',
            'url'   => $this->di->get('url')->create('users/deactivated'),
            'title' => 'Visa användare som har avaktiverats'
        ],
        
        // Active users menu item
        'active' => [
            'text'  =>'<i class="fa fa-check-circle-o"></i>',
            'url'   => $this->di->get('url')->create('users/active'),
            'title' => 'Visa användare som är aktiva och ej i papperskorgen'
        ],

        // Font Awesome menu item
        'fa' => [
            'text'  =>'<i class="fa fa-bolt"></i>',
            'url'   => $this->di->get('url')->create('setup'),
            'title' => 'Återställ användardatabasen'
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