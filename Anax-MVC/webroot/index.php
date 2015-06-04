<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_db-driven-app.php'; 

// Set the title of the page
$app->theme->setVariable('title', "WGTOTW");

// Use clean links
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Theme configuration
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');

// Navbar
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_wgtotw.php');

// Load session service and start session
$app->session();

// =======================================================
//
// User navbar
//

$app->user_navbar_auth->getUserNavbar();

//---------------------------------------------------------

// ========================================================
// 
// Startpage route
// 
$app->router->add('', function() use ($app) {
    
    $app->theme->setTitle("Startsida");
    
    $content = $app->fileContent->get('home/home.md');
    $content = $app->textFilter->doFilter($content, 'markdown');

    $app->views->add('wgtotw/page', [
        'content' => $content,
    ]);
    
    $latest_questions = $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'getLatestQuestions',
        'params'     => ['3'],
    ]);

    $most_active_users = $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'mostActiveUsers',
        'params'     => ['3'],
    ]);

    $most_active_tags =  $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'mostActiveTags',
        'params'     => ['8'],
    ]);
    
});
// --------------------------------------------------------

// ========================================================
// 
// Questions route
// 
$app->router->add('question', function() use ($app) {
 
    $app->theme->setTitle("Senast publicerade frågor");

    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'getLatestQuestions',
        'params'     => ['10'],
    ]);
 
});
// --------------------------------------------------------

// ========================================================
// 
// Tags route
// 
$app->router->add('tags', function() use ($app) {
 
    $app->theme->setTitle("Taggar");

    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'getAllTags',
        'params'     => [''],
    ]);
 
});
// --------------------------------------------------------

// ========================================================
// 
// User route
// 
$app->router->add('user', function() use ($app) {
    
    $app->dispatcher->forward([
        'controller' => 'user',
        'action'     => 'list',
        'params'     => [''],
    ]);
});
// --------------------------------------------------------

// ========================================================
// 
// About route
// 
$app->router->add('about', function() use ($app) {
 
    $app->theme->setTitle("Om webbplatsen");
    
    $content = $app->fileContent->get('about/about.md');
    $content = $app->textFilter->doFilter($content, 'markdown');

    $app->views->add('wgtotw/page', [
        'content' => $content,
    ]);
    
});
// --------------------------------------------------------

// ========================================================
// 
// My pages route
// 
$app->router->add('my_pages', function() use ($app) {

        $app->dispatcher->forward([
        'controller' => 'Userauthentication',
        'action'     => 'userHome',
        'params'     => [''],
        ]);
});
// --------------------------------------------------------



$app->router->handle();

// Render the response using theme engine.
$app->theme->render();