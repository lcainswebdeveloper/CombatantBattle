<?php

namespace Battler;

use Battler\CombatantFactory;

class Battle{
    public $combatantOne;
    public $combatantTwo;
    protected $isOver = false;
    protected $draw = false;
    protected $stoppedContest = false;
    protected $winner;
    protected $loser;
    public $firstAttacker;
    public $secondAttacker;
    public $rounds = 30;
    public $commentary = [];

    public function __construct($combatantOne, $combatantTwo){
        $this->combatantOne = $this->create_combatant($combatantOne);
        $this->combatantTwo = $this->create_combatant($combatantTwo);
        $this->add_opening_commentary();
    }

    public function combatantNameLengthValid($name){
        return (strlen(trim($name)) <= 32);
    }

    protected function create_combatant($combatant){
        if(is_array($combatant)):
            return (new CombatantFactory(key($combatant), trim(current($combatant))))->combatant();
        endif;
        return (new CombatantFactory(trim($combatant)))->combatant();
    }

    public function add_opening_commentary(){
        $this->addCommentary($this->combatantOne->name.' is a: '.$this->combatantOne->type);
        $this->addCommentary($this->combatantTwo->name.' is a: '.$this->combatantTwo->type);
        $this->addCommentary($this->combatantOne->name.' will now face '.$this->combatantTwo->name.' in a battle to the death...');
    }

    public function determineAttackOrder(){
        if(!isset($this->firstAttacker)):
            $fastest = $this->compareCombatantValues('speed');
            $this->firstAttacker = (!is_null($fastest)) ? $fastest : $this->compareCombatantValues('defence', 'lowest');
        endif;

        $this->secondAttacker = ($this->firstAttacker == $this->combatantOne) ? $this->combatantTwo : $this->combatantOne;
        $this->addCommentary($this->firstAttacker->name.' to attack first!');
    }

    public function compareCombatantValues($attributeKey, $searchFor = 'highest'){
        $combatantOneValue = $this->combatantOne->attributes[$attributeKey];
        $combatantTwoValue = $this->combatantTwo->attributes[$attributeKey];

        if($combatantOneValue > $combatantTwoValue):
            return ($searchFor == 'highest') ? $this->combatantOne : $this->combatantTwo;
        elseif($combatantTwoValue > $combatantOneValue):
            return ($searchFor == 'highest') ? $this->combatantTwo : $this->combatantOne;
        else:
            return null;
        endif;
    }

    public function commence($callback = null){
        $round = 1;
        while ($round <= $this->rounds) {
            if($this->isOver()):
                $this->stoppedContest = true;
                break;
            endif;
            $this->fight($round, function() use($callback){
                if(is_callable($callback)):
                    $callback();
                endif;
            });
            $round++;
        }
    }

    public function fight($round = 1, $callback = null){
        if($round == 1):
            $this->determineAttackOrder();
        endif;
        $this->startRoundCommentary($round);
        $this->attack($this->firstAttacker, $this->secondAttacker, function(){
            $this->attack($this->secondAttacker, $this->firstAttacker);
        });
        $this->roundOverActions($round,$callback);
    }

    public function attack($attacker, $defender, $callback = null){
        if($attacker->attributes['canAttack'] === true):
            $defenderStartHealth = $defender->attributes['health'];
            if($attacker->didStrike($defender)):
                $this->handleAttack($attacker, $defender);
            else:
                $this->handleMiss($attacker, $defender);
            endif;
            $this->addCommentary($this->endOfRoundHealthStatement($defenderStartHealth, $defender));
        else:
            $this->handleCantAttack($attacker);
        endif;

        if($defender->attributes['health'] <= 0):
            $this->fightOver($attacker, $defender);
        else:
            if(is_callable($callback)):
                $callback($defender, $attacker);
            endif;
        endif;
    }

    public function handleAttack($attacker, $defender){
        $comment = $attacker->name.' ('.$attacker->type.') landed a strike upon '.$defender->name.' ('.$defender->type.')';
        $damage = $this->calculateDamageAmount($attacker, $defender);

        $defender->attributes['health'] -= $damage;
        $comment .= ' which deducted '.$damage.' health points.';
        $this->addCommentary($comment);
    }

    public function calculateDamageAmount($attacker, $defender){
        /*Did they perform their special skills?*/
        $attacker->checkHitSkill($attacker, $defender);
        $damage = ($attacker->attributes['strength'] - $defender->attributes['defence']);
        if(!empty($attacker->commentaries)):
            foreach($attacker->commentaries as $commentary):
                $this->addCommentary($commentary);
            endforeach;
        endif;
        return $damage;

    }

    public function handleMiss($attacker, $defender){
        $this->addCommentary($attacker->name.' ('.$attacker->type.') missed a strike upon '.$defender->name.' ('.$defender->type.')');
        $defender->checkMissSkill($attacker, $defender);
        if(!empty($defender->commentaries)):
            foreach($defender->commentaries as $commentary):
                $this->addCommentary($commentary);
            endforeach;
        endif;
    }

    public function handleCantAttack($attacker){
        $this->addCommentary($attacker->name.' unable to strike but reinstated for the next try', 'error');
        $attacker->attributes['canAttack'] = true;
    }

    protected function endOfRoundHealthStatement($defenderStartHealth, $defender){
        if($defenderStartHealth == $defender->attributes['health']):
            return $defender->name.' health remains at: '.$defender->attributes['health'];
        endif;
        if($defender->attributes['health'] <= 0):
            return $defender->name.' has sustained a lethal strike and has been defeated!';
        endif;
        return $defender->name.' health is now: '.$defender->attributes['health'];
    }
    
    protected function fightOver($winner, $loser){
        $this->isOver = true;
        $this->winner = $winner;
        $this->loser  = $loser;
    }

    protected function roundOverActions($round, $callback){
        if($this->isOver === true):
            $this->addCommentary($this->winner->name.' has won the contest!');
        elseif($this->draw($round)):
            $this->addCommentary('We Have A Draw');
        else:
            $this->addCommentary('Round '.$round.' complete');
        endif;
        if(is_callable($callback)):
            $callback();
        endif;
    }

    protected function startRoundCommentary($round){
        $this->firstAttacker->commentaries = [];
        $this->secondAttacker->commentaries = [];
        if($round > $this->rounds):
            $this->addCommentary('We Have A Draw');
        endif;
        $this->addCommentary(' ');
        $this->addCommentary('*************************');
        $this->addCommentary('Seconds Out - Round '.$round);
        $this->addCommentary('*************************');
        $this->addCommentary($this->firstAttacker->name.' is starting this round with a health of '.$this->firstAttacker->attributes['health']);
        $this->addCommentary($this->secondAttacker->name.' is starting this round with a health of '.$this->secondAttacker->attributes['health']);
    }

    protected function addCommentary($value, $key = 'success'){
        $this->commentary[] = $value;
    }

    public function isOver(){
        return $this->isOver;
    }

    public function stoppedContest(){
        return $this->stoppedContest;
    }

    public function draw($round = null){
        if(!is_null($round)):
            if($round >= $this->rounds) $this->draw = true;
        endif;
        return $this->draw;
    }

    public function loser(){
        return $this->loser;
    }

    public function winner(){
        return $this->winner;
    }

    public function commentaryOutput($callback){
        foreach($this->commentary as $key => $comment):
            $callback($comment);
        endforeach;
        $this->commentary = [];
    }
}
