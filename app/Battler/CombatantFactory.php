<?php

namespace Battler;

class CombatantFactory{
    protected $combatant;
    public function __construct($combatant, $type = null){
        $combatantType = (is_null($type)) ? static::RandomType() : $type;
        $this->combatant = new $combatantType($combatant);
    }

    public static function RandomType(){
        $options = [
            \Battler\Combatants\Swordsman::class,
            \Battler\Combatants\Brute::class,
            \Battler\Combatants\Grappler::class
        ];
        return $options[array_rand($options, 1)];
    }

    public function combatant(){
        return $this->combatant;
    }


}
