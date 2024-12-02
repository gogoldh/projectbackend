
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterModal = document.getElementById('filterModal');
    const closeModal = document.querySelector('.close');
    const filterForm = document.getElementById('filterForm');
    const resetFiltersButton = document.getElementById('resetFilters');
    const cards = document.querySelectorAll('.card');
    filterButton.addEventListener('click', function() {
        filterModal.style.display = 'block';
    });
    closeModal.addEventListener('click', function() {
        filterModal.style.display = 'none';
    });
    window.addEventListener('click', function(event) {
        if (event.target == filterModal) {
            filterModal.style.display = 'none';
        }
    });
    filterForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const brand = document.getElementById('brand').value.toLowerCase().replace(' ', '-');
        const price = document.getElementById('price').value;
        const topSpeed = document.getElementById('top_speed').value;
        const weight = document.getElementById('weight').value;
        const motorPower = document.getElementById('motor_power').value;
        const rangePerCharge = document.getElementById('range_per_charge').value;
        const wheelSize = document.getElementById('wheel_size').value;
        cards.forEach(card => {
            const cardBrand = card.classList.contains(`brand-${brand}`);
            const cardPrice = parseFloat(card.querySelector('.color h3').textContent.replace('Price: $', ''));
            const cardTopSpeed = parseFloat(card.dataset.topSpeed);
            const cardWeight = parseFloat(card.dataset.weight);
            const cardMotorPower = parseFloat(card.dataset.motorPower);
            const cardRangePerCharge = parseFloat(card.dataset.rangePerCharge);
            const cardWheelSize = parseFloat(card.dataset.wheelSize);
            if (
                (brand === '' || cardBrand) &&
                (price === '' || cardPrice <= price) &&
                (topSpeed === '' || cardTopSpeed >= topSpeed) &&
                (weight === '' || cardWeight <= weight) &&
                (motorPower === '' || cardMotorPower >= motorPower) &&
                (rangePerCharge === '' || cardRangePerCharge >= rangePerCharge) &&
                (wheelSize === '' || cardWheelSize >= wheelSize)
            ) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        filterModal.style.display = 'none';
    });
    resetFiltersButton.addEventListener('click', function() {
        document.getElementById('brand').value = '';
        document.getElementById('price').value = 5000;
        document.getElementById('price').nextElementSibling.value = 5000;
        document.getElementById('top_speed').value = 0;
        document.getElementById('top_speed').nextElementSibling.value = 0;
        document.getElementById('weight').value = 100;
        document.getElementById('weight').nextElementSibling.value = 100;
        document.getElementById('motor_power').value = 0;
        document.getElementById('motor_power').nextElementSibling.value = 0;
        document.getElementById('range_per_charge').value = 0;
        document.getElementById('range_per_charge').nextElementSibling.value = 0;
        document.getElementById('wheel_size').value = 0;
        document.getElementById('wheel_size').nextElementSibling.value = 0;
        cards.forEach(card => {
            card.style.display = 'block';
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const compareBtns = document.querySelectorAll('.compare-btn');
    const comparePopup = document.getElementById('comparePopup');
    const selectedProductsList = document.getElementById('selectedProductsList');
    const compareNowBtn = document.getElementById('compareNowBtn');
    const closePopupBtn = document.getElementById('closePopupBtn');
    const comparePopupButton = document.getElementById('comparePopupButton');

    // Array to store selected product details
    let selectedProducts = JSON.parse(localStorage.getItem('selectedProducts')) || [];

    // Function to update the popup
    function updatePopup() {
        selectedProductsList.innerHTML = '';
        selectedProducts.forEach(product => {
            const productItem = document.createElement('li');
            productItem.textContent = product.name;
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'Remove';
            removeBtn.className = 'btn-remove';
            removeBtn.addEventListener('click', () => removeProduct(product.id));
            productItem.appendChild(removeBtn);
            selectedProductsList.appendChild(productItem);
        });
        compareNowBtn.disabled = selectedProducts.length !== 2;
        comparePopupButton.textContent = `Compare (${selectedProducts.length})`; // Update floating button text
    }

    // Function to add a product to the selection
    function addProduct(productId) {
        const productCard = document.querySelector(`.card-${productId}`);
        const productName = productCard.getAttribute('data-product-title');
        if (selectedProducts.length < 2 && !selectedProducts.some(product => product.id === productId)) {
            selectedProducts.push({ id: productId, name: productName });
            localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));
            updatePopup();
        }
        if (selectedProducts.length === 2) {
            // alert('You can now compare products.');

            // Maak hier een pop up voor de gebruiker
        }
    }

    // Function to remove a product from the selection
    function removeProduct(productId) {
        selectedProducts = selectedProducts.filter(product => product.id !== productId);
        localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));
        updatePopup();
    }

    // Function to show the popup
    function showPopup() {
        comparePopup.classList.remove('hidden');
        updatePopup();
    }

    // Function to hide the popup
    function hidePopup() {
        comparePopup.classList.add('hidden');
    }

    // Event listener for compare buttons
    compareBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addProduct(productId);
            showPopup();
        });
    });

    // Event listener for Compare Now button
    compareNowBtn.addEventListener('click', function () {
        if (selectedProducts.length === 2) {
            const compareUrl = `compare.php?product1=${selectedProducts[0].id}&product2=${selectedProducts[1].id}`;
            window.location.href = compareUrl;
        } else {
            // alert('Please select 2 products to compare.');
            // Schrijf hier nog een pop up voor de gebruiker


        }
    });

    // Event listener for Compare Popup button
    comparePopupButton.addEventListener('click', function () {
        showPopup();
    });

    // Event listener for Close Popup button
    closePopupBtn.addEventListener('click', hidePopup);

    // Close popup if clicked outside the content
    window.addEventListener('click', function (event) {
        if (event.target === comparePopup) {
            hidePopup();
        }
    });

    // Initialize the popup with any existing selected products
    if (selectedProducts.length > 0) {
        updatePopup();
    }

    
});


