<?php

namespace Utils;

class Pretre extends BaseJoueur
{
    public function __construct()
    {
        $this->techniques = ['attack', 'pouvoir_sacrer'];
        $this->armes = [self::CHAPELET_ARME];
    }

    public function attack(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $opponent->life -= $this->attack_value;
        }
    }

    public function pouvoir_sacrer(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $opponent->life -= 10;
            $chance = rand(1, 100);
            if ($chance <= 50) {
                $this->life += 5;
                if ($this->life > 85) {
                    $this->life = 85;
                }
            }
        }
    }
}
