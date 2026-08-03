<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar setor
            </h2>

            <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('setores.store') }}" class="p-6">
                    @csrf

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Dados do setor</h3>

                        <div class="mt-6">
                            <x-input-label for="name" value="Nome do setor" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" value="Descricao" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900">Admin do setor</h3>

                        <div class="mt-6">
                            <x-input-label for="admin_nome" value="Nome do admin" />
                            <x-text-input id="admin_nome" name="admin_nome" type="text" class="mt-1 block w-full" :value="old('admin_nome')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('admin_nome')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="admin_email" value="Email do admin" />
                            <x-text-input id="admin_email" name="admin_email" type="email" class="mt-1 block w-full" :value="old('admin_email')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('admin_email')" />
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="admin_password" value="Senha do admin" />
                                <x-text-input id="admin_password" name="admin_password" type="password" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('admin_password')" />
                            </div>

                            <div>
                                <x-input-label for="admin_password_confirmation" value="Confirmar senha" />
                                <x-text-input id="admin_password_confirmation" name="admin_password_confirmation" type="password" class="mt-1 block w-full" required />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('dashboard.super-admin') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>

                        <x-primary-button>
                            Criar setor
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
