<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard do Admin de Setor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Setor responsavel: <span class="font-semibold">{{ $setor?->name ?? 'Nao vinculado' }}</span>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Funcionarios do setor</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalFuncionarios }}</div>
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
                        <div class="text-sm font-medium text-gray-500">Solicitacoes decididas por voce</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $solicitacoesDecididas }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Esta visao mostra apenas informacoes e pendencias ligadas ao setor do admin logado.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
