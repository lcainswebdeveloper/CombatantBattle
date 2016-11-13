<?php

namespace Battler\Combatants;

class Grappler extends Combatant{

    public function __construct($name){
        parent::__construct($name);
        $this->type = 'Grappler';
        $this->skills['defence'] = [
            'counterAttack'
        ];
    }

    public function health(){
        return $this->setHealth(60,100);
    }

    public function strength(){
        return $this->setStrength(75,80);
    }

    public function defence(){
        return $this->setDefence(35,40);
    }

    public function speed(){
        return $this->setSpeed(60,80);
    }

    public function luck(){
        return $this->setLuck(0.3,0.4);
    }

    
}
