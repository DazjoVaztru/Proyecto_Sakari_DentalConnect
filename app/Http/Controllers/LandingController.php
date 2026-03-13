<?php

namespace App\Http\Controllers;

use App\Models\PlanSaas;
use App\Models\Publicidad;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        $planes = PlanSaas::where('activo', true)->orderBy('nivel')->get();
        $publicidad = Publicidad::where('activo', true)->latest()->take(6)->get();

        $suscripcionActiva = null;

        if (Auth::check() && Auth::user()->id_clinica) {
            $suscripcionActiva = Auth::user()->clinica
                ?->suscripcionActiva()
                ->with('plan')
                ->first();
        }

        return view('landing.index', compact('planes', 'publicidad', 'suscripcionActiva'));
    }
}
