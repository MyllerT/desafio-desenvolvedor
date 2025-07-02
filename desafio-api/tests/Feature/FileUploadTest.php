<?php

namespace Tests\Feature;

use App\Models\UploadedFile as UploadedFileModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_upload_success()
    {
        Storage::fake('local');

        $content = 'header1,header2';
        $path = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($path, $content);

        $file = new UploadedFile($path, 'test_file.csv', 'text/csv', null, true);

        $response = $this->postJson('/api/files/upload', ['file' => $file]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Arquivo enviado e registrado com sucesso!']);

        Storage::disk('local')->assertExists('uploads/' . $file->hashName());

        $this->assertDatabaseHas('uploaded_files', [
            'filename' => 'test_file.csv',
            'hash' => md5_file($file->getPathname()),
            'path' => 'uploads/' . $file->hashName(),
        ]);
    }

    public function test_file_upload_duplicate_fails_by_hash()
    {
        Storage::fake('local');

        $content = 'duplicate,file';
        $path = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($path, $content);

        $file = new UploadedFile($path, 'file1.csv', 'text/csv', null, true);

        $this->postJson('/api/files/upload', ['file' => $file]);

        $duplicateFile = new UploadedFile($path, 'file2.csv', 'text/csv', null, true);
        $response = $this->postJson('/api/files/upload', ['file' => $duplicateFile]);

        $response->assertStatus(409)
                 ->assertJsonFragment(['message' => 'O arquivo com este nome ou conteúdo já foi enviado anteriormente.']);
    }

    public function test_file_upload_duplicate_fails_by_filename()
    {
        Storage::fake('local');

        $path = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($path, 'file1');

        $file = new UploadedFile($path, 'duplicate.csv', 'text/csv', null, true);

        $this->postJson('/api/files/upload', ['file' => $file]);

        $path2 = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($path2, 'file2');

        $file2 = new UploadedFile($path2, 'duplicate.csv', 'text/csv', null, true);
        $response = $this->postJson('/api/files/upload', ['file' => $file2]);

        $response->assertStatus(409)
                 ->assertJsonFragment(['message' => 'O arquivo com este nome ou conteúdo já foi enviado anteriormente.']);
    }

    public function test_file_upload_validation_fails_for_missing_file()
    {
        $response = $this->postJson('/api/files/upload', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('file');
    }

    public function test_file_upload_validation_fails_for_invalid_mime_type()
    {
        Storage::fake('local');

        $content = 'invalid content';
        $path = tempnam(sys_get_temp_dir(), 'txt');
        file_put_contents($path, $content);

        $file = new UploadedFile($path, 'test.txt', 'text/plain', null, true);

        $response = $this->postJson('/api/files/upload', ['file' => $file]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('file');
    }
}