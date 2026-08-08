<x-app-layout>
    <style>
        .admin-setor-panels {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .admin-setor-panels {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard do Admin de Setor
            </h2>

            <a href="{{ route('funcionarios.excluded') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                Desfazer exclusao
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

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4 text-sm font-medium text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

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
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Solicitacoes recebidas</h3>

                    @if ($solicitacoesRecebidas->isNotEmpty())
                        <div class="mt-4 overflow-hidden rounded-md border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($solicitacoesRecebidas as $solicitacao)
                                        <tr>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="font-medium {{ $solicitacao->status->value === 'pendente' ? 'text-amber-600' : 'text-gray-700' }}">
                                                    {{ ucfirst($solicitacao->status->value) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ ucfirst($solicitacao->tipo->value) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                {{ $solicitacao->funcionario?->name ?? 'Funcionario removido' }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm">
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
                        <p class="mt-4 text-sm text-gray-500">Nenhuma solicitacao recebida ainda.</p>
                    @endif
                </div>
            </div>

            <div class="admin-setor-panels mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-lg font-semibold text-gray-900">Funcionarios do setor</h3>

                            @if ($setor)
                                <a href="{{ route('funcionarios.create', $setor) }}" class="shrink-0 rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                    Criar funcionario
                                </a>
                            @endif
                        </div>

                        @if ($funcionarios->isNotEmpty())
                            <div class="mt-4 overflow-hidden rounded-md border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Cargo</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Salario</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($funcionarios as $funcionario)
                                            @php
                                                $transferenciaPendente = in_array($funcionario->id, $funcionariosComTransferenciaPendente);
                                            @endphp

                                            <tr x-data="{ aberto: false }">
                                                <td class="px-4 py-3 text-sm">
                                                    <button type="button" x-on:click="aberto = ! aberto" class="text-left font-medium {{ $transferenciaPendente ? 'text-amber-600' : 'text-gray-900' }} hover:text-gray-950">
                                                        {{ $funcionario->name }}
                                                    </button>

                                                    <div x-show="aberto" x-cloak class="mt-3 grid gap-3 lg:grid-cols-3">
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
                                                    <form method="POST" action="{{ route('funcionarios.destroy', $funcionario) }}" onsubmit="return confirm('Tem certeza que deseja excluir este funcionario?')">
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Solicitacoes feitas</h3>

                        @if ($solicitacoesFeitas->isNotEmpty())
                            <div class="mt-4 overflow-hidden rounded-md border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipo</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Funcionario</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($solicitacoesFeitas as $solicitacao)
                                            <tr>
                                                <td class="px-4 py-3 text-sm">
                                                    <span class="font-medium {{ $solicitacao->status->value === 'pendente' ? 'text-amber-600' : 'text-gray-700' }}">
                                                        {{ ucfirst($solicitacao->status->value) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    {{ ucfirst($solicitacao->tipo->value) }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                    {{ $solicitacao->funcionario?->name ?? 'Funcionario removido' }}
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm">
                                                    @if ($solicitacao->status->value === 'pendente')
                                                        <form method="POST" action="{{ route('solicitacoes.destroy', $solicitacao) }}" onsubmit="return confirm('Tem certeza que deseja desfazer esta solicitacao?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-red-600 transition hover:bg-red-50">
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
