<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'superadmin@sgf.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPER_ADMIN,
            'setor_id' => null,
        ]);

        $setor = Setor::updateOrCreate([
            'name' => 'Recursos Humanos',
        ], [
            'description' => 'Setor responsavel por pessoas, cargos e processos internos.',
        ]);

        $analista = Cargo::updateOrCreate([
            'name' => 'Analista de RH',
        ], [
            'description' => 'Atua nas rotinas operacionais e administrativas do setor.',
            'hierarchy' => 2,
        ]);

        $coordenador = Cargo::updateOrCreate([
            'name' => 'Coordenador de RH',
        ], [
            'description' => 'Coordena a equipe e acompanha indicadores do setor.',
            'hierarchy' => 3,
        ]);

        $assistente = Cargo::updateOrCreate([
            'name' => 'Assistente de RH',
        ], [
            'description' => 'Apoia as atividades administrativas do setor.',
            'hierarchy' => 1,
        ]);

        User::updateOrCreate([
            'email' => 'admin.rh@sgf.com',
        ], [
            'name' => 'Admin RH',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN_SETOR,
            'setor_id' => $setor->id,
        ]);

        Funcionario::updateOrCreate([
            'name' => 'Ana Souza',
        ], [
            'salary' => 4200,
            'cargo_id' => $analista->id,
            'setor_id' => $setor->id,
        ]);

        Funcionario::updateOrCreate([
            'name' => 'Carlos Lima',
        ], [
            'salary' => 6500,
            'cargo_id' => $coordenador->id,
            'setor_id' => $setor->id,
        ]);

        Funcionario::updateOrCreate([
            'name' => 'Mariana Costa',
        ], [
            'salary' => 2800,
            'cargo_id' => $assistente->id,
            'setor_id' => $setor->id,
        ]);
    }
}
