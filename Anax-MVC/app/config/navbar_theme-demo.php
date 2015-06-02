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
        
        // Startpage menu item
        'tema'  => [
            'text'  => '<i class="fa fa-info"></i> Om temat',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Om detta tema'
        ],

        // Regioner menu item
        'regions' => [
            'text'  =>'<i class="fa fa-cogs"></i> Regioner',
            'url'   => $this->di->get('url')->create('regioner'),
            'title' => 'Regioner för temat'
        ],

        // Typography menu item
        'typography' => [
            'text'  =>'<i class="fa fa-font"></i> Typografi',
            'url'   => $this->di->get('url')->create('typografi'),
            'title' => 'Temats typografiska rutnät'
        ],

        // Font Awesome menu item
        'fa' => [
            'text'  =>'<i class="fa fa-flag"></i> Font Awsome',
            'url'   => $this->di->get('url')->create('font_awesome'),
            'title' => 'Font Awesome i temat'
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