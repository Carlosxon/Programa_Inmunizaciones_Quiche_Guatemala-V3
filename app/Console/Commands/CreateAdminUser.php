<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // se agregan los datos del usuario administrador
        $name = $this->ask('Enter name');
        $email = $this->ask('Enter email');
        $password = $this->secret('Enter password');

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Admin user created successfully.');
        return 0;
    }
}

