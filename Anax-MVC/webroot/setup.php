<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_db-driven-app.php'; 

// Set the title of the page
$app->theme->setVariable('title', "Installation/Återställning av webbplatsen");

$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid_simple.php');
$app->theme->addStylesheet("css/flashmessage.css");

// Add controller for Setup
$di->setShared('SetupController', function() use ($di) {
    $controller = new \Anax\Setup\SetupController();
    $controller->setDI($di);
    return $controller; 
});

$app->session(); 

// ========================================================
// 
// Main page route
// 
$app->router->add('', function() use ($app) {
    
    $content = $app->fileContent->get('/setup/setup.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');


    $app->views->add('wgtotw/page', [
        'content' => $content,
    ]); 

});

// --------------------------------------------------------
$app->router->add('blank', function() use ($app) {
    
    $app->dispatcher->forward([
        'controller' => 'setup',
        'action'     => 'create',
        'params'     => [''],
    ]);
    
    $app->flashmessage->success("En blank mall för webbplatsen har skapats.");
    
    $output = $app->flashmessage->output();
    $output .= '<p><a href="../">Till startsidan</a></p>';

    $app->views->add('wgtotw/page', [
        'content' => $output,
    ]);
});  

// --------------------------------------------------------
$app->router->add('w_content', function() use ($app) {
    
    $app->dispatcher->forward([
        'controller' => 'setup',
        'action'     => 'create',
        'params'     => [''],
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'setup',
        'action'     => 'populate',
        'params'     => [''],
    ]);
    
    $app->flashmessage->success("Webbplatsen har skapats med testanvändare och exempel-innehåll.");
    
    $output = $app->flashmessage->output();
    $output .= '<p><a href="../">Till startsidan</a></p>';

    $app->views->add('wgtotw/page', [
        'content' => $output,
    ]);
    
});

// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the page
$app->theme->render();