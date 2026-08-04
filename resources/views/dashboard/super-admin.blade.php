<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard do Super Admin
            </h2>

            <a href="{{ route('setores.create') }}" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                Criar setor
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Funcionarios ativos</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalFuncionarios }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Funcionarios excluidos</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $funcionariosExcluidos }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Solicitacoes pendentes</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $solicitacoesPendentes }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Admins de setor</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalAdminsSetor }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900">Setores cadastrados</h3>

                <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($setores as $setor)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="flex min-h-64 flex-col p-6">
                                <div>
                                    <div class="flex items-start justify-between gap-3">
                                        <h4 class="text-xl font-semibold text-gray-900">{{ $setor->name }}</h4>

                                        <a href="{{ route('funcionarios.create', $setor) }}" class="shrink-0 rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                            Criar funcionario
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
                                        <ul class="mt-2 space-y-2">
                                            @foreach ($setor->funcionarios as $funcionario)
                                                <li class="rounded border border-gray-200 px-3 py-2 text-sm text-gray-700">
                                                    {{ $funcionario->name }}
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
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2 lg:col-span-3">
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
