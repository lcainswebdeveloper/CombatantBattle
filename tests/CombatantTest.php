<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Battler\CombatantFactory;
use Battler\Utilities\SpecialSkills;
use Battler\Battle as BeginBattle;


class CombatantTest extends TestCase
{
    public function testWeCanAllocateTheName(){
        $combatant = new CombatantFactory('Ian Botham');
        $this->assertEquals($combatant->combatant()->name, 'Ian Botham');
    }

    public function testWeCanAllocateHealthAccurately(){
        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Swordsman::class);
        $this->assertGreaterThanOrEqual(40, $combatant->combatant()->attributes['health']);
        $this->assertLessThanOrEqual(60, $combatant->combatant()->attributes['health']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $this->assertGreaterThanOrEqual(90, $combatant->combatant()->attributes['health']);
        $this->assertLessThanOrEqual(100, $combatant->combatant()->attributes['health']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Grappler::class);
        $this->assertGreaterThanOrEqual(60, $combatant->combatant()->attributes['health']);
        $this->assertLessThanOrEqual(100, $combatant->combatant()->attributes['health']);
    }

    public function testWeCanAllocateStrengthAccurately(){
        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Swordsman::class);
        $this->assertGreaterThanOrEqual(60, $combatant->combatant()->attributes['strength']);
        $this->assertLessThanOrEqual(70, $combatant->combatant()->attributes['strength']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $this->assertGreaterThanOrEqual(65, $combatant->combatant()->attributes['strength']);
        $this->assertLessThanOrEqual(75, $combatant->combatant()->attributes['strength']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Grappler::class);
        $this->assertGreaterThanOrEqual(75, $combatant->combatant()->attributes['strength']);
        $this->assertLessThanOrEqual(80, $combatant->combatant()->attributes['strength']);
    }

    public function testWeCanAllocateDefenceAccurately(){
        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Swordsman::class);
        $this->assertGreaterThanOrEqual(20, $combatant->combatant()->attributes['defence']);
        $this->assertLessThanOrEqual(30, $combatant->combatant()->attributes['defence']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $this->assertGreaterThanOrEqual(40, $combatant->combatant()->attributes['defence']);
        $this->assertLessThanOrEqual(50, $combatant->combatant()->attributes['defence']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Grappler::class);
        $this->assertGreaterThanOrEqual(35, $combatant->combatant()->attributes['defence']);
        $this->assertLessThanOrEqual(40, $combatant->combatant()->attributes['defence']);
    }

    public function testWeCanAllocateSpeedAccurately(){
        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Swordsman::class);
        $this->assertGreaterThanOrEqual(90, $combatant->combatant()->attributes['speed']);
        $this->assertLessThanOrEqual(100, $combatant->combatant()->attributes['speed']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $this->assertGreaterThanOrEqual(40, $combatant->combatant()->attributes['speed']);
        $this->assertLessThanOrEqual(65, $combatant->combatant()->attributes['speed']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Grappler::class);
        $this->assertGreaterThanOrEqual(60, $combatant->combatant()->attributes['speed']);
        $this->assertLessThanOrEqual(80, $combatant->combatant()->attributes['speed']);
    }

    public function testWeCanAllocateLuckAccurately(){
        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Swordsman::class);
        $this->assertGreaterThanOrEqual(0.3, $combatant->combatant()->attributes['luck']);
        $this->assertLessThanOrEqual(0.5, $combatant->combatant()->attributes['luck']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Brute::class);
        $this->assertGreaterThanOrEqual(0.3, $combatant->combatant()->attributes['luck']);
        $this->assertLessThanOrEqual(0.35, $combatant->combatant()->attributes['luck']);

        $combatant = new CombatantFactory('Ian Botham', \Battler\Combatants\Grappler::class);
        $this->assertGreaterThanOrEqual(0.3, $combatant->combatant()->attributes['luck']);
        $this->assertLessThanOrEqual(0.4, $combatant->combatant()->attributes['luck']);
    }

}
