<?php
namespace CapstoneLogic\Users\Console;

use Illuminate\Console\Command;


class InitCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clu:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize CapstoneLogic Users';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->info('Migrating ...');
        $this->call('migrate');
        $this->call('passport:install');
        $this->info('Done!');
    }
}
