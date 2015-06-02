wgtotw
======

Project for the course *Databasdrivna webbapplikationer med PHP och MVC-ramverk*, a community site for questions/answers on a chosen subject.

Uses the PHP-based and MVC-inspired (micro) framework / webbtemplate / boilerplate for websites and webbapplications, [Anax-MVC](https://github.com/mosbth/Anax-MVC).



License
------------------

This software is free software and carries a MIT license.



Use of external libraries
-----------------------------------

The following external modules are included and subject to its own license.



### Modernizr
* Website: http://modernizr.com/
* Version: 2.6.2
* License: MIT license
* Path: included in `webroot/js/modernizr.js`



### PHP Markdown
* Website: http://michelf.ca/projects/php-markdown/
* Version: 1.4.0, November 29, 2013
* License: PHP Markdown Lib Copyright © 2004-2013 Michel Fortin http://michelf.ca/
* Path: included in `3pp/php-markdown`




Setup
-----------------------------------

1. Locate the folder `wgtotw/Anax-MVC/app/config`.

2. Create a new file called `config_mysql.php` with the following content:

```php
<? php
    return [
        'dsn'               => "mysql:host=REPLACE;dbname=REPLACE;",
        'username'          => "REPLACE",
        'password'          => "REPLACE",
        'driver_options'    => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
        'table_prefix'      => "mvc_",
    ];
```
Substitute every occurrence of `REPLACE` with your own MySQL-settings and save these changes.

3. Locate the folder `wgtotw/Anax-MVC/webroot/css`. The subfolder `anax-grid` will need changed access permissions, set these to 777 for the folder and all its content. 

4. Open the file `setup.php` from `wgtotw/Anax-MVC/webroot/` in a web browser to start the automatic setup-process. Follow the on-screen instructions to finish the setup.
