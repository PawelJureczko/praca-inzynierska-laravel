<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FilesRepository
{
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
