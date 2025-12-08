document.addEventListener('DOMContentLoaded', function () {
    
    const cartData = document.getElementById('cart-data');
    if (!cartData) return; 

    const baseTotal = parseFloat(cartData.dataset.baseTotal);
    const updateUrl = cartData.dataset.updateUrl;
    const csrfToken = cartData.dataset.csrf;

    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const displayDays = document.getElementById('display_days');
    const displayTotal = document.getElementById('display_total');

    function calculateTotal() {
        if (!startDateInput.value || !endDateInput.value) return;

        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        if (end >= start) {
            const diffTime = Math.abs(end - start);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            
            if (diffDays === 0) diffDays = 1;

            displayDays.innerText = diffDays + " Day(s)";
            const grandTotal = baseTotal * diffDays;
            
            displayTotal.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(grandTotal);
        } else {
            displayDays.innerText = "Invalid Dates";
            displayTotal.innerText = "-";
        }
    }

    if(startDateInput && endDateInput) {
        startDateInput.addEventListener('change', calculateTotal);
        endDateInput.addEventListener('change', calculateTotal);
    }

    document.querySelectorAll('.update-cart').forEach(function(element) {
        element.addEventListener('change', function (e) {
            e.preventDefault();
            
            fetch(updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id: this.getAttribute('data-id'),
                    quantity: this.value
                })
            }).then(response => {
                window.location.reload();
            });
        });
    });
});