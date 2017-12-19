<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileManagerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUploadImage()
    {
        $response = $this->post('/api/admin/images/upload', [
            'file' => UploadedFile::fake()->image('avatar.jpg'),
            'fileName' => 'avatar.jpg'
        ]);
        
        $response->assertDontSee('errors');
    
    }
}
