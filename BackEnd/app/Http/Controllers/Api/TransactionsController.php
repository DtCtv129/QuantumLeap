<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bilan;
use App\Models\Programme;
use App\Models\Caisse;
use App\Models\User;

class TransactionsController extends Controller
{
    //

    public function distribuerBudget(Request $request)
    {
        if ($this->isAdmin($request->user())) {
            $caisse = Caisse::est('id')->first();
            $programme = Programme::where('titre', $request->titre)->first();

            if ($programme->montant && $request->valeur <= $caisse->budget) {
                $nouveauMontant = $programme->montant + $request->valeur;
                $programme->update(['montant' => $nouveauMontant]);
                $nouveauBudget = $caisse->budget - $request->valeur;
               
                Bilan::create([
                    'budget_actuel' => $caisse->budget,
                    'titre_programme' => $programme->titre,
                    'transaction_value' => $request->valeur,
                    'date' => now(),
                    'budget_apres_trans' => $nouveauBudget,
                    'type' => 'débit'
                ]);
                
                $caisse->update(['budget' => $nouveauBudget]);
                return redirect()->back()->with('success', 'Budget distribué avec succès.');
            } else {
                return redirect()->back()->with('error', 'Erreur de distribution de budget.');
            }
        } else {
            return redirect()->back()->with('401', 'Accès non autorisé.');
        }
    }

    public function billan(Request $request)
    {
        if ($this->isAdmin($request->user())) {
            $transactions = Transactions::all();
            return response()->json($transactions);
        } else {
            return redirect()->back()->with('401', 'Accès non autorisé.');
        }
    }
}
