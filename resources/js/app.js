import './bootstrap';
import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    if (!passwordInput || !togglePassword) {
        return;
    }

    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        togglePassword.textContent = isPassword ? 'Ocultar' : 'Mostrar';
        togglePassword.setAttribute(
            'aria-label',
            isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
        );
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    if (!sidebar || !sidebarToggle || !sidebarBackdrop) {
        return;
    }

    const closeSidebar = () => {
        sidebar.classList.remove('is-open');
        sidebarBackdrop.classList.remove('is-visible');
    };

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('is-open');
        sidebarBackdrop.classList.toggle('is-visible');
    });

    sidebarBackdrop.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const description = document.getElementById('descripcion');
    const counter = document.getElementById('descripcion-counter');

    if (!description || !counter) {
        return;
    }

    const updateCounter = () => {
        counter.textContent = `${description.value.length} / 255`;
    };

    description.addEventListener('input', updateCounter);

    updateCounter();
});

document.addEventListener('DOMContentLoaded', () => {
    const productButtons = document.querySelectorAll('.pos-product-card');
    const cartContainer = document.getElementById('pos-cart');
    const emptyCart = document.getElementById('pos-empty-cart');
    const subtotalElement = document.getElementById('pos-subtotal');
    const totalElement = document.getElementById('pos-total');
    const changeElement = document.getElementById('pos-change');
    const discountInput = document.getElementById('descuento');
    const paymentInput = document.getElementById('pago_recibido');
    const submitButton = document.getElementById('pos-submit');
    const searchInput = document.getElementById('pos-search');
    const ventaForm = document.getElementById('venta-form');

    if (
        !cartContainer ||
        !subtotalElement ||
        !totalElement ||
        !changeElement ||
        !discountInput ||
        !paymentInput ||
        !submitButton ||
        !ventaForm
    ) {
        return;
    }

    const cart = new Map();

    const money = (value) => {
        return `$${Number(value).toFixed(2)}`;
    };

    const calculateTotals = () => {
        let subtotal = 0;

        cart.forEach((item) => {
            subtotal += item.price * item.quantity;
        });

        let discount = Number(discountInput.value || 0);

        if (discount < 0) {
            discount = 0;
        }

        const total = Math.max(subtotal - discount, 0);
        const payment = Number(paymentInput.value || 0);
        const change = Math.max(payment - total, 0);

        subtotalElement.textContent = money(subtotal);
        totalElement.textContent = money(total);
        changeElement.textContent = money(change);

        const validSale =
            cart.size > 0 &&
            discount <= subtotal &&
            payment >= total;

        submitButton.disabled = !validSale;
    };

    const renderCart = () => {
        cartContainer.innerHTML = '';

        if (cart.size === 0) {
            cartContainer.appendChild(emptyCart);
            emptyCart.classList.remove('d-none');
            calculateTotals();
            return;
        }

        emptyCart.classList.add('d-none');

        let index = 0;

        cart.forEach((item) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'pos-cart-item';

            wrapper.innerHTML = `
                <div class="pos-cart-item__top">
                    <div>
                        <div class="pos-cart-item__name">
                            ${item.name}
                        </div>

                        <div class="pos-cart-item__code">
                            ${item.code}
                        </div>
                    </div>

                    <strong>
                        ${money(item.price * item.quantity)}
                    </strong>
                </div>

                <div class="pos-cart-item__controls">
                    <button
                        type="button"
                        class="btn btn-sm btn-light border"
                        data-action="decrease"
                        data-id="${item.id}"
                    >
                        <i class="bi bi-dash-lg"></i>
                    </button>

                    <input
                        type="number"
                        min="1"
                        max="${item.stock}"
                        value="${item.quantity}"
                        class="form-control form-control-sm pos-quantity"
                        data-action="quantity"
                        data-id="${item.id}"
                    >

                    <button
                        type="button"
                        class="btn btn-sm btn-light border"
                        data-action="increase"
                        data-id="${item.id}"
                    >
                        <i class="bi bi-plus-lg"></i>
                    </button>

                    <span class="small text-muted ms-2">
                        Stock: ${item.stock}
                    </span>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger ms-auto"
                        data-action="remove"
                        data-id="${item.id}"
                        title="Quitar producto"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                <input
                    type="hidden"
                    name="productos[${index}][id]"
                    value="${item.id}"
                >

                <input
                    type="hidden"
                    name="productos[${index}][cantidad]"
                    value="${item.quantity}"
                >
            `;

            cartContainer.appendChild(wrapper);

            index++;
        });

        calculateTotals();
    };

    const addProduct = (button) => {
        const id = Number(button.dataset.productId);
        const stock = Number(button.dataset.productStock);

        if (stock <= 0) {
            return;
        }

        if (cart.has(id)) {
            const item = cart.get(id);

            if (item.quantity < item.stock) {
                item.quantity++;
            }

            renderCart();
            return;
        }

        cart.set(id, {
            id,
            code: button.dataset.productCode,
            name: button.dataset.productName,
            category: button.dataset.productCategory,
            price: Number(button.dataset.productPrice),
            stock,
            quantity: 1,
        });

        renderCart();
    };

    productButtons.forEach((button) => {
        button.addEventListener('click', () => {
            addProduct(button);
        });
    });

    cartContainer.addEventListener('click', (event) => {
        const button = event.target.closest('[data-action]');

        if (!button) {
            return;
        }

        const id = Number(button.dataset.id);
        const action = button.dataset.action;
        const item = cart.get(id);

        if (!item) {
            return;
        }

        if (action === 'increase') {
            if (item.quantity < item.stock) {
                item.quantity++;
            }
        }

        if (action === 'decrease') {
            if (item.quantity > 1) {
                item.quantity--;
            } else {
                cart.delete(id);
            }
        }

        if (action === 'remove') {
            cart.delete(id);
        }

        renderCart();
    });

    cartContainer.addEventListener('change', (event) => {
        const input = event.target.closest(
            '[data-action="quantity"]'
        );

        if (!input) {
            return;
        }

        const id = Number(input.dataset.id);
        const item = cart.get(id);

        if (!item) {
            return;
        }

        let quantity = Number(input.value);

        if (!Number.isInteger(quantity) || quantity < 1) {
            quantity = 1;
        }

        if (quantity > item.stock) {
            quantity = item.stock;
        }

        item.quantity = quantity;

        renderCart();
    });

    discountInput.addEventListener('input', calculateTotals);
    paymentInput.addEventListener('input', calculateTotals);

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const term = searchInput.value
                .trim()
                .toLowerCase();

            productButtons.forEach((button) => {
                const searchable = [
                    button.dataset.productCode,
                    button.dataset.productName,
                    button.dataset.productCategory,
                ]
                    .join(' ')
                    .toLowerCase();

                button.classList.toggle(
                    'd-none',
                    !searchable.includes(term)
                );
            });
        });
    }

    ventaForm.addEventListener('submit', (event) => {
        if (cart.size === 0) {
            event.preventDefault();
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-2"
                aria-hidden="true"
            ></span>
            Procesando venta...
        `;
    });

    renderCart();
});