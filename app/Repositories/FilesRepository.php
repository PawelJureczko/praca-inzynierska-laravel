<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FilesRepository
{

    public function downloadFile($fileId) {
        // Pobierz informacje o pliku z bazy danych
        $file = DB::table('files')->where('id', $fileId)->first();

        if (!$file) {
            return response()->json(['message' => 'Plik nie znaleziony.'], 404);
        }

        // Sprawdź, czy plik istnieje w storage
        $path = storage_path('app/' . $file->path);
        if (!file_exists($path)) {
            return response()->json(['message' => 'Plik nie znaleziony w storage.'], 404);
        }

        // Ustaw nagłówki odpowiedzi, aby zawierały nazwę pliku z odpowiednim rozszerzeniem
        return response()->download($path, $file->filename, [
            'Content-Disposition' => 'attachment; filename="' . $file->filename . '"'
        ]);
    }
    public function uploadFile($request) {
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $filesData = [];
            foreach ($files as $file) {
                // Zapisz plik
                $hashedName = $file->hashName();
                $path = $file->storeAs('public/uploads', $hashedName);

                // Zapisz informacje o pliku w bazie danych
                $fileId = DB::table('files')->insertGetId([
                    'filename' => $file->getClientOriginalName(),
                    'hashed_filename' => $hashedName,
                    'path' => $path,
                    'uploaded_by' => auth()->id(), // Jeśli masz autoryzację
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $filesData[] = [
                    'id' => $fileId,
                    'filename' => $file->getClientOriginalName(),
                ];
            }
            return response()->json([
                'status' => 'ok',
                'files' => $filesData,
                'message' => 'Pliki zostały pomyślnie przesłane.']);
        }
        return response()->json(['message' => 'Wystąpił niespodziewany błąd.'], 500);
    }
}
