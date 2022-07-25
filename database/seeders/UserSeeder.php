<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $teacher = User::create([
            'first_name' => 'Emmie',
            'last_name' => 'Baai',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'roles' => [Role::TEACHER]
        ]);

        $classGroup = ClassGroup::create([
            'name' => '3/4A',
            'user_id' => [$teacher->id]
        ]);

        $teacher->teachergroup_ids = [$classGroup->id];
        $teacher->save();

        // Easy to remember users to test with.
        $testUser = User::create([
            'first_name' => 'Bofin',
            'last_name' => 'Bot',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'roles' => [Role::STUDENT],
            'studentgroup_ids' => [$classGroup->id]
        ]);


        User::create([
            'first_name' => 'Fleur',
            'last_name' => 'Blaauw',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'roles' => [Role::ADMIN]
        ]);

        // Dummy data for bulk actions.
        $faker = Factory::create();

        $users = [];

        $users[] = $testUser->id;

        // Students
        for ($i = 0; $i < 25; $i++) {
            $user = User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password'),
                'roles' => [Role::STUDENT],
                'studentgroup_ids' => [$classGroup->id]
            ]);

            $users[] = $user->id;
        }
        $classGroup->student_ids = $users;
        $classGroup->save();

        // Teacher
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password'),
                'roles' => [Role::TEACHER]
            ]);
        }

        // Admin
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password'),
                'roles' => [Role::ADMIN]
            ]);
        }
    }
}
