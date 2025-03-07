<?php
namespace Tests\Unit;
use App\Models\Admin;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // تشغيل Seeder لتحميل البيانات في قاعدة البيانات قبل كل اختبار
        $this->seed(DatabaseSeeder::class);

        $this->admin = Admin::first() ?? Admin::factory()->create();
        $this->user = User::first() ?? User::factory()->create();
        $this->task = Task::first() ?? Task::factory()->create();
    }
    // public function test_admin_authentication_works()
    // {

    //     // تسجيل الدخول باستخدام Sanctum
    //     Sanctum::actingAs($this->admin, ['*'], 'admin');

    //     $response = $this->getJson('/api/task'); // أي Route تتطلب Auth


    //     // يجب أن تكون الاستجابة 200
    //     $response->assertStatus(200);
    // }
    public function test_admin_can_create_task()
    {
        // تأكد من أن المشرف لديه Token
        Sanctum::actingAs($this->admin, ['*'], 'admin'); // استخدم الـ guard الصحيح

        $taskData = Task::factory()->make()->toArray();
        // dd($taskData);

        $response = $this->postJson('/api/admin/create-task', $taskData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['name' => 'New Task']);
    }

    // public function test_admin_can_edit_task()
    // {
    //     $response = $this->actingAs($this->admin)->putJson("/api/tasks/{$this->task->id}", [
    //         'name' => 'Updated Task'
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('tasks', ['id' => $this->task->id, 'name' => 'Updated Task']);
    // }

    // public function test_editing_non_existent_task_returns_404()
    // {
    //     $response = $this->actingAs($this->admin)->putJson('/api/tasks/999', [
    //         'name' => 'Updated Task'
    //     ]);

    //     $response->assertStatus(404);
    // }

    // public function test_admin_can_delete_task()
    // {
    //     $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$this->task->id}");

    //     $response->assertStatus(200);
    //     $this->assertSoftDeleted('tasks', ['id' => $this->task->id]);
    // }

    // public function test_non_admin_cannot_delete_task()
    // {
    //     $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$this->task->id}");

    //     $response->assertStatus(403);
    // }

    // public function test_admin_can_restore_task()
    // {
    //     $this->task->delete();
    //     $response = $this->actingAs($this->admin)->postJson("/api/tasks/restore/{$this->task->id}");

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('tasks', ['id' => $this->task->id]);
    // }

    // public function test_admin_can_reassign_task()
    // {
    //     $newUser = User::skip(2)->first(); // استرجاع مستخدم مختلف

    //     $response = $this->actingAs($this->admin)->putJson("/api/tasks/{$this->task->id}/assign/{$newUser->id}");

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('tasks', ['id' => $this->task->id, 'user_id' => $newUser->id]);
    // }

    // public function test_admin_can_assign_project_to_user()
    // {
    //     $projectId = 1;
    //     $response = $this->actingAs($this->admin)->postJson("/api/users/{$this->user->id}/projects/{$projectId}");

    //     $response->assertStatus(201);
    // }

    // public function test_cannot_create_task_with_invalid_data()
    // {
    //     $response = $this->actingAs($this->admin)->postJson('/api/tasks', [
    //         'name' => ''
    //     ]);

    //     $response->assertStatus(422);
    // }

}
