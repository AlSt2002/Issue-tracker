<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        Issue::factory(50)
            ->make()
            ->each(function (Issue $issue) use ($projects): void {
                $issue->project_id = $projects->random()->id;
                $issue->save();
            });
    }
}
