<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        Project::factory(5)
            ->make()
            ->each(function (Project $project) use ($users): void {
                $project->owner_id = $users->random()->id;
                $project->save();
            });
    }
}
