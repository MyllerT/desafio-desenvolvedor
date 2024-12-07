<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Models\FileData;


class FileController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,xlsx|max:2048',
            ]);

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileContent = file_get_contents($file);
            $fileHash = hash('sha256', $fileContent); // Gera o hash do arquivo

            // Verifica se o hash já existe na base de dados
            if (Upload::where('hash', $fileHash)->exists()) {
                return response()->json(['message' => 'Arquivo já enviado anteriormente.'], 400);
            }

            $path = $file->storeAs('uploads', $fileName);

            // Cria o registro na tabela Uploads
            $upload = Upload::create([
                'file_name' => $fileName,
                'file_path' => $path,
                'upload_date' => now(),
                'hash' => $fileHash, // Salva o hash no registro
            ]);

            // Processa o arquivo conforme o formato
            if ($file->getClientOriginalExtension() === 'csv') {
                $this->processCsv($file, $upload->id);
            } elseif ($file->getClientOriginalExtension() === 'xlsx') {
                $this->processExcel($file, $upload->id);
            }

            return response()->json(['message' => 'Arquivo carregado e processado com sucesso.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validação falhou.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro ao processar o upload.', 'details' => $e->getMessage()], 500);
        }
    }

    // Método processCsv já existente
    private function processCsv($file, $uploadId)
    {
        $rows = array_map('str_getcsv', file($file->getRealPath()));

        foreach ($rows as $row) {
            if (count($row) < 6) continue;

            try {
                FileData::create([
                    'upload_id' => $uploadId,
                    'RptDt' => $row[0],
                    'TckrSymb' => $row[1],
                    'MktNm' => $row[2],
                    'SctyCtgyNm' => $row[3],
                    'ISIN' => $row[4],
                    'CrpnNm' => $row[5],
                ]);
            } catch (\Exception $e) {
                continue;
            }
        }
    }


public function uploadHistory(Request $request)
{
    $query = Upload::query();

    if ($request->has('file_name')) {
        $query->where('file_name', 'like', '%' . $request->file_name . '%');
    }

    if ($request->has('upload_date')) {
        $query->whereDate('upload_date', $request->upload_date);
    }

    return response()->json($query->paginate(10));
}


public function searchContent(Request $request)
{
    $query = FileData::query();

    if ($request->has('TckrSymb')) {
        $query->where('TckrSymb', $request->TckrSymb);
    }

    if ($request->has('RptDt')) {
        $query->where('RptDt', $request->RptDt);
    }

    // Se não enviar nenhum parâmetro, retorna todos os resultados paginados
    if (!$request->has('TckrSymb') && !$request->has('RptDt')) {
        return response()->json($query->paginate(10));
    }

    return response()->json($query->paginate(10));
}


}
