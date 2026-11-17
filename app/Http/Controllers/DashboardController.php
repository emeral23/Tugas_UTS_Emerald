<?php

namespace App\Http\Controllers;

use App\Models\Anotasi;
use App\Models\Habitat;
use App\Models\Rekaman;
use App\Models\TitikPerekaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistics = [
            ['label' => 'Data Rekaman', 'count' => Rekaman::count(), 'route' => 'rekaman.index'],
            ['label' => 'Habitat & Ekosistem', 'count' => Habitat::count(), 'route' => 'habitats.index'],
            ['label' => 'Titik Perekaman', 'count' => TitikPerekaman::count(), 'route' => 'titik-perekaman.index'],
            ['label' => 'Anotasi Suara', 'count' => Anotasi::count(), 'route' => 'anotasi.index'],
        ];

        $rekamansTerbaru = Rekaman::with('habitat')
            ->latest('tanggal_perekaman')
            ->limit(5)
            ->get();

        return view('dashboard', compact('statistics', 'rekamansTerbaru'));
    }
}
