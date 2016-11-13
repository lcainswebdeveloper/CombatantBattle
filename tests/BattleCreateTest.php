<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Battler\CombatantFactory;
use Battler\Battle as BeginBattle;

class BattleCreateTest extends TestCase
{
    public function testWeCanValidateTheCombatantNameLength(){
        $battle = new BeginBattle('Ian Botham', 'Random name that is definitely longer than 32 characters long');
        $this->assertTrue($battle->combatantNameLengthValid($battle->combatantOne->name));
        $this->assertFalse($battle->combatantNameLengthValid($battle->combatantTwo->name));
    }

    public function testWeCanAssignRandomBattleTypes()
    {
        $combatant = new CombatantFactory('Ian Botham');
        $this->assertContains($combatant->combatant()->type, [
            'Grappler', 'Brute', 'Swordsman'
        ]);
    }

    public function testWeCanManuallyAssignACombatantTypeIfNeeded(){
        $battle = new BeginBattle([
            'Ian Botham' => \Battler\Combatants\Brute::class
        ],[
            'Donald Duck' => \Battler\Combatants\Swordsman::class
        ]);

        $this->assertEquals($battle->combatantOne->type, 'Brute');
        $this->assertEquals($battle->combatantTwo->type, 'Swordsman');

        /*Above constructor defers to this*/
        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $this->assertEquals($combatant->combatant()->type, 'Brute');

        $combatant = new CombatantFactory('Donald Duck', \Battler\Combatants\Swordsman::class);
        $this->assertEquals($combatant->combatant()->type, 'Swordsman');

        $combatant = new CombatantFactory('Keith Chegwin', \Battler\Combatants\Grappler::class);
        $this->assertEquals($combatant->combatant()->type, 'Grappler');
    }

    public function testWeCanConfirmWhichCombatantWillGoFirst(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['speed'] = 50;
        $battle->combatantTwo->attributes['speed'] = 60;
        $battle->commence();
        $this->assertEquals($battle->firstAttacker, $battle->combatantTwo);
        $this->assertEquals($battle->secondAttacker, $battle->combatantOne);
    }


    public function testThatLowestDefenceWillBeSelectedIfSpeedsAreEqual(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['speed']   = 50;
        $battle->combatantOne->attributes['defence'] = 40;
        $battle->combatantTwo->attributes['speed']   = 50;
        $battle->combatantTwo->attributes['defence'] = 50;

        $battle->commence();

        $this->assertEquals($battle->firstAttacker, $battle->combatantOne);
        $this->assertEquals($battle->secondAttacker, $battle->combatantTwo);
    }

    public function testWeCanSetAFirstAttackerManuallyIfRequired(){
        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['speed'] = 80; //should go first if not overwritten
        $battle->combatantTwo->attributes['speed'] = 60;
        $battle->commence();
        $this->assertEquals($battle->firstAttacker, $battle->combatantOne);
        $this->assertEquals($battle->secondAttacker, $battle->combatantTwo);

        $battle = new BeginBattle('Ian Botham', 'Donald Duck');
        $battle->combatantOne->attributes['speed'] = 80;
        $battle->combatantTwo->attributes['speed'] = 60;
        $battle->firstAttacker = $battle->combatantTwo;
        $battle->commence();
        $this->assertEquals($battle->firstAttacker, $battle->combatantTwo);
        $this->assertEquals($battle->secondAttacker, $battle->combatantOne);
    }
}
