<?php

namespace App\Http\Controllers;

use App\Attributes\Route\Get;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;


class TestController extends Controller
{
    public function index() {
        $testData = DB::select('SELECT * FROM users'); // Zastąp 'your_table' nazwą rzeczywistej tabeli w bazie danych

        return Inertia::render('Test', [
            'event' => 'elo',
            'testowy_props' => 'test',
            'testdata' => $testData
        ]);
    }

    public function create() {
        return Inertia::render('Test/Create');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
        ]);

        Test::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
        ]);

        // Renderuj komponent Test/Create
        return response()->json();
    }

    public function edit($id)
    {
        $record = Test::find($id);

        return Inertia::render('Test/Edit', [
            'record' => $record
        ]);
    }

    public function update(Request $request, $id)
    {
        // Walidacja danych
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
        ]);

        // Znajdź rekord do zaktualizowania
        $record = Test::findOrFail($id);

        // Zaktualizuj dane rekordu
        $record->update([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            // Dodaj inne pola z formularza
        ]);

        // Odpowiedź do frontendu (możesz dostosować odpowiedź do swoich potrzeb)
        return response()->json(['message' => 'Rekord został zaktualizowany pomyślnie']);
    }




    /**
     * Display the user's profile form.
     */
//    public function edit(Request $request): Response
//    {
//        return Inertia::render('Edit', [
//            'status' => session('status'),
//        ]);
//    }

    /**
     * Update the user's profile information.
     */
//    public function update(ProfileUpdateRequest $request): RedirectResponse
//    {
//        $request->user()->fill($request->validated());
//
//        if ($request->user()->isDirty('email')) { //sprawdza czy zmienil sie mail
//            $request->user()->email_verified_at = null;
//        }
//
//        $request->user()->save();
//
//        return Redirect::route('dashboard');
//    }

    /**
     * Delete the user's account.
     */
//    public function destroy(Request $request): RedirectResponse
//    {
//        $request->validate([
//            'password' => ['required', 'current_password'],
//        ]);
//
//        $user = $request->user();
//
//        Auth::logout();
//
//        $user->delete();
//
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
//
//        return Redirect::to('/');
//    }
}
