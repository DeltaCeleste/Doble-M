<?php

    require_once __DIR__ . '/vendor/autoload.php';

    require_once __DIR__ . '/utils/functions.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    echo $twig->render('test.twig', []);

?>