<?php

    require_once __DIR__ . '/vendor/autoload.php';


    $loader = new \Twig\Loader\FilesystemLoader('plantillas');
    $twig = new \Twig\Environment($loader);

    echo $twig->render('plantilla_test.twig', []);

?>