<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contactos
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <form 
            action="{{ route('contacts.update', $contact) }}"
            method="POST"
            class="bg-white rounded-lg shadow p-6"
        >
            @csrf
            @method('PUT')
            <x-validation-errors class="mb-4"/>

            <div class="mb-4">
                <x-label class="mb-1">
                    Nombre de Contacto
                </x-label>
                
                <x-input
                    type='text'
                    name='name'
                    value="{{ old('name', $contact->name) }}"
                    placeholder='Ingresa el nombre del contacto'
                    class="w-full"
                />
            </div>

            <div class="mb-4">
                <x-label class="mb-1">
                    Email
                </x-label>
                
                <x-input
                    type='email'
                    name='email'
                    value="{{ old('email', $contact->user->email) }}"
                    placeholder='Ingresa un correo'
                    class="w-full"
                />
            </div>

            <div class="flex justify-end">
                <x-button
                    type='submit'
                >
                    Actualizar Contacto
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>