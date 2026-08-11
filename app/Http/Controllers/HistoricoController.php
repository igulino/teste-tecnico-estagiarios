<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class HistoricoController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewSuperAdminDashboard', User::class);

        $funcionario = $request->string('funcionario')->trim()->toString();

        return view('historicos.index', [
            'funcionarioBusca' => $funcionario,
            'historicos' => Historico::query()
                ->with('funcionario')
                ->when($funcionario !== '', function ($query) use ($funcionario) {
                    $query->where(function ($query) use ($funcionario) {
                        $query->where('nome_funcionario_snapshot', 'like', "%{$funcionario}%")
                            ->orWhereHas('funcionario', function ($query) use ($funcionario) {
                                $query->where('name', 'like', "%{$funcionario}%");
                            });
                    });
                })
                ->latest()
                ->get(),
        ]);
    }
}
