<?php
require_once 'helpers.php';
?><!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>World of Wish</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>

<div class="bg-primary text-white px-5 py-2 d-flex justify-content-between">
    <span>
        Vie:
        <strong id="vie">
            <?= $app->getLife(); ?>
        </strong>
    </span>

    <span class="<?= ! $app->hasStarted()? 'd-none' : ''; ?>">
        nom:
        <strong>
            <?= $app->getUsername(); ?>
        </strong>
    </span>

    <div class="<?= ! $app->hasStarted()? 'd-none' : ''; ?>">
        <a href="logout.php" class="btn btn-dark">
            deconnecte
        </a>
    </div>
</div>
<div class="container">
    <h1>Zone de jeu</h1>
    <p>Bienvenue à <strong>World of Wish</strong></p>
    <div class="card mt-3">
        <div class="card-body">
            <?php
                if (! $app->hasSide()):
            ?>
                <form action="action.php" method="post" onsubmit="return checkSide()">
                    <div class="form d-flex flex-column align-items-center">
                        <h3>Chosir ton équipe</h3>
                        <div class="form-group d-flex">
                            <?php
                                foreach ($app->getSides() as $index => $side):
                            ?>
                                <div class="<?= $index > 0 ? 'ms-4' : ''; ?>">
                                    <label class="btn btn-secondary" for="side-<?= $side; ?>"><?= ucfirst($side);?> </label>
                                    <input type="radio" class="d-none" onchange="changeSideButton('side-<?= $side; ?>')"
                                           value="<?= $side; ?>" name="side" id="side-<?= $side; ?>">
                                </div>
                            <?php
                                endforeach;
                            ?>
                        </div>
                        <div class="form-group mt-3">
                            <label for="username">pseudo: </label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>
                        <div class="mt-4 d-flex justify-content align-self">
                            <button type="submit" class="btn btn-primary">
                                Commencer
                            </button>
                        </div>
                    </div>
                </form>
            <?php
                elseif(!$app->hasPlayer()):
            ?>
                <form action="action.php" method="post" onsubmit="checkPlayer()">
                    <div class="form d-flex flex-column align-items-center">
                        <h3>Chosir ta classe de joueur</h3>
                        <div class="form-group d-flex">
                            <?php
                                foreach ($app->getPlayers() as $index => $player):
                            ?>
                            <div class="d-flex flex-row"  style="display:flex ;">
                                <label class="btn   btn-primary:hover" for="player-<?= $player; ?>"><?= ucfirst($player);?> </label>
                                <input type="radio" class="d-none" onchange="changePlayer('player-<?= $player; ?>')"
                                       value="<?= $player; ?>" name="player" id="player-<?= $player; ?>">
                            </div>
                            <?php
                                endforeach;
                            ?>
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Demarrer la partie
                            </button>
                        </div>
                    </div>
                </form>
            <?php
                else:
            ?>
                <?php
                    $player = $app->getPlayer();
                    $opponent = $app->getOpponent();
                ?>
                <?php
                    if ($player->getLife() === 0):
                ?>
                    <div class="card">
                        <p class="alert alert-danger">
                            You losed
                        </p>
                    </div>
                <?php
                    elseif ($opponent->getLife() === 0):
                ?>
                    <div class="card">
                        <p class="alert alert-success">
                            You win
                        </p>
                    </div>
                <?php
                    else:
                ?>
                    <div class="container d-flex justify-content-between">
                        <div class="card">
                            <div class="card-header bg-primary">
                                You (<?= $player->role(); ?>) => <?= $player->life; ?>
                            </div>
                            <div class="card-body">
                                <h3>Your arms</h3>
                                <form action="action.php" method="post" onsubmit="return false">
                                    <p>
                                        Bouclier <?= $player->bouclier ? 'actif' : 'desactivé'; ?>
                                        <?php
                                            if ($player->bouclier):
                                        ?>
                                            <label for="bouclier" class="btn btn-secondary">
                                                Parer
                                            </label>
                                            <input type="radio" name="arme" onchange="attaquer('BOUCLIER')"
                                                   id="bouclier" class="d-none" value="bouclier">
                                        <?php
                                            endif;
                                        ?>
                                        <?php
                                            if (! empty($player->defenses)):
                                                foreach($player->defenses as $defense)
                                        ?>
                                            <label for="<?= $defense; ?>" class="btn btn-secondary">
                                                Defendre avec <?= $defense; ?>
                                            </label>
                                            <input type="radio" name="arme" onchange="attaquer('<?= $defense; ?>')"
                                                   id="<?= $defense; ?>" class="d-none" value="<?= $defense; ?>">
                                        <?php
                                            endif;
                                        ?>
                                        <?php
                                            if (! empty($player->techniques)):
                                                foreach($player->techniques as $technique)
                                        ?>
                                            <label for="<?= $technique; ?>" class="btn btn-secondary">
                                                Utiliser <?= $technique; ?>
                                            </label>
                                            <input type="radio" name="arme" onchange="attaquer('<?= $technique; ?>')"
                                                   id="<?= $technique; ?>" class="d-none" value="<?= $technique; ?>">
                                        <?php
                                            endif;
                                        ?>
                                    </p>
                                    <br/>
                                    <p>
                                        <?php
                                            foreach($player->armes as $index => $arme):
                                        ?>
                                            <label class="btn btn-primary" for="arme<?= $index; ?>">
                                                Attaque avec <?= $arme; ?>
                                            </label>
                                            <input type="radio" onchange="attaquer('<?= $arme; ?>')"
                                                   name="arme" id="arme<?= $index; ?>" value="<?= $arme; ?>" class="d-none">
                                            <br/>
                                            <br/>
                                        <?php
                                            endforeach;
                                        ?>
                                    </p>
                                </form>
                            </div>
                        </div>
                        <div class="card" style="max-width: 250px;">
                            <div class="card-body">
                                <div class="d-flex flex-column justify-content-center">
                                    <h2>Arms value</h2>
                                    <div class="">
                                        <p>
                                            <?php
                                                foreach ($app->allArmes() as $arme => $value):
                                            ?>
                                                <?= $arme; ?> => <?= $value; ?>
                                                <br/>
                                            <?php
                                            endforeach;
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger">
                                Adversaire (<?= $opponent->role(); ?>) => <?= $opponent->life; ?>
                            </div>
                            <div class="card-body">
                                <h3>Your arms</h3>
                                <p>
                                    <?php
                                        foreach ($opponent->armes as $arme):
                                    ?>
                                        <?= $arme; ?>
                                        <br/>
                                    <?php
                                        endforeach;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php
                    endif;
                ?>
            <?php
                endif;
            ?>
        </div>
