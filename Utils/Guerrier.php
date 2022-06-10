<?php

namespace Utils;

class Guerrier extends BaseJoueur
{
    public $life = 150;
    public $bouclier = true;
    protected $max_armes = 2;

    public function __construct($armes = null)
    {
        if ($armes === null) {
            // On cree un nouveau tableau, car la premiere arme selectionner ne doit plus etre presente
            $new_armes = $this->default_armes[self::GUERRIER_PLAYER];
            $armes = [];
            $index_arme1 = rand(0, 2);
            $armes[] = $new_armes[$index_arme1];

            // Reinitialize array
            unset($new_armes[$index_arme1]);
            $new_armes = array_values($new_armes);

            $armes[] = $new_armes[rand(0, 1)];
        }
        $this->setArmes($armes);
    }

    public function setArmes($armes)
    {
        if (is_array($armes)) {
            // Si il n'a pas la hache, il a un bouclier
            $this->bouclier = ! in_array(self::HACHE_ARME, $armes);
            $this->defenses = ['parer'];
            $this->armes = $armes;
            return true;
        }

        return false;
    }

    public function parer($arme)
    {
        $chance = rand(1, 100);
        if (in_array(self::LANCE_ARME, $this->armes) ? $chance > 66 : $chance > 50) {
            $this->life -= self::ARME_VALUES[$arme];
        }
    }

    public function attack(&$opponent)
    {
    }
}
