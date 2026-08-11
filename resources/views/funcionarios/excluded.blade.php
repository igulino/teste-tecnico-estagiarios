<x-app-layout>
    <style>
        .exclusoes-table-wrapper {
            border: 0;
            width: 100%;
        }

        .exclusoes-table {
            width: 100%;
            table-layout: fixed;
        }

        .exclusoes-table-head {
            background-color: #4A4D4C;
        }

        .exclusoes-table-head th {
            color: #E8EBEC;
        }

        .exclusoes-table-body {
            background-color: transparent;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Exclusoes de funcionarios
            </h2>

            <a href="{{ route('dashboard.admin-setor') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="dashboard-page py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md border p-4 text-sm font-semibold" style="background-color: #dcfce7; border-color: #22c55e; color: #166534;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="dashboard-card overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Funcionarios excluidos do setor</h3>

                    @if ($funcionarios->isNotEmpty())
                        <div class="exclusoes-table-wrapper mt-4 overflow-x-auto rounded-md">
                            <table class="exclusoes-table">
                                <thead class="exclusoes-table-head">
                                    <tr>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Cargo</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Salario</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Excluido em</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                    </tr>
                                </thead>
                                <tbody class="exclusoes-table-body">
                                    @foreach ($funcionarios as $funcionario)
                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">{{ $funcionario->name }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">{{ $funcionario->cargo?->name ?? 'Sem cargo vinculado' }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">R$ {{ number_format((float) $funcionario->salary, 2, ',', '.') }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">{{ $funcionario->deleted_at?->format('d/m/Y H:i') }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                                <form method="POST" action="{{ route('funcionarios.restore', $funcionario->id) }}">
                                                    @csrf

                                                    <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                                                        Desfazer
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-500">Nenhum funcionario excluido neste setor.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
