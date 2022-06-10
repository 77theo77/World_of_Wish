<?php

use Utils\App;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'Utils/PlayerInterface.php';
require_once 'Utils/BaseJoueur.php';
require_once 'Utils/Guerrier.php';
require_once 'Utils/Archer.php';
require_once 'Utils/Druide.php';
require_once 'Utils/Pretre.php';
require_once 'Utils/Sorcier.php';
require_once 'Utils/App.php';

function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * @return App
 * @throws Exception
 */
function getApp() {
    start_session();

    if (! isset($_SESSION['app'])) {
        $_SESSION['app'] = serialize(new App());
    }

    if (isset($_SESSION['app'])) {
        return unserialize($_SESSION['app']);
    }

    throw new Exception('Echec de recuperation de partie.');
}

/**
 * @throws Exception
 */
function updateApp(&$app) {
    if (! $app) {
        throw new Exception('Pas de game en cours.');
    }

    start_session();
    $_SESSION['app'] = serialize($app);
}


try {
    $app = getApp();
//    var_dump($app);
//    echo "<hr>";
//    $app->chooseCoalition();
//    var_dump($app);
//    echo "<hr>";
//    $app->setUsername('Dyos');
//    $app->chooseCoalition();
//    var_dump($app);
//    echo "<hr>";
//    $app->logout();
//    var_dump($app);
//    echo "<hr>";

    if (!($app instanceof App)) {
        die('Erreur inattendue.');
    }
} catch (Exception $e) {
    die('Erreur inattendue: ' . $e->getMessage());
}

