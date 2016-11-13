<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Battler\CombatantFactory;
use Battler\Battle as BeginBattle;
use Battler\Utilities\Mathematics;

class MathematicsTest extends TestCase
{
    public function testWeCanGetARandomDecimalWithinAGivenRange(){
        $random = Mathematics::getRandomDecimal();
        $this->assertGreaterThanOrEqual(0, $random);
        $this->assertLessThanOrEqual(1.0, $random);

        $random = Mathematics::getRandomDecimal(10,85);
        $this->assertGreaterThanOrEqual(10, $random);
        $this->assertLessThanOrEqual(85, $random);
    }

    public function testWeCanCheckOnTheProbabilityOfAnOutcome(){
        for ($i=0; $i < 200; $i++) {
            $random = mt_rand(1,100);
            $check = Mathematics::chanceCheckerTest($random);
            if($check['randomValue'] <= $random):
                $this->assertTrue($check['outcome']);
            else:
                $this->assertFalse($check['outcome']);
            endif;
        }
    }
}
