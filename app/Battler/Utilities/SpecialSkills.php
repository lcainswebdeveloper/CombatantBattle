<?php

namespace Battler\Utilities;

class SpecialSkills{
    public $commentaries;
    /*SKILLS*/
    public function stunningBlowAction($attacker, $defender){
        $defender->attributes['canAttack'] = false;
        $this->commentaries[] = $attacker->name. ' scored a STUNNING BLOW, rendering '.$defender->name.' unable to take their next attack!';
    }

    public function stunningBlow($attacker, $defender){
        if(Mathematics::chanceCheckerTest(2)['outcome'] === true):
            $this->stunningBlowAction($attacker, $defender);
        endif;
        return $defender;
    }

    public function counterAttackAction($attacker, $defender){
        $currentHealth = $attacker->attributes['health'];
        $attacker->attributes['health'] -= 10;
        $this->commentaries[] = $defender->name. ' managed a COUNTER ATTACK, reducing the current health of '.$currentHealth.' points for '.$attacker->name.' by 10 points leaving them with '.$attacker->attributes['health'].' points';
    }

    public function counterAttack($attacker, $defender){
        $this->counterAttackAction($attacker, $defender);
        return $attacker;
    }

    public function luckyStrikeAction($attacker){
        $currentStrength = $this->attributes['strength'];
        $attacker->attributes['strength'] *= 2;
        $this->commentaries[] = $this->name. ' scored a LUCKY STRIKE and now has doubled their stregnth from '.$currentStrength.' to: '.$attacker->attributes['strength'];
    }

    public function luckyStrike($attacker, $defender){
        if(Mathematics::chanceCheckerTest(5)['outcome'] === true):
            $this->luckyStrikeAction($attacker);
        endif;
        return $defender;
    }
}
