function changeQty(productID, delta) {
    const input = document.getElementById(`product_${productID}_qty`);
    let value = parseInt(input.value) || 0;
    const availableUnits = parseInt(document.getElementById(`available_${productID}`).textContent.split(' ')[0]) || 0;
    value = Math.max(0, value + delta);
    if (value > availableUnits) {
        value = availableUnits;
    }
    input.value = value;
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        const price = parseFloat(input.getAttribute('data-price'));
        const qty = parseInt(input.value) || 0;
        total += qty * price;
    });
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', function() {
        calculateTotal();
    });
});

window.onload = calculateTotal;
