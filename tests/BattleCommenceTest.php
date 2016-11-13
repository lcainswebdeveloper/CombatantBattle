<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Battler\CombatantFactory;
use Battler\Battle as BeginBattle;

class BattleCommenceTest extends TestCase
{
    public function testTheCorrectAmountOfDamageWillBeCalculatedEachRound(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['strength'] = 90;
        $battle->combatantOne->attributes['defence']  = 40;
        $battle->combatantTwo->attributes['strength'] = 70;
        $battle->combatantTwo->attributes['defence']  = 50;

        $battle->fight();

        $this->assertEquals($battle->calculateDamageAmount($battle->combatantOne, $battle->combatantTwo), 40);
        $this->assertEquals($battle->calculateDamageAmount($battle->combatantTwo, $battle->combatantOne), 30);
    }

    public function testACombatantWillBeAbleToStrikeAfterTheyMissARound(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['canAttack'] = false;
        $battle->commence();
        $this->assertContains($battle->combatantOne->name.' unable to strike but reinstated for the next try', $battle->commentary);
        $this->assertTrue($battle->combatantOne->attributes['canAttack']);
    }

    public function testAttackMissIsBeingCalculatedCorrectly(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['luckPercentage'] = 100; //opponent will always miss
        $battle->combatantTwo->attributes['luckPercentage'] = 0; //will never avoid a strike
        $this->assertTrue($battle->combatantOne->didStrike($battle->combatantTwo));
        $this->assertFalse($battle->combatantTwo->didStrike($battle->combatantOne));
    }

    public function testluckyStrikeIsWorkingCorrectly(){
        $combatant = new CombatantFactory('Donald Duck', \Battler\Combatants\Swordsman::class);
        $combatant->combatant()->attributes['strength'] = 50;
        $combatant->combatant()->luckyStrikeAction();
        $this->assertEquals($combatant->combatant()->attributes['strength'], 100);
    }

    public function teststunningBlowIsWorkingCorrectly(){
        $attacker = new CombatantFactory('Donald Duck', \Battler\Combatants\Brute::class);
        $defender = new CombatantFactory('Ian Botham', \Battler\Combatants\Swordsman::class);
        $this->assertTrue($defender->combatant()->attributes['canAttack']);
        $attacker->combatant()->stunningBlowAction($defender->combatant());
        $this->assertFalse($defender->combatant()->attributes['canAttack']);
        $this->assertContains($attacker->combatant()->name.' scored a STUNNING BLOW, rendering '.$defender->combatant()->name.' unable to take their next attack!', $attacker->combatant()->commentaries);
    }

    public function testcounterAttackIsWorkingCorrectly(){
        $attacker = new CombatantFactory('Donald Duck', \Battler\Combatants\Brute::class);
        $attacker->combatant()->attributes['health'] = 50;
        $defender = new CombatantFactory('Ian Botham', \Battler\Combatants\Grappler::class);
        $defender->combatant()->counterAttackAction($attacker->combatant());
        $this->assertEquals($attacker->combatant()->attributes['health'], 40);
        $this->assertContains($defender->combatant()->name.' managed a COUNTER ATTACK, reducing the current health of 50 points for '.$attacker->combatant()->name.' by 10 points leaving them with 40 points', $defender->combatant()->commentaries);
    }

    /*
    In production, in the swordsman class all we would need to do in the constructor is:
    $this->skills['attack'] = [
        'luckyStrike',
        'stunningBlow'
    ];
    */
    public function testACombatantCouldUseMultipleSkills(){
        $attacker = new CombatantFactory('Donald Duck', \Battler\Combatants\Swordsman::class);
        $defender = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $attacker->combatant()->attributes['strength'] = 50;
        $attacker->combatant()->luckyStrikeAction();
        $attacker->combatant()->stunningBlowAction($defender->combatant());
        $this->assertEquals($attacker->combatant()->attributes['strength'], 100);
        $this->assertFalse($defender->combatant()->attributes['canAttack']);
    }

    public function testWeCanAllocateADrawIfNeeded(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['health'] = 10000;
        $battle->combatantTwo->attributes['health'] = 10000;
        $battle->rounds = 1;
        $battle->commence();
        $this->assertTrue($battle->draw());
    }

    public function testWeCanStopWhenTheFightIsWon(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['health'] = 10000;
        $battle->combatantTwo->attributes['health'] = -10;
        $battle->commence();
        $this->assertTrue($battle->stoppedContest());
        $this->assertEquals($battle->winner(), $battle->combatantOne);
    }

    public function testWeCanSetAFirstAttackerManuallyIfRequired(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->firstAttacker = $battle->combatantTwo;
        $battle->commence();
        $this->assertEquals($battle->firstAttacker, $battle->combatantTwo);
        $this->assertEquals($battle->secondAttacker, $battle->combatantOne);
    }
}
