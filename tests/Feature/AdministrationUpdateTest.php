<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdministrationUpdateTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_upload_creates_record_for_agent_without_files(): void
    {
        Storage::fake('public');
        $admin = User::role('super_admin')->firstOrFail();
        $agent = User::role('agent')->doesntHave('administration')->first()
            ?? tap(User::role('agent')->firstOrFail(), fn ($u) => $u->administration()->delete());

        $this->actingAs($admin)->put(route('updateAdministration', $agent), [
            'ktp' => UploadedFile::fake()->image('ktp.jpg'),
            'sPerjanjian' => UploadedFile::fake()->image('sp.jpg'),
        ])->assertSessionHas('success');

        $adm = $agent->fresh()->administration;
        $this->assertNotNull($adm);
        Storage::disk('public')->assertExists('images/administration/' . $adm->ktp);
        Storage::disk('public')->assertExists('images/administration/' . $adm->sPerjanjian);
    }

    public function test_admin_upload_single_file_without_record_is_rejected(): void
    {
        Storage::fake('public');
        $admin = User::role('super_admin')->firstOrFail();
        $agent = tap(User::role('agent')->firstOrFail(), fn ($u) => $u->administration()->delete());

        $this->actingAs($admin)->put(route('updateAdministration', $agent), [
            'ktp' => UploadedFile::fake()->image('ktp.jpg'),
        ])->assertSessionHas('error');

        $this->assertNull($agent->fresh()->administration);
    }

    public function test_agent_cannot_update_other_agent_files(): void
    {
        [$agent, $other] = User::role('agent')->take(2)->get()->all();

        $this->actingAs($agent)->put(route('updateAdministration', $other), [
            'ktp' => UploadedFile::fake()->image('ktp.jpg'),
        ])->assertForbidden();
    }
}
