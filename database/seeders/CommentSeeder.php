<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Comment;
use App\Models\RecipeRating;

class CommentSeeder extends Seeder
    {
        private $commentTemplates = [
            // Positive comments (rating 4-5)
            'positive' => [
                'Absolutely delicious! Made this for dinner and my family loved it. Will definitely make it again!',
                'Best recipe I\'ve tried in a long time. The instructions were clear and easy to follow.',
                'This turned out amazing! Much better than I expected. Highly recommended.',
                'Love how simple yet flavorful this is. Perfect for a weeknight dinner!',
                'Five stars! The taste is incredible and the nutrition info is very helpful.',
                'Chef\'s kiss! This recipe is a game changer for my meal prep routine.',
                'Wow, this exceeded all my expectations. Definitely a keeper!',
                'Made this twice already this month. It\'s become our family favorite.',
            ],
            // Neutral comments (rating 3)
            'neutral' => [
                'It\'s good, but I had to adjust some ingredients to suit my taste.',
                'Nice recipe! Took a bit longer than expected but results were solid.',
                'Pretty straightforward to make. Tastes decent with some modifications.',
                'Did the job for a quick dinner. Could be enhanced with better spices.',
                'It\'s okay, nothing too special but worth trying.',
                'Decent recipe, though I preferred the original version I found elsewhere.',
            ],
            // Constructive comments (rating 2)
            'constructive' => [
                'The concept is good but the seasoning needs adjustment. Try adding more herbs.',
                'Decent attempt, but I found the texture a bit off. Maybe less cooking time?',
                'It\'s alright, though some steps could be clearer for beginners.',
                'Good base recipe, but would benefit from additional tips for better flavor.',
                'Not bad, but I had to make several changes to make it taste right.',
            ],
            // Suggestions (rating 1-2)
            'suggestions' => [
                'Could use more detail in the instructions. What heat level should I use?',
                'Had trouble with this one. Maybe add cooking temperatures or times?',
                'The ingredient proportions didn\'t work well for me. Need clarification.',
                'Interesting concept, but execution was challenging with the given instructions.',
            ],
        ];

        public function run(): void
        {
            $users = User::all();
            $recipes = Recipe::all();

            if ($users->isEmpty() || $recipes->isEmpty()) {
                return; // Skip if no users or recipes
            }

            foreach ($recipes as $recipe) {
                // Each recipe gets 4-8 comments from different users
                $commentCount = rand(4, 8);
                $commenters = $users->random(min($commentCount, $users->count()));

                foreach ($commenters as $user) {
                    // Prevent duplicate comments
                    if (Comment::where('recipe_id', $recipe->id)
                               ->where('user_id', $user->id)
                               ->exists()) {
                        continue;
                    }

                    // Generate rating weighted towards positive (4-5 is 50%, 3 is 25%, 1-2 is 25%)
                    $ratingRoll = rand(1, 100);
                    if ($ratingRoll <= 50) {
                        $rating = rand(4, 5);
                        $template = 'positive';
                    } elseif ($ratingRoll <= 75) {
                        $rating = 3;
                        $template = 'neutral';
                    } else {
                        $rating = rand(1, 2);
                        $template = rand(0, 1) ? 'constructive' : 'suggestions';
                    }

                    // Pick a random comment from the template
                    $content = $this->commentTemplates[$template][array_rand($this->commentTemplates[$template])];

                    // Save rating
                    RecipeRating::updateOrCreate(
                        ['recipe_id' => $recipe->id, 'user_id' => $user->id],
                        ['rating' => $rating]
                    );

                    // Create comment
                    Comment::create([
                        'recipe_id' => $recipe->id,
                        'user_id' => $user->id,
                        'content' => $content,
                        'rating' => $rating,
                        'is_approved' => true,
                    ]);
                }
            }
        }
    }