<!--        <div class="card-footer">-->
<!--            --><?php
//                var_dump($app);
//            ?>
<!--        </div>-->
    </div>

</div>


<script src="js/bootstrap.js"></script>
<script>
    window.addEventListener('load', () => {
    })

    function attaquer(arme) {
      console.log(arme)
      let formData = {
        "arme": arme,
      };
      let options = {
        method: 'PUT',
        headers: {
          'Content-Type':
            'application/json;charset=utf-8'
        },
        body: JSON.stringify(formData)
      }
      // Fake api for making post requests
      let response = fetch("/action.php", options);
      response.then(res => res.json()).then(data => {
        console.log('data')
        console.log(data?.app)
        window.location.reload();
      })
    }

    function changeSideButton(value) {
      let sides = document.querySelectorAll("label[for^='side-']")
      let sidesLength = sides.length;
      for (let i = 0; i < sidesLength; i++) {
        if (sides[i].getAttribute('for') === value) {
          sides[i].classList.add('btn-success')
          sides[i].classList.remove('btn-secondary')
        } else {
          sides[i].classList.remove('btn-success')
          sides[i].classList.add('btn-secondary')
        }
      }
    }

    function checkSide() {
      if (!document.querySelector("label[for^='side-'][class*='btn-success']")) {
        alert('Veuillez chosir un camp!');
        return false;
      }
      return true;
    }

    function changePlayer(value) {
      let players = document.querySelectorAll("label[for^='player-']")
      let playersLength = players.length;
      for (let i = 0; i < playersLength; i++) {
        if (players[i].getAttribute('for') === value) {
          players[i].classList.add('btn-success')
          players[i].classList.remove('btn-secondary')
        } else {
          players[i].classList.remove('btn-success')
          players[i].classList.add('btn-secondary')
        }
      }
    }

    function checkPlayer() {
      if (!document.querySelector("label[for^='player-'][class*='btn-success']")) {
        alert('Veuillez chosir une classe de joueur!');
        return false;
      }
      return true;
    }

</script>
</body>
</html>
