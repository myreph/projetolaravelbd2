<x-app-layout>
    <style>
        .search-result {
            padding: 0.5rem;
            cursor: pointer;
        }

        .search-result:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Realizar Venda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="sale-form" method="POST" action="{{ route('sales.store') }}">
                        @csrf

                        <div class="relative">
                            <input type="text" id="search" class="form-control" placeholder="Buscar produto...">
                            <div id="search-results" class="absolute z-10 mt-1 bg-white w-full border border-gray-300 rounded-md shadow-lg" style="max-width: 100%;"></div>
                        </div>

                        <div id="selected-products" class="my-4"></div>

                        <div class="form-group">
                            <label for="final_value" class="col-md-4 control-label">Final Value</label>
                            <div class="col-md-6">
                                <input type="text" id="final_value" class="form-control" readonly value="R$ 0,00">
                            </div>
                        </div>

                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Realizar Venda
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    const products = @json($products);
    const selectedProductsDiv = document.getElementById('selected-products');
    const finalValueInput = document.getElementById('final_value'); // Atualizado para 'final_value'
    const saleForm = document.getElementById('sale-form');

    let selectedProducts = [];
    let finalValue = 0; // Atualizado para 'finalValue'

    function updateFinalValue() { // Atualizado para 'updateFinalValue'
        finalValue = selectedProducts.reduce((acc, product) => acc + (parseFloat(product.price) * product.quantity), 0); // Atualizado para 'finalValue'
        finalValueInput.value = `R$ ${finalValue.toFixed(2).replace('.', ',')}`; // Atualizado para 'finalValueInput'
    }

    function addProduct(product) {
        const productIndex = selectedProducts.findIndex(p => p.id === product.id);

        if (productIndex === -1) {
            selectedProducts.push({ ...product, quantity: 1 });
        } else {
            selectedProducts[productIndex].quantity += 1;
        }

        renderSelectedProducts();
        updateFinalValue();
        console.log(selectedProducts);

    }

    function removeProduct(productId) {
        selectedProducts = selectedProducts.filter(product => product.id !== productId);
        renderSelectedProducts();
        updateFinalValue();
    }

    function renderSelectedProducts() {
        selectedProductsDiv.innerHTML = '';

        selectedProducts.forEach(product => {
            const productDiv = document.createElement('div');
            productDiv.classList.add('mb-2');

            const label = document.createElement('label');
            const price = parseFloat(product.price);
            label.textContent = `${product.name} (R$ ${price.toFixed(2).replace('.', ',')}) - Quantidade:`;

            productDiv.appendChild(label);

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.min = 1;
            quantityInput.value = product.quantity;
            quantityInput.style.width = '50px';
            quantityInput.addEventListener('change', () => {
                product.quantity = parseInt(quantityInput.value);
                updateFinalValue();
            });

            productDiv.appendChild(quantityInput);

            const removeButton = document.createElement('button');
            removeButton.textContent = 'Remover';
            removeButton.classList.add('btn', 'btn-danger', 'ml-2');
            removeButton.addEventListener('click', () => {
                removeProduct(product.id);
            });

            productDiv.appendChild(removeButton);

            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'products[]';
            productInput.value = JSON.stringify(product);
            productDiv.appendChild(productInput);

            selectedProductsDiv.appendChild(productDiv);
        });
    }

    saleForm.addEventListener('submit', (event) => {
        event.preventDefault(); // previne que a página recarregue
        console.log('submit event triggered');
        
        if (selectedProducts.length === 0) {
            alert('Por favor, adicione ao menos um produto à venda.');
            return;
        }

        let formData = new FormData();
        selectedProducts.forEach(product => {
            formData.append('products[]', JSON.stringify(product));
        });

        fetch(saleForm.action, {
            method: saleForm.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error(error));
    });

    const searchInput = document.getElementById('search');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', (event) => {
        const query = event.target.value;

        if (query.length >= 3) {
    fetch(`/products/search?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then(data => {
            searchResults.innerHTML = '';

            data.data.forEach(product => {
                const result = document.createElement('div');
                result.classList.add('search-result');
                result.textContent = product.name;
                result.dataset.productId = product.id;

                result.addEventListener('mousedown', (event) => {
                    event.preventDefault(); // previne que a página recarregue
                    addProduct(product); // Adicione o produto com a quantidade padrão 1
                    searchResults.innerHTML = '';
                    searchInput.value = '';
                });

                searchResults.appendChild(result);
            });
        })
        .catch(function() {
            console.log("A requisição falhou.");
        });
} else {
    searchResults.innerHTML = '';
}
});

</script>
</x-app-layout>
        
