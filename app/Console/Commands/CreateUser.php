<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

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
        $name = $this->ask('What name?');
        $email = $this->ask('What email?');
        $password = Hash::make($this->secret('What password?'));

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->email_verified_at = now();
        $user->save();

        $this->info('<bg=green;fg=white>SUCCESS</> User created!');
    }
}
