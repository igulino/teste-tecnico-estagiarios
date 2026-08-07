<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar cargo
            </h2>

            <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('cargos.store') }}" class="p-6">
                    @csrf

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Dados do cargo</h3>

                        <div class="mt-6">
                            <x-input-label for="name" value="Nome do cargo" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" value="Descricao" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="hierarchy" value="Nivel hierarquico" />
                            <x-text-input id="hierarchy" name="hierarchy" type="number" min="1" step="1" class="mt-1 block w-full" :value="old('hierarchy')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('hierarchy')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>

                        <x-primary-button>
                            Criar cargo
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
