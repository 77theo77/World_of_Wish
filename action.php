<?php

use Utils\BaseJoueur;
use Utils\Guerrier;

require_once 'helpers.php';

if (! empty($_POST)) {
    try {
        if (isset($_POST['username'])) {
            $username = trim(htmlentities(htmlspecialchars($_POST['username'])));
            $side = trim(htmlentities(htmlspecialchars($_POST['side'])));
            if (isset($app)) {
                $app->setUsername($username);
                $app->setSide($side);
                updateApp($app);
            }
        }
        if (isset($_POST['player'])) {
            $player = trim(htmlentities(htmlspecialchars($_POST['player'])));
            if (isset($app)) {
                $app->setPlayer($player);
                updateApp($app);
            }
        }
    } catch (Exception $e) {
        die('Erreur inattendue: ' . $e->getMessage());
    }

//} else if () {
//    echo json_encode([
//        'app' => 'app'
//    ]);

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (isset($app)) {
        $data = [];
        $input = file_get_contents('php://input');
        if ($input) {
            $data = json_decode($input, JSON_OBJECT_AS_ARRAY);
        }

        if (isset($data['arme'])) {
            /** @var BaseJoueur $player */
            $player = $app->getPlayer();
            /** @var BaseJoueur $player */
            $opponent = $app->getOpponent();

            if ($player && $opponent) {
                $opponent->attack($player);
                if ($player instanceof Guerrier) {
                    $player->parer($opponent->armes[0]);
                }
                $player->attackWith($opponent, $data['arme']);
                $app->updatePlayer($player);
                $app->updateOpponent($opponent);
                updateApp($app);
            }
        }
        echo json_encode([
            'app' => $app
        ]);
        die();
    } else {
        die('Erreur inattendue.');
    }
}

header('Location: index.php');
