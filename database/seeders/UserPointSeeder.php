<?php

namespace Database\Seeders;

use App\Enums\PhysicalActivities;
use App\Models\User;
use Illuminate\Database\Seeder;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;

class UserPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding User Activites Points
        info('Seeding User Activites Points');
        note('This process may take a while, please wait...');

        $users = User::all();
        $progress = progress(label: 'Seeding User Activites Points', steps: $users->count());

        $progress->start();
        $users->each(function ($user) use ($progress) {
            $latestPoint = $user->activityPoints()->latest()->first();

            if($latestPoint) {
                $this->addPointsForOneWeek($user, $latestPoint);
            } else {
                $this->addPointsForPastYear($user);
            }

            $progress->advance();
        });
        $progress->finish();

        info('User Activites Points Seeded');

        // Updae User Rank
        info('Updating User Rank');
        note('This process may take a while, please wait...');
        $users = User::with('activityPoints')->get();
        $progress = progress(label: 'Updating User Rank', steps: $users->count());

        // update all users rank based on their points respected to other users
        $this->updateUserRank($users, $progress);
        $progress->finish();
    }

    private function addPointsForOneWeek(User $user, $latestPoint): void
    {
        $getActivities = PhysicalActivities::getActivities();
        $activityPoints = 20;
        $currentDate = $latestPoint->created_at;
        $skipCounter = 0;

        for ($i = 0; $i < 7; $i++) {
            $randomNumberOfActivities = rand(1, 3);

            // skip day randomly but 2 times in a week
            if (rand(0, 1) && $skipCounter < 2) {
                $currentDate = $currentDate->addDay();
                $skipCounter++;
                continue;
            }

            for ($j = 0; $j < $randomNumberOfActivities; $j++) {
                $activity = $getActivities[array_rand($getActivities)];

                // set timestamp to the next day
                $currentDate = $currentDate->addDay();

                $user->activityPoints()->create([
                    'points' => $activityPoints,
                    'activity' => $activity,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);
            }
        }
    }

    private function addPointsForPastYear(User $user): void
    {
        $getActivities = PhysicalActivities::getActivities();
        $activityPoints = 20;
        $currentDate = now()->startOfYear();
        $daysDiff = abs(round(now()->diffInDays($currentDate)));
        $skipCounter = 0;
        $dayCounter = 0;

        for ($i = 0; $i < $daysDiff; $i++) {
            $randomNumberOfActivities = rand(1, 3);
            $dayCounter++;

            // skip day randomly but not more than 3 times in 10 days
            if (rand(0, 1) && $skipCounter < 3 && $dayCounter % 10 !== 0) {
                $currentDate = $currentDate->addDay();
                $skipCounter++;
                continue;
            }

            if ($dayCounter % 10 === 0) {
                $dayCounter = 0;
                $skipCounter = 0;
            }

            for ($j = 0; $j < $randomNumberOfActivities; $j++) {
                $activity = $getActivities[array_rand($getActivities)];

                // set timestamp to the next day
                $currentDate = $currentDate->addDay();

                $user->activityPoints()->create([
                    'points' => $activityPoints,
                    'activity' => $activity,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);
            }
        }
    }

    private function updateUserRank($users, $progress): void
    {
        $progress->start();

        // Update overall rank
        $this->updateRank($users, 'totalPoints', $progress);

        // Update rank for today
        $this->updateRank($users, 'pointsToday', $progress);

        // Update rank for current month
        $this->updateRank($users, 'pointsThisMonth', $progress);

        // Update rank for current year
        $this->updateRank($users, 'pointsThisYear', $progress);
    }

    private function updateRank($users, $pointsMethod, $progress): void
    {
        $sortedUsers = $users->sortByDesc(function ($user) use ($pointsMethod) {
            return $user->$pointsMethod();
        });

        $rank = 1;
        $previousPoints = null;
        $sortedUsers->each(function ($user) use (&$rank, &$previousPoints, $pointsMethod, $progress) {
            if ($previousPoints !== null && $user->$pointsMethod() < $previousPoints) {
                $rank++;
            }
            if ($pointsMethod === 'totalPoints') {
                $user->rank = $rank;
            } elseif ($pointsMethod === 'pointsToday') {
                $user->today_rank = $rank;
            } elseif ($pointsMethod === 'pointsThisMonth') {
                $user->monthly_rank = $rank;
            } elseif ($pointsMethod === 'pointsThisYear') {
                $user->yearly_rank = $rank;
            }
            $user->save();

            $previousPoints = $user->$pointsMethod();
            $progress->advance();
        });
    }
}
