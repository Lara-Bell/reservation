<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 15)->create();

        factory(App\Appointment::class, 700)->create();

        factory(App\Holiday::class, 450)->create();

        $users = App\User::all();

        App\Appointment::all()->each(function ($appointment) use ($users) {
            $appointment->users()->attach(
                $users->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

    }
}
