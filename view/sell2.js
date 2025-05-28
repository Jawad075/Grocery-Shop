const prices = {
 
};

const availableStock = {
   
};

function changeQty(id, delta, availableId) {
    const input = document.getElementById(id);
    let value = parseInt(input.value) || 0;
    const available = parseInt(document.getElementById(availableId).textContent);
    if (value + delta >= 0 && value + delta <= available) {
        value += delta;
        input.value = value;
    }
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    for (let id in prices) {
        const qty = parseInt(document.getElementById(id)?.value) || 0;
        total += qty * prices[id];
    }
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

window.onload = calculateTotal;
