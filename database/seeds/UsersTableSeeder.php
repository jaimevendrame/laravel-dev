<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\lotecweb\User::class,1)
            ->states('admin')
            ->create([
                'name' => 'Jaime Filho',
                'email' => 'admin@user.com',
                'idusu' => 1032,
            ]);

        factory(\lotecweb\User::class,1)
            ->create([
                'name' => 'Client Filho',
                'email' => 'client@user.com',
            ]);
    }
}
