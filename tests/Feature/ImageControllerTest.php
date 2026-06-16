<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_can_be_uploaded()
    {
        
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->postJson(route('image.upload'), [
            'file' => $file,
        ]);

        Storage::disk('public')->assertExists('temp/' . $file->hashName());

        $this->assertContains('temp/' . $file->hashName(), session('uploadedImages', []));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'temp/' . $file->hashName(),
            'original_name' => 'test-image.jpg',
        ]);
    }
    public function test_image_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');
    
        $file = UploadedFile::fake()->image('test-image.jpg');
        $path = $file->store('temp', 'public');
    
        session(['uploadedImages' => [$path]]);
    
        $response = $this->deleteJson(route('image.delete'), [
            'path' => $path,
        ]);
    
        Storage::disk('public')->assertMissing($path);
    
        $this->assertNotContains($path, session('uploadedImages', []));
    
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }    
}