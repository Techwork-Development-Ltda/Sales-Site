<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AuthModel;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertTrue;

class UserControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    private string $token = '';
    private AuthModel $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = AuthModel::factory()->create([
            'email' => 'admin@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $this->token = JWTAuth::fromUser($this->authUser);
    }

    public function test_assert_true(): void
    {
        $true = true;
        assertTrue($true);
    }

    public function test_getUserById_status_200(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")->getJson('/api/v1/user/'.$this->authUser->id);
        $response->assertStatus(200)->assertJsonPath('status', true);
    }

    public function test_getUserById_status_422_unprocessable_entity(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")->getJson('/api/v1/user/a'.$this->authUser->id);
        $response->assertStatus(422);
    }
}