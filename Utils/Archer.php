<?php

namespace Utils;

class Archer extends BaseJoueur
{
    public $life = 120;

    public function __construct()
    {
        $this->attack_value = self::ARME_VALUES[self::ARC_ARME];
        $this->techniques = ['attack', 'tir'];
        $this->armes = [self::ARC_ARME];;
    }

    public function attack(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $opponent->life -= $this->attack_value;
        }
    }

    public function tir(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $opponent->life -= ($this->attack_value * 1.5);
            $this->attack_value -= 2;
        }
    }
}
