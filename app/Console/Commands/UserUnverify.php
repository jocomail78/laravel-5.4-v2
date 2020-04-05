<?php

namespace App\Console\Commands;

use App\Http\Controllers\UsersController;
use Illuminate\Console\Command;
use App\User;

class UserUnverify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:unverify {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unverify user by setting user verified status to False';

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
        $userId = $this->argument('userId');
        $user = User::find($userId);
        if($user){
            $this->info('User found.');
            $usersController = new UsersController();
            $usersController->unverify($userId);
        }else{
            $this->error('User not found');
        }
    }
}
