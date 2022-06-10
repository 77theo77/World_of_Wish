<?php

namespace Utils;

class BaseJoueur implements PlayerInterface
{
    const GUERRIER_PLAYER = 'GUERRIER';
    const MAGE_PLAYER = 'SORCIER';
    const ARCHER_PLAYER = 'ARCHER';
    const PRETRE_PLAYER = 'PRETRE';
    const DRUIDE_PLAYER = 'DRUIDE';

    const HACHE_ARME = 'HACHE';
    const EPEE_ARME = 'EPEE';
    const LANCE_ARME = 'LANCE';
    const BAGUETTE_ARME = 'BAGUETTE';
    const ARC_ARME = 'ARC';
    const CHAPELET_ARME = 'CHAPELET';
    const GRIFFE_ARME = 'GRIFFE';

    public $life = 85;
    public $bouclier = false;
    public $armes = [];
    protected $max_armes = 1;
    public $attack_value = 1;
    public $faction = null;
    public $username = null;

    public $techniques = ['attack'];
    public $defenses = [];

    public $default_armes = [
        self::GUERRIER_PLAYER => [
            self::HACHE_ARME,
            self::EPEE_ARME,
            self::LANCE_ARME,
        ],
        self::MAGE_PLAYER => [
            self::BAGUETTE_ARME,
        ],
        self::ARCHER_PLAYER => [
            self::ARC_ARME,
        ],
        self::PRETRE_PLAYER => [
            self::CHAPELET_ARME,
        ],
        self::DRUIDE_PLAYER => [
            self::GRIFFE_ARME,
        ]
    ];

    const ARME_VALUES = [
        self::HACHE_ARME => 20,
        self::EPEE_ARME => 15,
        self::LANCE_ARME => 10,
        self::ARC_ARME => 15,
        self::BAGUETTE_ARME => 5,
        self::CHAPELET_ARME => 1,
        self::GRIFFE_ARME => 1
    ];

    public function setInitialData($faction)
    {
        $this->faction = $faction;
        $this->username = uniqid('User');

        return $this;
    }

    public function attack(&$user)
    {
        if ($user instanceof BaseJoueur && $user->life > 0) {
            $user->life -= 1;
            return true;
        }

        return false;
    }

    public function attackWith(&$user, $arme)
    {
        $value = self::ARME_VALUES[$arme];
        if ($user instanceof BaseJoueur && $user->life > 0) {
            $user->life -= $value;
            return true;
        }

        return false;
    }

    public function parer($arme)
    {
        return self::ARME_VALUES[$arme] < 1 && $this->life > 0;
    }

    public function getLife()
    {
        return $this->life > 0 ? $this->life : 0;
    }

    public function role()
    {
        return str_replace('Utils\\', '', get_called_class());
    }

    public function setDefaultArmes()
    {
        return $this->armes = $this->default_armes[$this->role()];
    }
}
