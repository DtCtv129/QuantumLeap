<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Caisse;
use App\Models\Programme;


use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    //
    public function distribuerBudget(Request $request)
{
    $caisse = Caisse::first();
    $programme = Programme::where('titre', $request->titre)->first();

    if ($programme && $request->valeur <= $caisse->budget) {
        $programme->update(['montant' => $request->valeur]);
        $caisse->decrement('budget', $request->valeur);
        Transaction::create([
            'programme_id' => $programme->id,
            'montant' => $request->valeur,
            'type' => 'débit'
        ]);
        return redirect()->back()->with('success', 'Budget distribué avec succès.');
    } else {
        return redirect()->back()->with('error', 'Erreur de distribution de budget.');
    }
}
}
