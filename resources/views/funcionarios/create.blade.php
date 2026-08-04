<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar funcionario
            </h2>

            <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('funcionarios.store', $setor) }}" class="p-6">
                    @csrf

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $setor->name }}</h3>

                        <div class="mt-6">
                            <x-input-label for="name" value="Nome do funcionario" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="salary" value="Salario" />
                            <x-text-input id="salary" name="salary" type="number" min="0" step="0.01" class="mt-1 block w-full" :value="old('salary')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('salary')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="cargo_id" value="Cargo" />
                            <select id="cargo_id" name="cargo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Selecione um cargo</option>

                                @foreach ($cargos as $cargo)
                                    <option value="{{ $cargo->id }}" @selected(old('cargo_id') == $cargo->id)>
                                        {{ $cargo->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('cargo_id')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>

                        <x-primary-button>
                            Criar funcionario
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
