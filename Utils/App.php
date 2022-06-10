<?php

namespace Utils;

require_once 'PlayerInterface.php';
require_once 'BaseJoueur.php';
require_once 'Archer.php';
require_once 'Guerrier.php';
require_once 'Pretre.php';
require_once 'Sorcier.php';
require_once 'Druide.php';

class App  implements \JsonSerializable
{
    const COALITION_SIDE = "COALITION";
    const COLONIE_SIDE = "COLONIE";
    const DEFAULT_STATUS = "NOT START";
    const LOSE_STATUS = "LOOSED";
    const WIN_STATUS = "WIN";

    private $side = null;
    private $started = false;
    private $life = 0;
    private $opponent_life = 0;
    private $player = null;
    private $username = null;
    private $opponent = null;

    public function __construct($username = null, $side = null)
    {
        if ($username !== null) {
            $this->username = $username;
            $this->started = true;
        }
        if (in_array($side, $this->getSides())) {
            $this->started = true;
        }
    }

    public function setUsername($username = null)
    {
        $this->username = $username;
        if ($username !== null) {
            $this->started = true;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function updatePlayer($player)
    {
        if ($player instanceof BaseJoueur) {
            $this->player = $player;
            $this->life = $player->life;
            return true;
        }
        return false;
    }

    public function updateOpponent($opponent)
    {
        if ($opponent instanceof BaseJoueur) {
            $this->opponent = $opponent;
            $this->opponent_life = $opponent->life;
            return true;
        }
        return false;
    }

    public function setPlayer($player = null)
    {
        if ($player === null) {
            $this->player = null;
            $this->opponent = null;
            $this->life = 0;
            $this->opponent_life = 0;
            $this->started = false;
            return true;
        }

        if (! in_array($player, $this->getPlayers())) {
            return false;
        }

        $player = ([
            BaseJoueur::GUERRIER_PLAYER => new Guerrier(),
            BaseJoueur::ARCHER_PLAYER => new Archer(),
            BaseJoueur::PRETRE_PLAYER => new Pretre(),
            BaseJoueur::DRUIDE_PLAYER => new Druide(),
            BaseJoueur::MAGE_PLAYER => new Sorcier(),
        ][$player]);

        if ($player instanceof BaseJoueur) {
            $this->life = $player->life;
            $player->username = $this->username;
            $player->faction = $this->side;
//            $this->player = $player;
            $this->player = serialize($player);
            $this->initOpponent();
            $this->started = true;
            return true;
        }

        return false;
    }

    public function getPlayer()
    {
        if ($this->player instanceof BaseJoueur) {
            return $this->player;
        }

        return $this->player ? unserialize($this->player) : null;
    }


    public function initOpponent($opponent = true)
    {
        if (! $opponent) {
            $this->opponent = null;
            return true;
        }

        $opponent = ([
            new Guerrier(),
            new Archer(),
            new Pretre(),
            new Druide(),
            new Sorcier(),
        ][rand(0, 4)]);

        if ($opponent instanceof BaseJoueur) {
            $opponent->setInitialData($this->getOtherSide());
            $this->opponent_life = $opponent->life;
//            $this->opponent = $opponent;
            $this->opponent = serialize($opponent);
            return true;
        }

        return false;
    }

    public function setOpponent($opponent, $life = null)
    {
        if ($opponent) {
            if ($life >= 0) {
                $opponent->life = $life;
            }
            $this->opponent_life = $opponent->life;
//            $this->opponent = $opponent;
            $this->opponent = serialize($opponent);
            return true;
        }
        return false;
    }

    public function allArmes()
    {
        return BaseJoueur::ARME_VALUES;
    }

    public function getOpponent()
    {
        if ($this->opponent instanceof BaseJoueur) {
            return $this->opponent;
        }
        return $this->opponent ? unserialize($this->opponent) : null;
    }

    public function getOpponentLife()
    {
        return $this->opponent_life > 0 ? $this->opponent_life : 0;
    }

    public function getLife()
    {
        return $this->life > 0 ? $this->life : 0;
    }

    public function hasStarted()
    {
        return $this->started;
    }

    public function hasSide()
    {
        return $this->side;
    }

    public function hasPlayer()
    {
        return $this->player;
    }

    public function hasBouclier()
    {
//        $player = $this->player;
        $player = unserialize($this->player);
        if ($player instanceof Guerrier) {
            return $player->bouclier;
        }
        return false;
    }

    public function logout()
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
            unset($_SESSION);
            $this->started = false;
            $this->username = null;
            $this->player = null;
            $this->life = 0;
            $this->side = null;
            return true;
        }
        return false;
    }

    public function getGameStatus()
    {
        return self::DEFAULT_STATUS;
    }

    public function getSides()
    {
        return [
            self::COALITION_SIDE,
            self::COLONIE_SIDE,
        ];
    }

    public function getPlayers()
    {
        return [
            BaseJoueur::GUERRIER_PLAYER,
            BaseJoueur::MAGE_PLAYER,
            BaseJoueur::ARCHER_PLAYER,
            BaseJoueur::PRETRE_PLAYER,
            BaseJoueur::DRUIDE_PLAYER,
        ];
    }

    public function getOtherSide()
    {
        return $this->side !== self::COLONIE_SIDE ? self::COLONIE_SIDE : self::COALITION_SIDE;
    }

    public function setSide($side)
    {
        if ($this->started && ! $this->side && in_array($side, $this->getSides())) {
            $this->side = $side;
            return true;
        }

        return false;
    }

    public function chooseColonie()
    {
        if ($this->started && ! $this->side) {
            $this->side = self::COLONIE_SIDE;
            return true;
        }

        return false;
    }

    public function chooseCoalition()
    {
        if ($this->started && ! $this->side) {
            $this->side = self::COALITION_SIDE;
            return true;
        }

        return false;
    }

    public function jsonSerialize()
    {
        return [
            'side' => $this->side,
            'started' => $this->started,
            'life' => $this->life,
            'opponent_life' => $this->opponent_life,
            'player' => $this->getPlayer(),
            'username' => $this->username,
            'opponent' => $this->getOpponent(),
        ];
    }
}
