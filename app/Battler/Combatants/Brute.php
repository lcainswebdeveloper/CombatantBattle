<?php

namespace Battler\Combatants;
use Battler\Utilities\Mathematics;

class Brute extends Combatant{
    public function __construct($name){
        parent::__construct($name);
        $this->type = 'Brute';
        $this->skills['attack'] = [
            'stunningBlow'
        ];
    }

    public function health(){
        return $this->setHealth(90,100);
    }

    public function strength(){
        return $this->setStrength(65,75);
    }

    public function defence(){
        return $this->setDefence(40,50);
    }

    public function speed(){
        return $this->setSpeed(40,65);
    }

    public function luck(){
        return $this->setLuck(0.3,0.35);
    }

    
}
