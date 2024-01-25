<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use App\Services\UserService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserServiceTest extends TestCase
{
    protected $userService;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // create a fake user
        $user = User::factory()->create();

        // login the user
        $this->actingAs($user);

        // instantiate the UserService
        $this->userService = app(UserService::class);
    }

    /** @test */
    public function it_can_retrieve_account_info()
    {
        $data = $this->userService->getAccountInfo();

        $this->assertTrue($data['success']);
        $this->assertEquals(Auth::id(), $data['data']['id']);
    }

    /** @test */
    public function it_can_update_account_info()
    {
        $newData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890'
        ];

        $this->userService->updateAccountInfo(Request::create('/', 'PUT', $newData));

        $user = User::find(Auth::id());

        $this->assertEquals($newData['name'], $user->name);
        $this->assertEquals($newData['email'], $user->email);
        $this->assertEquals($newData['phone'], $user->phone);
    }
}
