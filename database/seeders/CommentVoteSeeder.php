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

            if ($users->isEmpty() || $comments->isEmpty()) {
                return; // Skip if no users or comments
            }

            foreach ($comments as $comment) {
                // Comments with higher ratings get more likes (0-10)
                // Comments with lower ratings get more dislikes
                if ($comment->rating >= 4) {
                    // High rating: mostly likes (7-10 likes, 0-2 dislikes)
                    $likeCount = rand(7, 10);
                    $dislikeCount = rand(0, 2);
                } elseif ($comment->rating == 3) {
                    // Neutral: balanced (3-5 likes, 2-4 dislikes)
                    $likeCount = rand(3, 5);
                    $dislikeCount = rand(2, 4);
                } else {
                    // Low rating: mostly dislikes (2-5 likes, 4-8 dislikes)
                    $likeCount = rand(2, 5);
                    $dislikeCount = rand(4, 8);
                }

                // Create like votes
                $likeVoters = $users->random(min($likeCount, $users->count()));
                foreach ($likeVoters as $user) {
                    if (!CommentVote::where('comment_id', $comment->id)
                                    ->where('user_id', $user->id)
                                    ->exists()) {
                        CommentVote::create([
                            'comment_id' => $comment->id,
                            'user_id'    => $user->id,
                            'vote'       => 1, // like
                        ]);
                    }
                }

                // Create dislike votes (from different users)
                $allUserIds = $users->pluck('id')->toArray();
                $likerIds = $likeVoters->pluck('id')->toArray();
                $availableDislikers = array_diff($allUserIds, $likerIds);

                if (!empty($availableDislikers)) {
                    $dislikeVoters = User::whereIn('id', array_slice($availableDislikers, 0, min($dislikeCount, count($availableDislikers))))->get();
                    foreach ($dislikeVoters as $user) {
                        if (!CommentVote::where('comment_id', $comment->id)
                                        ->where('user_id', $user->id)
                                        ->exists()) {
                            CommentVote::create([
                                'comment_id' => $comment->id,
                                'user_id'    => $user->id,
                                'vote'       => -1, // dislike
                            ]);
                        }
                    }
                }
            }
        }
    }
