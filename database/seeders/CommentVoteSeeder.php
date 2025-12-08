<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\User;

class CommentVoteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $comments = Comment::all();

        foreach ($comments as $comment) {

            // Each comment gets 0–5 votes
            $voteCount = rand(0, 5);

            for ($i = 0; $i < $voteCount; $i++) {

                $user = $users->random();

                // Prevent duplicate user vote
                if (CommentVote::where('comment_id', $comment->id)
                               ->where('user_id', $user->id)
                               ->exists()) {
                    continue;
                }

                CommentVote::create([
                    'comment_id' => $comment->id,
                    'user_id'    => $user->id,
                    'vote'       => rand(0, 1) ? 1 : -1, // like or dislike
                ]);
            }
        }
    }
}
