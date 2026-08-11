<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Historico
            </h2>

            <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Registros de historico</h3>

                    <form method="GET" action="{{ route('historicos.index') }}" class="mt-4 flex flex-col gap-2 sm:flex-row">
                        <input
                            name="funcionario"
                            type="text"
                            value="{{ $funcionarioBusca }}"
                            placeholder="Buscar por funcionario afetado"
                            class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            Buscar
                        </button>

                        @if ($funcionarioBusca !== '')
                            <a href="{{ route('historicos.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                Limpar
                            </a>
                        @endif
                    </form>

                    @if ($historicos->isNotEmpty())
                        <div class="mt-4 overflow-hidden rounded-md border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Contexto</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Data</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario afetado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($historicos as $historico)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                {{ $historico->contexto ?? 'Sem contexto informado' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                {{ str_replace('_', ' ', ucfirst($historico->tipo)) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $historico->created_at?->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $historico->nome_funcionario_snapshot ?? $historico->funcionario?->name ?? 'Funcionario nao encontrado' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-500">Nenhum historico registrado ainda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
