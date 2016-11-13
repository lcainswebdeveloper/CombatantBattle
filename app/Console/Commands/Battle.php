<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Battler\CombatantFactory;
use Battler\Battle as BeginBattle;

class Battle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'battle:commence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run this command to unleash the carnage....';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $combatantOne;
    protected $combatantTwo;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $combatantOne = $this->ask('Please give the name of combatant one ie Donald Trump - max 32 characters');
        $combatantTwo = $this->ask('Please give the name of combatant two ie Hillary Clinton - max 32 characters');
        $battle = new BeginBattle($combatantOne, $combatantTwo);
        if($battle->combatantNameLengthValid($combatantOne)):
            if($battle->combatantNameLengthValid($combatantTwo)):
                $this->output_commentary($battle);
                $this->countdown();
                $this->performBattle($battle);
            else:
                $this->nameInvalid($combatantTwo);
            endif;
        else:
            $this->nameInvalid($combatantOne);
        endif;

    }

    protected function nameInvalid($name){
        $this->error('The name '.$name.' is longer than 32 characters and invalid. Please start again.');
    }

    public function performBattle($battle){
        $battle->commence(function() use($battle){
            $this->output_commentary($battle);
            sleep(2);
        });
    }

    protected function output_commentary($battle){
        $battle->commentaryOutput(function($comment){
            $this->info($comment);
        });
    }

    protected function countdown(){
        sleep(2);
        $this->info(3);
        sleep(1);
        $this->info(2);
        sleep(1);
        $this->info(1);
        sleep(1);
        $this->info('HERE WE GO.......');
    }
}
