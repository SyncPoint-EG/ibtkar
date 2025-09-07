<?php

namespace App\Console\Commands;

use App\Models\Code;
use Illuminate\Console\Command;

class FixCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $codes = Code::all();
        foreach ($codes as $code) {
            if($code->teacher_id == 2){
                if($code->for == 'lesson'){
                    $code->price = 80 ;
                }
                if($code->for == 'chapter'){
                    $code->price = 300 ;
                }
            }
            if($code->teacher_id == 6){
                if($code->for == 'lesson'){
                    $code->price = 70 ;
                }
                if($code->for == 'chapter'){
                    $code->price = 220 ;
                }
            }
            $code->save();
        }
    }
}
