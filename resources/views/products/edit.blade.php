<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Produto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form class="form-horizontal" method="POST" action="{{ route('products.update', $product->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $product->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Descrição</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" value="{{ $product->description }}">

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                            <label for="price" class="col-md-4 control-label">Preço</label>
                            <div class="col-md-6">
                                <input id="price" type="text" class="form-control" name="price" value="{{ old('price', number_format($product->price, 2, ',', '')) }}" required>
                                @if ($errors->has('price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="unit_of_measure" class="col-md-4 control-label">Unidade de Medida</label>
                            <div class="col-md-6">
                                <select id="unit_of_measure" name="unit_of_measure" class="form-control">
                                    <option value="unidade" {{ $product->measure == 'unidade' ? 'selected' : '' }}>Unidade</option>
                                    <option value="kg" {{ $product->measure == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                                    <option value="g" {{ $product->measure == 'g' ? 'selected' : '' }}>Grama (g)</option>
                                    <option value="l" {{ $product->measure == 'l' ? 'selected' : '' }}>Litro (l)</option>
                                    <option value="ml" {{ $product->measure == 'ml' ? 'selected' : '' }}>Metro (l)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                        <label for="quantity" class="col-md-4 control-label">Quantidade</label>
                        <div class="col-md-6">
                            <input id="quantity" type="number" class="form-control" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @if ($errors->has('quantity'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('quantity') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('inventories_id') ? ' has-error' : '' }}">
                        <label for="inventories_id" class="col-md-4 control-label">Inventário</label>
                        <div class="col-md-6">
                            <select name="inventories_id" id="inventories_id" class="form-control">
                                <option value="">Selecione um inventário</option>
                                @foreach($inventories as $inventory)
                                    <option value="{{ $inventory->id }}" {{ $product->inventories_id == $inventory->id ? 'selected' : '' }}>{{ $inventory->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('inventories_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('inventories_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', (event) => {
        let value = event.target.value;
        // Remove todos os caracteres que não são dígitos
        value = value.replace(/\D/g, '');
        // Remove os zeros à esquerda
        value = value.replace(/^0+/, '');
        // Adiciona os zeros à esquerda novamente, se necessário
        if (value.length < 3) {
            value = value.padStart(3, '0');
        }
        // Insere a vírgula no formato correto
        const parts = value.match(/^(\d+)(\d{2})$/);
        if (parts) {
            value = `${parts[1]},${parts[2]}`;
        }
        // Atualiza o valor do input
        event.target.value = value;
    });
</script>
</x-app-layout>

