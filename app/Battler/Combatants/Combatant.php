<?php

namespace Battler\Combatants;
use Battler\Utilities\Mathematics;

abstract class Combatant implements CombatantInterface{
    public $attributes = [];
    public $skills = [];
    public $commentaries = [];
    public $name;
    public $type;
    public function __construct($name){
        $this->name = $name;
        $this->attributes = $this->setStartingAttributes();
        $this->skills['attack'] = [];
        $this->skills['defence'] = [];
    }

    public function setStartingAttributes($config = []){
        return [
            'health'    => (isset($config['health']))   ? $config['health']   : $this->health(),
            'strength'  => (isset($config['strength'])) ? $config['strength'] : $this->strength(),
            'defence'   => (isset($config['defence']))  ? $config['defence']  : $this->defence(),
            'speed'     => (isset($config['speed']))    ? $config['speed']    : $this->speed(),
            'luck'      => $luck = (isset($config['luck'])) ? $config['luck'] : $this->luck(),
            'luckPercentage' => $luck * 100,
            'canAttack' => (isset($config['canAttack'])) ? $config['canAttack'] : true
        ];
    }

    protected function generateRandom($min, $max){
        return mt_rand($min, $max);
    }

    protected function setHealth($min, $max){
        return $this->generateRandom($min, $max);
    }

    protected function setStrength($min, $max){
        return $this->generateRandom($min, $max);
    }

    protected function setDefence($min, $max){
        return $this->generateRandom($min, $max);
    }

    protected function setSpeed($min, $max){
        return $this->generateRandom($min, $max);
    }

    protected function setLuck($min, $max){
        return Mathematics::getRandomDecimal($min, $max);
    }

    public function didStrike($defender){
        return !Mathematics::chanceCheckerTest($defender->attributes['luckPercentage'])['outcome'];
    }

    public function checkHitSkill($defender){
        $this->commentaries = [];
        foreach($this->skills['attack'] as $skill):
            $this->$skill($defender);
        endforeach;
        return $defender;
    }

    public function checkMissSkill($attacker){
        $this->commentaries = [];
        foreach($this->skills['defence'] as $skill):
            $this->$skill($attacker);
        endforeach;
        return $attacker;
    }

    /*SKILLS*/
    public function stunningBlowAction($defender){
        $defender->attributes['canAttack'] = false;
        $this->commentaries[] = $this->name. ' scored a STUNNING BLOW, rendering '.$defender->name.' unable to take their next attack!';
    }

    protected function stunningBlow($defender){
        if(Mathematics::chanceCheckerTest(2)['outcome'] === true):
            $this->stunningBlowAction($defender);
        endif;
        return $defender;
    }

    public function counterAttackAction($attacker){
        $currentHealth = $attacker->attributes['health'];
        $attacker->attributes['health'] -= 10;
        $this->commentaries[] = $this->name. ' managed a COUNTER ATTACK, reducing the current health of '.$currentHealth.' points for '.$attacker->name.' by 10 points leaving them with '.$attacker->attributes['health'].' points';
    }

    protected function counterAttack($attacker){
        $this->counterAttackAction($attacker);
        return $attacker;
    }

    public function luckyStrikeAction(){
        $currentStrength = $this->attributes['strength'];
        $this->attributes['strength'] *= 2;
        $this->commentaries[] = $this->name. ' scored a LUCKY STRIKE and now has doubled their stregnth from '.$currentStrength.' to: '.$this->attributes['strength'];
    }

    protected function luckyStrike($defender){
        if(Mathematics::chanceCheckerTest(5)['outcome'] === true):
            $this->luckyStrikeAction();
        endif;
        return $defender;
    }
}
