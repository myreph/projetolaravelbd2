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
                            <label for="subtotal" class="col-md-4 control-label">Subtotal</label>
                            <div class="col-md-6">
                                <input type="text" id="subtotal" class="form-control" readonly value="R$ 0,00">
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
    const subtotalInput = document.getElementById('subtotal');
    const saleForm = document.getElementById('sale-form');

    let selectedProducts = [];
    let subtotal = 0;

    function updateSubtotal() {
        subtotal = selectedProducts.reduce((acc, product) => acc + (product.price * product.quantity), 0);
        subtotalInput.value = `R$ ${subtotal.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')}`;
    }

    function addProduct(product, quantity) {
        const productIndex = selectedProducts.findIndex(p => p.id === product.id);

        if (productIndex === -1) {
            selectedProducts.push({ ...product, quantity });
        } else {
            selectedProducts[productIndex].quantity += quantity;
        }

        renderSelectedProducts();
        updateSubtotal();
    }

    function removeProduct(productId) {
        selectedProducts = selectedProducts.filter(product => product.id !== productId);
        renderSelectedProducts();
        updateSubtotal();
    }

    function renderSelectedProducts() {
        selectedProductsDiv.innerHTML = '';

        selectedProducts.forEach(product => {
            const productDiv = document.createElement('div');
            productDiv.classList.add('mb-2');

            const label = document.createElement('label');
            label.textContent = `${product.name} (R$ ${product.price.toFixed(2).replace('.', ',')}) - Quantidade:`;

            productDiv.appendChild(label);

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.min = 1;
            quantityInput.value = product.quantity;
            quantityInput.style.width = '50px';
            quantityInput.addEventListener('change', () => {
                product.quantity = parseInt(quantityInput.value);
                updateSubtotal();
            });

            productDiv.appendChild(quantityInput);

            const removeButton = document.createElement('button');
            removeButton.textContent = 'Remover';
            removeButton.classList.add('btn', 'btn-danger', 'ml-2');
            removeButton.addEventListener('click', () => {
                removeProduct(product.id);
            });

            productDiv.appendChild(removeButton);

            selectedProductsDiv.appendChild(productDiv);
        });
    }

    saleForm.addEventListener('submit', (event) => {
        if (selectedProducts.length === 0) {
            event.preventDefault();
            alert('Por favor, adicione ao menos um produto à venda.');
        }
    });

    const searchInput = document.getElementById('search');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', (event) => {
        const query = event.target.value;

        if (query.length >= 3) {
            fetch(`/products/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';

                    data.data.forEach(product => {
                        const result = document.createElement('div');
                        result.classList.add('search-result');
                        result.textContent = product.name;
                        result.dataset.productId = product.id;

                        // Adicione o campo de entrada da quantidade
                        const quantityInput = document.createElement('input');
                        quantityInput.type = 'number';
                        quantityInput.min = 1;
                        quantityInput.value = 1;
                        quantityInput.style.width = '50px';
                        quantityInput.style.marginLeft = '5px';
                        quantityInput.addEventListener('change', (event) => {
                            updateQuantityInput(result, parseInt(event.target.value));
                        });
                        result.appendChild(quantityInput);

                        result.addEventListener('mousedown', (event) => {
    event.preventDefault(); // previne que a página recarregue
    const quantityInput = result.querySelector('input[type=number]');
    const quantity = parseInt(quantityInput.value);
    addProduct(product, quantity); // Use a quantidade fornecida
    searchResults.innerHTML = '';
    searchInput.value = '';
});

function updateQuantityInput(result, quantity) {
    const productId = result.dataset.productId;
    const productIndex = selectedProducts.findIndex(p => p.id === productId);
    if (productIndex !== -1) {
        selectedProducts[productIndex].quantity = quantity;
        renderSelectedProducts();
        updateSubtotal();
    }
}

function addProduct(product, quantity) {
    const productIndex = selectedProducts.findIndex(p => p.id === product.id);

    if (productIndex === -1) {
        selectedProducts.push({ ...product, quantity });
    } else {
        selectedProducts[productIndex].quantity += quantity;
    }

    renderSelectedProducts();
    updateSubtotal();
}

function removeProduct(productId) {
    selectedProducts = selectedProducts.filter(product => product.id !== productId);
    renderSelectedProducts();
    updateSubtotal();
}

function renderSelectedProducts() {
    selectedProductsDiv.innerHTML = '';

    selectedProducts.forEach(product => {
        const productDiv = document.createElement('div');
        productDiv.classList.add('mb-2');

        const label = document.createElement('label');
        label.textContent = `${product.name} (R$ ${product.price.toFixed(2).replace('.', ',')}) - Quantidade:`;

        productDiv.appendChild(label);

        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.min = 1;
        quantityInput.value = product.quantity;
        quantityInput.style.width = '50px';
        quantityInput.addEventListener('change', () => {
            updateQuantityInput(result, parseInt(quantityInput.value));
        });

        productDiv.appendChild(quantityInput);

        const removeButton = document.createElement('button');
        removeButton.textContent = 'Remover';
        removeButton.classList.add('btn', 'btn-danger', 'ml-2');
        removeButton.addEventListener('click', () => {
            removeProduct(product.id);
        });

        productDiv.appendChild(removeButton);

        selectedProductsDiv.appendChild(productDiv);
    });
}
</script>
</x-app-layout>

