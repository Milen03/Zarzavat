document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const availableStock = document.getElementById('available-stock');
        const unitPrice = document.getElementById('unit-price');
        const totalPrice = document.getElementById('total-price');
        
        function updateProductInfo() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            
            if (selectedOption.value) {
                const price = parseFloat(selectedOption.dataset.price);
                const stock = parseInt(selectedOption.dataset.stock);
                
                availableStock.textContent = stock;
                unitPrice.textContent = price.toFixed(2) + ' лв.';
                
                // Ограничаваме количеството до наличността
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
                
                updateTotal();
            } else {
                availableStock.textContent = '-';
                unitPrice.textContent = '0.00 лв.';
                totalPrice.textContent = '0.00 лв.';
            }
        }
        
        function updateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            
            if (selectedOption.value) {
                const price = parseFloat(selectedOption.dataset.price);
                const quantity = parseInt(quantityInput.value);
                
                const total = price * quantity;
                totalPrice.textContent = total.toFixed(2) + ' лв.';
            }
        }
        
        productSelect.addEventListener('change', updateProductInfo);
        quantityInput.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
        
        // Инициализация
        updateProductInfo();
    });