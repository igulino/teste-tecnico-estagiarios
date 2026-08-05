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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard do Admin de Setor
        </h2>
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

            <div class="admin-setor-panels mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Funcionarios do setor</h3>

                        @if ($funcionarios->isNotEmpty())
                            <div class="mt-4 overflow-hidden rounded-md border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                   
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

                                                    <form x-show="aberto" x-cloak method="POST" action="{{ route('solicitacoes.transferencia.store') }}" class="mt-3 flex flex-col gap-2">
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
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $funcionario->cargo?->name ?? 'Sem cargo vinculado' }}</td>
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

                        <div class="mt-4 rounded-md border border-gray-200 p-4">
                            <div class="text-sm font-medium text-gray-500">Pendentes neste setor</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $solicitacoesPendentes }}</div>
                        </div>

                        <p class="mt-4 text-sm text-gray-500">
                            Espaco reservado para listar as solicitacoes criadas ou acompanhadas por este setor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
