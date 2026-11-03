<?php

    require_once __DIR__ . '/vendor/autoload.php';

    // Patio de pruebas de Dani
    require_once __DIR__ . "/api/admin_functions.php";
    echo getNextUserID();
    echo "\n";
    if(userExists("Estudiante de pruebas")){
        echo "Si";
    } else echo "No";
    echo "\n";
    if(insertStudent("patata", "contraseña de prueba")){
        echo "Si";
    } else echo "No";
    echo "\n";


    $loader = new \Twig\Loader\FilesystemLoader('plantillas');
    $twig = new \Twig\Environment($loader);

    echo $twig->render('plantilla_test.twig', []);

?>