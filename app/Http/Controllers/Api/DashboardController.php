<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Annonces;
use App\Models\Demande;
use App\Http\Library\ApiHelpers;


class DashboardController extends Controller
{
    //
    use ApiHelpers;
    public function countProgrammes()
    {
        $programmesCount = Programme::count();

        return $this->OnSuccess($programmesCount, 'le nombre des programmes');
    }

    public function countAnnonces()
    {
        $annoncesCount = Annonces::count();

        return  $this->OnSuccess($annoncesCount, 'le nombre des annonces');}

    public function countDemandes()
    {
        $demandesCount = Demande::count();
        return $this->OnSuccess($demandesCount, 'le nombre des demandes');

        
    }
}

