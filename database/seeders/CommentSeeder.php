<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        Issue::all()->each(function (Issue $issue): void {
            Comment::factory(rand(2, 8))
                ->create([
                    'issue_id' => $issue->id,
                ]);
        });
    }
}
