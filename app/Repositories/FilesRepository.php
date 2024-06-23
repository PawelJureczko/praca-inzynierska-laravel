<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FilesRepository
{
    public function uploadFile($request) {
        if ($request->hasFile('files')) {
            $files = $request->file('files');
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
            }
            return response()->json([
                'status' => 'ok',
                'id' => $fileId,
                'message' => 'Pliki zostały pomyślnie przesłane.']);
        }
        return response()->json(['message' => 'Wystąpił niespodziewany błąd.'], 500);
    }
}
