<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Consulta de Estoques
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">Estoque</h3>
                    <a href="{{ route('inventories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Novo estoque
                    </a>
                </div>
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Nome</th>
                                <th class="border px-4 py-2">Descrição</th>
                                <th class="border px-4 py-2">Editar</th>
                                <th class="border px-4 py-2">Deletar</th>
                                <th class="border px-4 py-2">Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventories as $inventory)
                                <tr>
                                    <td class="border px-4 py-2">{{ $inventory->id }}</td>
                                    <td class="border px-4 py-2">{{ $inventory->name }}</td>
                                    <td class="border px-4 py-2">{{ $inventory->description }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('inventories.edit', $inventory->id) }}" class="text-blue-500">Editar</a>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('inventories.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este inventário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500">Deletar</button>
                                        </form>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('inventories.show', $inventory->id) }}" class="text-blue-500">Detalhes</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
