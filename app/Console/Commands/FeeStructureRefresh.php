<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FeeStructureRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fee:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the fees table and checks for valid fees available';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $fees   =   \App\Fee::all()->where("status","=", 2)->where("effective_from","<=",date("Y-m-d"));
        foreach ($fees as $fee)
        {
            $fee->update([
                "status"    =>  1
            ]);
        }
    }
}
