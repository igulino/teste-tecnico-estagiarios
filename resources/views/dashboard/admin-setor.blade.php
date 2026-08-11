<x-app-layout>
    <style>
        .admin-setor-main {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .admin-setor-main {
                grid-template-columns: minmax(280px, 0.8fr) minmax(560px, 1.2fr);
            }
        }

        .solicitacoes-feitas-table {
            min-width: 560px;
        }

        .admin-setor-left {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .admin-setor-summary {
            display: grid;
            gap: 0.5rem;
        }

        @media (min-width: 640px) {
            .admin-setor-summary {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .admin-setor-table-wrapper {
            border: 0;
        }

        .admin-setor-table-head {
            background-color: #4A4D4C;
        }

        .admin-setor-table-head th {
            color: #E8EBEC;
        }

        .admin-setor-table-body {
            background-color: transparent;
        }

        .admin-setor-clickable-row {
            cursor: pointer;
            transition: background-color 150ms ease, box-shadow 150ms ease;
        }

        .admin-setor-clickable-row:hover {
            background-color: rgba(232, 235, 236, 0.35);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard do Admin de Setor
            </h2>

            <div class="flex items-center gap-3">
               

                <a href="{{ route('funcionarios.excluded') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                    Desfazer exclusao
                </a>
                 <a href="{{ route('solicitacoes.recebidas') }}" class="inline-flex h-9 w-9 items-center justify-center transition hover:opacity-80" title="Solicitacoes recebidas">
                    <img
                        src="{{ asset($possuiSolicitacoesRecebidasPendentes ? 'images/bellRedDot.png' : 'images/bell.png') }}"
                        alt="Solicitacoes recebidas"
                        class="h-11 w-11 object-contain"
                    >
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

            @if ($errors->any())
                <div class="mb-6 rounded-md border p-4 text-sm font-semibold" style="background-color: #fee2e2; border-color: #ef4444; color: #b91c1c;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="dashboard-card mb-6 overflow-hidden ">
                <div class="p-6 text-gray-900">
                    Setor responsavel: <span class="font-semibold">{{ $setor?->name ?? 'Nao vinculado' }}</span>
                </div>
            </div>

            <div class="admin-setor-main mt-6">
                <div class="admin-setor-left">
                    <div class="admin-setor-summary">
                        <div class="dashboard-card overflow-hidden ">
                            <div class="px-4 py-3">
                                <div class="text-xs font-medium text-gray-500">Funcionarios do <br>setor</div>
                                <div class="mt-1 text-xl font-semibold text-gray-900">{{ $totalFuncionarios }}</div>
                            </div>
                        </div>

                        <div class="dashboard-card overflow-hidden ">
                            <div class="px-4 py-3">
                                <div class="text-xs font-medium text-gray-500">Solicitacoes pendentes</div>
                                <div class="mt-1 text-xl font-semibold text-gray-900">{{ $solicitacoesPendentes }}</div>
                            </div>
                        </div>

                        <div class="dashboard-card overflow-hidden ">
                            <div class="px-4 py-3">
                                <div class="text-xs font-medium text-gray-500">Solicitacoes decididas por voce</div>
                                <div class="mt-1 text-xl font-semibold text-gray-900">{{ $solicitacoesDecididas }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-card overflow-hidden sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900">Funcionarios do setor</h3>

                                @if ($setor)
                                    <a href="{{ route('funcionarios.create', $setor) }}" class="shrink-0 rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                        Registrar funcionario
                                    </a>
                                @endif
                            </div>

                            @if ($funcionarios->isNotEmpty())
                                <div class="admin-setor-table-wrapper mt-4 overflow-hidden ">
                                    <table class="min-w-full">
                                        <thead class="admin-setor-table-head">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Cargo</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Salario</th>
                                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                            </tr>
                                        </thead>
                                        <tbody class="admin-setor-table-body">
                                            @foreach ($funcionarios as $funcionario)
                                                @php
                                                    $transferenciaPendente = in_array($funcionario->id, $funcionariosComTransferenciaPendente);
                                                @endphp

                                                <tr x-data="{ aberto: false }" x-on:click="aberto = ! aberto" class="admin-setor-clickable-row">
                                                    <td class="px-4 py-3 text-sm">
                                                        <button type="button" class="cursor-pointer text-left font-medium {{ $transferenciaPendente ? 'text-amber-600' : 'text-gray-900' }} hover:text-gray-950">
                                                            {{ $funcionario->name }}
                                                        </button>

                                                    <div x-show="aberto" x-cloak x-on:click.stop class="mt-3 grid gap-3 lg:grid-cols-3">
                                                        <form method="POST" action="{{ route('solicitacoes.transferencia.store') }}" class="flex flex-col gap-2">
                                                            @csrf
                                                            <input type="hidden" name="funcionario_id" value="{{ $funcionario->id }}">

                                                            <select name="setor_destino_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                                @foreach ($setores as $setorOpcao)
                                                                    <option value="{{ $setorOpcao->id }}">
                                                                        {{ $setorOpcao->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                                                                Solicitar transferencia
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('solicitacoes.aumento.store') }}" class="flex flex-col gap-2">
                                                            @csrf
                                                            <input type="hidden" name="funcionario_id" value="{{ $funcionario->id }}">

                                                            <input name="salario_proposto" type="number" min="0" step="0.01" placeholder="Novo salario" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                                            <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                                                Solicitar aumento
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('solicitacoes.promocao.store') }}" class="flex flex-col gap-2">
                                                            @csrf
                                                            <input type="hidden" name="funcionario_id" value="{{ $funcionario->id }}">

                                                            <select name="cargo_proposto_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                                @foreach ($cargos as $cargoOpcao)
                                                                    <option value="{{ $cargoOpcao->id }}" @selected($funcionario->cargo_id === $cargoOpcao->id)>
                                                                        {{ $cargoOpcao->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                                                Solicitar cargo
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $funcionario->cargo?->name ?? 'Sem cargo vinculado' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">R$ {{ number_format((float) $funcionario->salary, 2, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-right text-sm">
                                                    <form method="POST" action="{{ route('funcionarios.destroy', $funcionario) }}" x-on:click.stop onsubmit="return confirm('Tem certeza que deseja excluir este funcionario?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-red-600 transition hover:bg-red-50">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-gray-500">Nenhum funcionario cadastrado neste setor.</p>
                        @endif
                    </div>
                    </div>
                </div>

                <div class="dashboard-card overflow-hidden sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Solicitacoes feitas</h3>

                        @if ($solicitacoesFeitas->isNotEmpty())
                            <div class="admin-setor-table-wrapper mt-4 overflow-x-auto ">
                                <table class="solicitacoes-feitas-table w-full">
                                    <thead class="admin-setor-table-head">
                                        <tr>
                                            <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                            <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipo</th>
                                            <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                            <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="admin-setor-table-body">
                                        @foreach ($solicitacoesFeitas as $solicitacao)
                                            @php
                                                $status = $solicitacao->status instanceof \App\Enums\SolicitacaoStatus
                                                    ? $solicitacao->status
                                                    : \App\Enums\SolicitacaoStatus::tryFrom($solicitacao->status);
                                            @endphp

                                            <tr>
                                                <td class="whitespace-nowrap px-4 py-3 text-sm">
                                                    <span class="font-medium {{ $status === \App\Enums\SolicitacaoStatus::PENDENTE ? 'text-amber-600' : 'text-gray-700' }}">
                                                        {{ ucfirst($status?->value ?? $solicitacao->status) }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                                    {{ ucfirst($solicitacao->tipo->value) }}
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">
                                                    {{ $solicitacao->funcionario?->name ?? 'Funcionario removido' }}
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                                    @if ($status === \App\Enums\SolicitacaoStatus::PENDENTE)
                                                        <form method="POST" action="{{ route('solicitacoes.destroy', $solicitacao) }}" onsubmit="return confirm('Tem certeza que deseja desfazer esta solicitacao?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="whitespace-nowrap rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:opacity-85" style="background-color: #b91c1c;">
                                                                Desfazer
                                                            </button>
                                                        </form>
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
                            <p class="mt-4 text-sm text-gray-500">Nenhuma solicitacao feita ainda.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
