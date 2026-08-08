<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Solicitacoes
            </h2>

            <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div x-data="{ aberto: true }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <button type="button" x-on:click="aberto = ! aberto" class="flex w-full items-center justify-between text-left">
                            <h3 class="text-lg font-semibold text-gray-900">Pendentes</h3>

                            <span x-bind:class="aberto ? 'rotate-90' : ''" class="text-gray-500 transition-transform">
                                &rsaquo;
                            </span>
                        </button>

                        <div x-show="aberto" x-cloak>
                            @if ($solicitacoesPendentes->isNotEmpty())
                                <div class="mt-4 space-y-3">
                                @foreach ($solicitacoesPendentes as $solicitacao)
                                    <div class="rounded-md border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ ucfirst($solicitacao->tipo->value) }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    {{ $solicitacao->funcionario?->name ?? 'Funcionario removido' }}
                                                </div>
                                            </div>

                                            <span class="text-sm font-medium text-amber-600">
                                                {{ ucfirst($solicitacao->status->value) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 text-sm text-gray-500">
                                            Solicitado por {{ $solicitacao->solicitadoPor?->name ?? 'Usuario removido' }}
                                        </div>

                                        @if ($solicitacao->tipo->value === 'transferencia')
                                            <div class="mt-2 text-sm text-gray-500">
                                                {{ $solicitacao->setorOrigem?->name ?? 'Origem nao definida' }} para {{ $solicitacao->setorDestino?->name ?? 'Destino nao definido' }}
                                            </div>
                                        @elseif ($solicitacao->tipo->value === 'aumento')
                                            <div class="mt-2 text-sm text-gray-500">
                                                R$ {{ number_format((float) $solicitacao->salario_atual, 2, ',', '.') }} para R$ {{ number_format((float) $solicitacao->salario_proposto, 2, ',', '.') }}
                                            </div>
                                        @endif

                                        <div class="mt-4 flex gap-2">
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
                                    </div>
                                @endforeach
                                </div>
                            @else
                                <p class="mt-4 text-sm text-gray-500">Nenhuma solicitacao pendente.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div x-data="{ aberto: true }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <button type="button" x-on:click="aberto = ! aberto" class="flex w-full items-center justify-between text-left">
                            <h3 class="text-lg font-semibold text-gray-900">Decididas</h3>

                            <span x-bind:class="aberto ? 'rotate-90' : ''" class="text-gray-500 transition-transform">
                                &rsaquo;
                            </span>
                        </button>

                        <div x-show="aberto" x-cloak>
                            @if ($solicitacoesDecididas->isNotEmpty())
                                <div class="mt-4 space-y-3">
                                @foreach ($solicitacoesDecididas as $solicitacao)
                                    <div class="rounded-md border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ ucfirst($solicitacao->tipo->value) }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    {{ $solicitacao->funcionario?->name ?? 'Funcionario removido' }}
                                                </div>
                                            </div>

                                            <span class="text-sm font-medium {{ $solicitacao->status->value === 'aprovada' ? 'text-green-700' : 'text-red-600' }}">
                                                {{ ucfirst($solicitacao->status->value) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 text-sm text-gray-500">
                                            Solicitado por {{ $solicitacao->solicitadoPor?->name ?? 'Usuario removido' }}
                                        </div>

                                        <div class="mt-2 text-sm text-gray-500">
                                            Decidido por {{ $solicitacao->decididoPor?->name ?? 'Usuario removido' }}
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            @else
                                <p class="mt-4 text-sm text-gray-500">Nenhuma solicitacao decidida.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
