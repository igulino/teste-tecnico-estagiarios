<x-app-layout>
    <style>
        .solicitacoes-table-wrapper {
            border: 0;
            width: 100%;
        }

        .solicitacoes-table {
            width: 100%;
            table-layout: fixed;
        }

        .solicitacoes-table-head {
            background-color: #4A4D4C;
        }

        .solicitacoes-table-head th {
            color: #E8EBEC;
        }

        .solicitacoes-table-body {
            background-color: transparent;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Solicitacoes recebidas
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

            @if ($errors->any())
                <div class="mb-6 rounded-md border p-4 text-sm font-semibold" style="background-color: #fee2e2; border-color: #ef4444; color: #b91c1c;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="dashboard-card overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    @if ($solicitacoesRecebidas->isNotEmpty())
                        <div class="solicitacoes-table-wrapper overflow-x-auto rounded-md">
                            <table class="solicitacoes-table">
                                <thead class="solicitacoes-table-head">
                                    <tr>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipo</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                        <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                    </tr>
                                </thead>
                                <tbody class="solicitacoes-table-body">
                                    @foreach ($solicitacoesRecebidas as $solicitacao)
                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                                <span class="font-medium {{ $solicitacao->status->value === 'pendente' ? 'text-amber-600' : 'text-gray-700' }}">
                                                    {{ ucfirst($solicitacao->status->value) }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                                {{ ucfirst($solicitacao->tipo->value) }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">
                                                {{ $solicitacao->funcionario?->name ?? 'Funcionario removido' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                                @if ($solicitacao->status->value === 'pendente')
                                                    <div class="flex justify-end gap-2">
                                                        <form method="POST" action="{{ route('solicitacoes.accept', $solicitacao) }}">
                                                            @csrf

                                                            <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                                                                Aceitar
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('solicitacoes.reject', $solicitacao) }}">
                                                            @csrf

                                                            <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-red-600 transition hover:bg-red-50">
                                                                Recusar
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500">Decidida</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Nenhuma solicitacao recebida ainda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
