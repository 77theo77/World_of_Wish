<?php

namespace Utils;

interface PlayerInterface {
    public function attack(&$opponent);
    public function attackWith(&$opponent, $arme);
    public function parer($arme);
    public function role();
    public function setDefaultArmes();
}
