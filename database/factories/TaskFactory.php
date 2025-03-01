<?php
namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->sentence(3),
            "description" => $this->faker->paragraph(),
            "status" => $this->faker->randomElement(['inprogress', 'completed', 'late']),
            "deadline" => $this->faker->dateTimeBetween('now', '+1 month'),
            "user_id" => User::inRandomOrder()->first()->id,  // Assign random user
            "project_id" => Project::inRandomOrder()->first()->id, // Assign random project
            "image" => $this->faker->imageUrl(),
        ];
    }
}
