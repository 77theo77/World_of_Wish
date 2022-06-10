<?php

namespace Utils;

class Sorcier extends BaseJoueur
{
    public $life = 100;

    public function __construct()
    {
        $this->techniques = ['attack', 'boule_de_feu'];
        $this->armes = [self::BAGUETTE_ARME];
    }

    public function attack(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $opponent->life -= self::ARME_VALUES[self::BAGUETTE_ARME];
        }
    }

    public function boule_de_feu(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $chance = rand(1, 100);
            if ($chance > 80) {
                $opponent->life -= 30;
            }
        }
    }
}
