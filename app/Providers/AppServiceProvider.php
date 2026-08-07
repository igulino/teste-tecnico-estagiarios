<?php

namespace App\Providers;

use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Setor;
use App\Models\Solicitacao;
use App\Models\User;
use App\Policies\CargoPolicy;
use App\Policies\FuncionarioPolicy;
use App\Policies\SetorPolicy;
use App\Policies\SolicitacaoPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Cargo::class, CargoPolicy::class);
        Gate::policy(Funcionario::class, FuncionarioPolicy::class);
        Gate::policy(Setor::class, SetorPolicy::class);
        Gate::policy(Solicitacao::class, SolicitacaoPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
