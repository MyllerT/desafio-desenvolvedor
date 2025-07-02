<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use app\Models\UploadedFile;
use app\Models\InstrumentRecord;
use League\Csv\Reader;
use Exception;

class FileUploadController extends Controller
{
    private const ALLOWED_MIMES = 'csv,xlsx,xls';

   public function upload(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:' . self::ALLOWED_MIMES,
        ], [
            'file.required' => 'Nenhum arquivo foi enviado. Por favor, selecione um arquivo.',
            'file.file' => 'O item enviado não é um arquivo válido.',
            'file.mimes' => 'O tipo de arquivo não é permitido. Apenas arquivos CSV, XLSX e XLS são aceitos.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Erro de validação', 'errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $hash = md5_file($file->getPathname());

        try {
            if ($this->isFileDuplicate($filename, $hash)) {
                return response()->json(['message' => 'O arquivo com este nome ou conteúdo já foi enviado anteriormente.'], 409);
            }

            $path = $file->store('uploads');

            $uploadedFile = UploadedFile::create([
                'filename' => $filename,
                'hash' => $hash,
                'path' => $path,
                'uploaded_at' => now(),
            ]);

            return response()->json([
                'message' => 'Arquivo enviado e registrado com sucesso!',
                'file_id' => $uploadedFile->id,
                'filename' => $uploadedFile->filename,
                'path' => $uploadedFile->path
            ], 201);

        } catch (Exception $e) {
            \Log::error("Erro ao processar upload do arquivo '{$filename}': " . $e->getMessage());

            if (isset($path) && Storage::exists($path)) {
                Storage::delete($path);
            }

            return response()->json(['message' => 'Ocorreu um erro ao enviar o arquivo. Por favor, tente novamente mais tarde.'], 500);
        }
    }

    private function isFileDuplicate(string $filename, string $hash): bool
    {
        return UploadedFile::where('filename', $filename)
                           ->orWhere('hash', $hash)
                           ->exists();
    }

   
}