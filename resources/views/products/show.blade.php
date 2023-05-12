<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="card">
                    <div class="card-header">{{ $product->name }}</div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $product->description }}</h5>
                        <p class="card-text">
                            <strong>Preço: </strong> R$ {{ number_format($product->price, 2, ',', '.') }}<br>
                            <strong>Unidade de Medida: </strong> {{ $product->measure }}<br>
                            <strong>Quantidade: </strong> {{ $product->quantity }}<br>
                            <strong>Inventário: </strong> {{ $product->inventory->name }}<br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
