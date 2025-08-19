<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CodeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user and assign the 'super-admin' role
        $this->user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $this->user->assignRole($role);

        // Grant all permissions to the 'super-admin' role
        $permissions = [
            'view_code',
            'create_code',
            'edit_code',
            'delete_code',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
            $role->givePermissionTo($permissionName);
        }

        $this->actingAs($this->user);
    }

    /** @test */
    public function a_user_can_view_codes_index(){
        $this->withoutExceptionHandling();
        $response = $this->get(route('codes.index'));
        $response->assertOk();
        $response->assertViewIs('dashboard.codes.index');
    }

    /** @test */
    public function a_user_can_view_code_details()
    {
        $this->withoutExceptionHandling();
        $code = Code::factory()->create();
        $response = $this->get(route('codes.show', $code->id));
        $response->assertOk();
        $response->assertViewIs('dashboard.codes.show');
        $response->assertSee($code->code);
    }

    /** @test */
    public function a_user_can_create_a_code()
    {
        $this->withoutExceptionHandling();
        $codeData = Code::factory()->make()->toArray();
        $response = $this->post(route('codes.store'), $codeData);
        $response->assertRedirect(route('codes.index'));
        $this->assertDatabaseHas('codes', ['code' => $codeData['code']]);
    }

    /** @test */
    public function a_user_can_update_a_code()
    {
        $this->withoutExceptionHandling();
        $code = Code::factory()->create();
        $updatedData = [
            'code' => 'UPDATED_CODE',
            'for' => 'lesson',
            'number_of_uses' => 10,
            'expires_at' => now()->addDays(30)->format('Y-m-d'),
        ];
        $response = $this->put(route('codes.update', $code->id), $updatedData);
        $response->assertRedirect(route('codes.index'));
        $this->assertDatabaseHas('codes', ['id' => $code->id, 'code' => 'UPDATED_CODE']);
    }

    /** @test */
    public function a_user_can_delete_a_code()
    {
        $this->withoutExceptionHandling();
        $code = Code::factory()->create();
        $response = $this->delete(route('codes.destroy', $code->id));
        $response->assertRedirect(route('codes.index'));
        $this->assertDatabaseMissing('codes', ['id' => $code->id]);
    }
}
