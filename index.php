<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once 'includes/simple_html_dom.php';

    require_once "config.php";

    session_start();
    //register autoloader
    Twig_Autoloader::register();
    //loader for template files
    $loader = new Twig_Loader_Filesystem('templates');
    //twig instance
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));
    //load template file
    $twig->setCache(false);

    class Event {}

    $events = array();
    $measures = array();

    if(isset($_SESSION['user'])){
       
        $template = $twig->loadTemplate('index.html');
        echo $template->render(array('title' => 'Tester Demo', 'user' => 'admin')); 
        
    }
    else{
        $template = $twig->loadTemplate('login.html');
        echo $template->render(array('title' => 'Tester Demo'));
    }
    
?>
