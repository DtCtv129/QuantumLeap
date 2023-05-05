<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function show($id)
{
    $user = auth()->user();
    $profile = Profile::where('user_id', $id)->first();

    return response()->json(['user' => $user, 'profile' => $profile]);
}



public function create(Request $request)
{
    // Valider les entrées de l'utilisateur ici
    $user = auth()->user();
    $profile = new Profile;
    $profile->user_id = $user->id;
    // Ajouter d'autres attributs du profil ici
    $profile->save();
    // Retourner une réponse appropriée ici
    return response()->json([
        'message' => 'Profile created successfully!',
        'data' => $profile
    ], 201);
}




public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id
    ]);

    $user = User::find($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return response()->json(['message' => 'Profile updated successfully!', 'user' => $user]);
}



public function edit($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404); // Si l'utilisateur n'existe pas, on retourne une erreur 404 sous forme JSON
    }

    $profile = Profile::where('user_id', $id)->first();

    if (!$profile) {
        $profile = new Profile(['user_id' => $id]); // Si le profil n'existe pas, on en crée un nouveau avec l'ID de l'utilisateur
    }

    return response()->json(['user' => $user, 'profile' => $profile]); // Retourner les données de l'utilisateur et du profil sous forme JSON
}
}
