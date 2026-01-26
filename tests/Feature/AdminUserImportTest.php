<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminUserImportTest extends TestCase
{
    public function test_admin_can_import_users_via_csv_real_content()
    {
        \Illuminate\Support\Facades\Queue::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true
        ]);

        $csvContent = "Rajat Malhotra, rajatmalhotra9999@gmail.com, jims123456";

        $file = UploadedFile::fake()->createWithContent('users.csv', $csvContent);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect(route('admin.users'));

        $this->assertDatabaseHas('users', [
            'email' => 'rajatmalhotra9999@gmail.com',
        ]);

        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\SendAccountCreatedEmailJob::class);
    }
}
