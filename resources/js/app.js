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