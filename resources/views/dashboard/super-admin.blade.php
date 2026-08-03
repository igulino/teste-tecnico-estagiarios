<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard do Super Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Visao geral da empresa inteira. Este perfil pode acompanhar e decidir solicitacoes de qualquer setor.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
