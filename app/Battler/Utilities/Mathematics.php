<?php

namespace Battler\Utilities;

class Mathematics{
    public static function getRandomDecimal($min = 0, $max = 1, $decimalPlaces = 2){
        return number_format(mt_rand($min * 100, $max*100) / 100, $decimalPlaces);
    }

    public static function chanceCheckerTest($percentageChance){
        $randomValue = rand(1,100);
        return [
            'outcome'       => (bool)($randomValue <= $percentageChance),
            'randomValue'  => $randomValue
        ];
    }
}
