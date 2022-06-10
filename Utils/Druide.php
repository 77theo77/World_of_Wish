<?php

namespace Utils;

class Druide extends BaseJoueur
{
    public function __construct()
    {
        $this->techniques = ['attack', 'pouvoir_naturel'];
        $this->armes = [self::GRIFFE_ARME];
    }

    public function attack(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $opponent->life -= $this->attack_value;
        }
    }

    public function pouvoir_naturel(&$opponent)
    {
        if ($opponent instanceof BaseJoueur && $opponent->life > 0) {
            $degats = rand(5, 15);
            $opponent->life -= $degats;
            $chance = rand(1, 100);
            if ($chance <= 25) {
                $this->life += $degats;
                if ($this->life > 85) {
                    $this->life = 85;
                }
            }
        }
    }
}
