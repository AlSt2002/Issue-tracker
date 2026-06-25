<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class IssueAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::all();
        $users = User::all();

        Issue::all()->each(function (Issue $issue) use ($tags, $users): void {

            $issue->tags()->sync(
                $tags
                    ->random(rand(1, 4))
                    ->pluck('id')
                    ->toArray()
            );

            $issue->assignees()->sync(
                $users
                    ->random(rand(1, 3))
                    ->pluck('id')
                    ->toArray()
            );
        });
    }
}
