import './bootstrap';

const loginDialog = document.getElementById('login-choice');
if (loginDialog) {
    document.querySelectorAll('[data-login-choice]').forEach(trigger => {
        trigger.addEventListener('click', event => {
            if (typeof loginDialog.showModal !== 'function') return;
            event.preventDefault();
            if (loginDialog.open) return;
            loginDialog.showModal();
        });
    });
    loginDialog.addEventListener('click', event => {
        const bounds = loginDialog.getBoundingClientRect();
        if (event.target === loginDialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) loginDialog.close();
    });
    loginDialog.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            event.preventDefault();
            loginDialog.close();
        }
    });
}
