<?php

namespace Battler\Combatants;
use Battler\Utilities\Mathematics;

interface CombatantInterface{
    
    public function health();

    public function strength();

    public function defence();

    public function speed();

    public function luck();

}
