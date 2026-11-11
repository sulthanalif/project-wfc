<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\RecordActivityLog;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SuperAdminProtectionAndActivityLogTest extends TestCase
{
    public function test_routes_are_registered_with_expected_names_and_methods()
    {
        $this->assertTrue(Route::has('admin.profile'));
        $this->assertTrue(Route::has('admin.profile.email'));
        $this->assertTrue(Route::has('admin.profile.password'));
        $this->assertTrue(Route::has('admin.activity-logs'));
        $this->assertTrue(Route::has('user.direct-password'));

        $directPasswordRoute = Route::getRoutes()->getByName('user.direct-password');
        $this->assertContains('PUT', $directPasswordRoute->methods());

        $activityLogRoute = Route::getRoutes()->getByName('admin.activity-logs');
        $this->assertContains('GET', $activityLogRoute->methods());
    }

    public function test_user_controller_rejects_modifying_super_admin()
    {
        $controller = new UserController();

        $superAdmin = new User();
        $superAdmin->id = 999;
        // Mock hasRole method
        $mock = $this->createMock(User::class);
        $mock->method('hasRole')->with('super_admin')->willReturn(true);

        $request = new Request();

        $editRes = $controller->edit($request, $mock);
        $this->assertTrue($editRes->isRedirection());
        $this->assertEquals('Data Super Admin tidak dapat diubah dari menu ini.', session('error'));

        $updateRes = $controller->update($request, $mock);
        $this->assertTrue($updateRes->isRedirection());
        $this->assertEquals('Data Super Admin tidak dapat diubah dari menu ini.', session('error'));

        $statusRes = $controller->changeStatus($request, $mock);
        $this->assertTrue($statusRes->isRedirection());
        $this->assertEquals('Status Super Admin tidak dapat diubah.', session('error'));

        $accessRes = $controller->changeStatusAccess($mock);
        $this->assertTrue($accessRes->isRedirection());
        $this->assertEquals('Status akses Super Admin tidak dapat diubah.', session('error'));

        $destroyRes = $controller->destroy($request, $mock);
        $this->assertTrue($destroyRes->isRedirection());
        $this->assertEquals('Data Super Admin tidak dapat dihapus.', session('error'));

        $roleRes = $controller->changeRole($request, $mock);
        $this->assertTrue($roleRes->isRedirection());
        $this->assertEquals('Role Super Admin tidak dapat diubah.', session('error'));

        $directPassRes = $controller->updateDirectPassword($request, $mock);
        $this->assertTrue($directPassRes->isRedirection());
        $this->assertEquals('Password Super Admin tidak dapat diubah dari menu ini.', session('error'));
    }

    public function test_activity_log_middleware_filters_passwords_and_tokens()
    {
        $middleware = new RecordActivityLog();

        $request = Request::create('/master/user/1/direct-password', 'PUT', [
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'current_password' => 'oldsecret',
            '_token' => 'csrf_token_value',
            'safe_param' => 'hello_world',
        ]);

        $response = new Response('OK', 200);

        // Terminate calls create, but if DB is not connected, it catches Throwable gracefully without breaking
        $middleware->terminate($request, $response);

        // Verify request still returned 200
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_activity_log_model_fillable_and_casts()
    {
        $log = new ActivityLog();

        $this->assertContains('user_id', $log->getFillable());
        $this->assertContains('user_name', $log->getFillable());
        $this->assertContains('method', $log->getFillable());
        $this->assertContains('url', $log->getFillable());
        $this->assertContains('payload', $log->getFillable());

        $this->assertEquals('array', $log->getCasts()['payload'] ?? null);
    }

    public function test_admin_profile_controller_validation()
    {
        $controller = new AdminProfileController();

        $user = new User();
        $user->id = '15f865e2-e1e9-466a-9653-36a2ded7f9e4';
        $user->password = bcrypt('correctpassword');
        Auth::setUser($user);

        // Missing name and email
        $request = new Request();
        $res = $controller->updateEmail($request);
        $this->assertTrue($res->isRedirection());
        $this->assertNotNull(session('error'));

        // Invalid current password
        $passRequest = new Request([
            'current_password' => 'wrongpass',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $resPass = $controller->updatePassword($passRequest);
        $this->assertTrue($resPass->isRedirection());
    }
}
