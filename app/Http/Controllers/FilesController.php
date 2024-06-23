<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{
    public function upload(Request $request) {
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                // Zapisz plik
                $hashedName = $file->hashName();
                $path = $file->storeAs('public/uploads', $hashedName);

                // Zapisz informacje o pliku w bazie danych
                DB::table('files')->insert([
                    'filename' => $file->getClientOriginalName(),
                    'hashed_filename' => $hashedName,
                    'path' => $path,
                    'uploaded_by' => auth()->id(), // Jeśli masz autoryzację
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            return response()->json(['message' => 'Pliki zostały pomyślnie przesłane.']);
        }
        return response()->json(['message' => 'Nie wybrano żadnych plików.'], 400);
    }
}
