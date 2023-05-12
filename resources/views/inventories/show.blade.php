<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes do Inventário: {{ $inventory->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold">Produtos no Inventário</h3>
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Nome</th>
                                <th class="border px-4 py-2">Descrição</th>
                                <th class="border px-4 py-2">Preço</th>
                                <th class="border px-4 py-2">Unidade de Medida</th>
                                <th class="border px-4 py-2">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="border px-4 py-2">{{ $product->name }}</td>
                                    <td class="border px-4 py-2">{{ $product->description }}</td>
                                    <td class="border px-4 py-2">R$ {{ number_format($product->price/100, 2, ',', '.') }}</td>
                                    <td class="border px-4 py-2">{{ $product->measure }}</td>
                                    <td class="border px-4 py-2">{{ $product->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
