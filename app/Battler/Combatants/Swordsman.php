<?php

namespace Battler\Combatants;
use Battler\Utilities\Mathematics;

class Swordsman extends Combatant{

    public function __construct($name){
        parent::__construct($name);
        $this->type = 'Swordsman';
        $this->skills['attack'] = [
            'luckyStrike'
        ];
    }

    public function health(){
        return $this->setHealth(40,60);
    }

    public function strength(){
        return $this->setStrength(60,70);
    }

    public function defence(){
        return $this->setDefence(20,30);
    }

    public function speed(){
        return $this->setSpeed(90, 100);
    }

    public function luck(){
        return $this->setLuck(0.3,0.5);
    }

}
