<x-app-layout>
    <style>
        .super-admin-stats {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .super-admin-stat-card {
            min-height: 180px;
        }

        @media (max-width: 640px) {
            .super-admin-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard do Super Admin
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('solicitacoes.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                    Ver solicitacoes
                </a>

                <a href="{{ route('historicos.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                    Ver historico
                </a>
            </div>
        </div>
    </x-slot>

    <div class="dashboard-page py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md border p-4 text-sm font-semibold" style="background-color: #dcfce7; border-color: #22c55e; color: #166534;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="super-admin-stats">
                <div class="dashboard-card super-admin-stat-card overflow-hidden ">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Funcionarios ativos</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalFuncionarios }}</div>
                    </div>
                </div>

                <div class="dashboard-card super-admin-stat-card overflow-hidden ">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Funcionarios excluidos</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $funcionariosExcluidos }}</div>
                    </div>
                </div>

                <div class="dashboard-card super-admin-stat-card overflow-hidden ">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Solicitacoes pendentes</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $solicitacoesPendentes }}</div>
                    </div>
                </div>

                <div class="dashboard-card super-admin-stat-card overflow-hidden ">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Admins de setor</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalAdminsSetor }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold leading-tight" style="color: #D8DDDD;">Setores cadastrados</h3>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($setores as $setor)
                        <div class="dashboard-card overflow-hidden ">
                            <div class="flex min-h-64 flex-col p-6">
                                <div>
                                    <div class="flex items-start justify-between gap-3">
                                        <h4 class="text-xl font-semibold text-gray-900">{{ $setor->name }}</h4>

                                        <a href="{{ route('funcionarios.create', $setor) }}" class="shrink-0 rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                            Registrar funcionario
                                        </a>
                                    </div>

                                    @if ($setor->description)
                                        <p class="mt-2 text-sm text-gray-500">{{ $setor->description }}</p>
                                    @endif
                                </div>

                                <div class="mt-5">
                                    <div class="text-sm font-semibold text-gray-700">Admin do setor</div>
                                    <div class="mt-1 text-sm text-gray-600">
                                        {{ $setor->admin?->name ?? 'Sem admin vinculado' }}
                                    </div>
                                </div>

                                <div class="mt-5 flex-1">
                                    <div class="text-sm font-semibold text-gray-700">Funcionarios</div>

                                    @if ($setor->funcionarios->isNotEmpty())
                                        <ul class="shadow-md">
                                            @foreach ($setor->funcionarios as $funcionario)
                                                <li x-data="{ aberto: false }" x-on:click="aberto = ! aberto" class="cursor-pointer  px-3 py-2 text-sm text-gray-700 transition hover:shadow-sm" style="background-color: #e8ebec;">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <button type="button" class="cursor-pointer text-left font-medium text-gray-800 hover:text-gray-950">
                                                            {{ $funcionario->name }}
                                                        </button>

                                                        <form method="POST" action="{{ route('funcionarios.destroy', $funcionario) }}" x-on:click.stop onsubmit="return confirm('Tem certeza que deseja excluir este funcionario?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="text-xs font-semibold uppercase tracking-widest text-red-600 hover:text-red-800">
                                                                Excluir
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <form x-show="aberto" x-cloak x-on:click.stop method="POST" action="{{ route('funcionarios.transfer', $funcionario) }}" class="mt-3 flex flex-col gap-2 sm:flex-row">
                                                    @csrf
                                                        @method('PATCH')

                                                        <select name="setor_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                            @foreach ($setores as $setorOpcao)
                                                                <option value="{{ $setorOpcao->id }}" @selected($funcionario->setor_id === $setorOpcao->id)>
                                                                    {{ $setorOpcao->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                                                            Mudar
                                                        </button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-2 text-sm text-gray-500">Nenhum funcionario cadastrado.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="dashboard-card overflow-hidden sm:rounded-lg md:col-span-2 lg:col-span-3">
                            <div class="p-6 text-gray-600">
                                Nenhum setor cadastrado ainda.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
