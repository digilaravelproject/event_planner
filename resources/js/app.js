import './bootstrap';

function initialisePasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach(button => {
        if (button.dataset.bound === 'true') return;
        const input = document.getElementById(button.dataset.passwordTarget);
        if (!input) return;
        button.dataset.bound = 'true';
        button.addEventListener('click', () => {
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.setAttribute('aria-pressed', String(!visible));
            button.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
            button.querySelectorAll('[data-password-visible]').forEach(icon => {
                icon.classList.toggle('hidden', icon.dataset.passwordVisible !== String(!visible));
            });
            input.focus({ preventScroll: true });
        });
    });
}

/**
 * Lightweight, dependency-free data-table controls for every admin list.
 * Server-side filters/pagination remain authoritative; these controls make the
 * currently loaded result set immediately searchable, sortable and easier to scan.
 */
function initialiseAdminDataTables() {
    document.querySelectorAll('.admin-content table:not([data-table="off"])').forEach(table => {
        if (table.dataset.enhanced === 'true' || !table.tBodies.length) return;

        const body = table.tBodies[0];
        const rows = Array.from(body.rows);
        const isEmptyState = rows.length === 1 && rows[0].cells.length === 1;
        if (isEmptyState) return;

        table.dataset.enhanced = 'true';
        table.classList.add('admin-data-table');

        const shell = table.closest('.overflow-x-auto') || table.parentElement;
        const toolbar = document.createElement('div');
        toolbar.className = 'admin-table-toolbar';
        toolbar.innerHTML = `
            <label class="admin-table-search">
                <span class="sr-only">Search this table</span>
                <svg aria-hidden="true" viewBox="0 0 24 24"><path d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/></svg>
                <input type="search" placeholder="Search table…" autocomplete="off">
            </label>
            <div class="admin-table-tools">
                <label>Show <select aria-label="Rows per page"><option>10</option><option selected>25</option><option>50</option><option value="all">All</option></select></label>
                <span class="admin-table-info" aria-live="polite"></span>
                <div class="admin-table-pager"><button type="button" data-page="previous" aria-label="Previous page">‹</button><button type="button" data-page="next" aria-label="Next page">›</button></div>
            </div>`;
        shell.parentElement.insertBefore(toolbar, shell);

        const noResults = document.createElement('div');
        noResults.className = 'admin-table-empty';
        noResults.textContent = 'No matching records found.';
        noResults.hidden = true;
        shell.insertAdjacentElement('afterend', noResults);

        const search = toolbar.querySelector('input');
        const length = toolbar.querySelector('select');
        const info = toolbar.querySelector('.admin-table-info');
        const previous = toolbar.querySelector('[data-page="previous"]');
        const next = toolbar.querySelector('[data-page="next"]');
        let page = 1;
        let sortColumn = null;
        let sortDirection = 1;

        const normalise = value => value.trim().toLocaleLowerCase();
        const visibleRows = () => {
            const term = normalise(search.value);
            return rows.filter(row => !term || normalise(row.innerText).includes(term));
        };

        const render = () => {
            let filtered = visibleRows();
            if (sortColumn !== null) {
                filtered = filtered.slice().sort((a, b) => {
                    const aValue = normalise(a.cells[sortColumn]?.innerText || '');
                    const bValue = normalise(b.cells[sortColumn]?.innerText || '');
                    return aValue.localeCompare(bValue, undefined, { numeric: true }) * sortDirection;
                });
                filtered.forEach(row => body.appendChild(row));
            }

            const perPage = length.value === 'all' ? Math.max(filtered.length, 1) : Number(length.value);
            const pages = Math.max(1, Math.ceil(filtered.length / perPage));
            page = Math.min(page, pages);
            const start = (page - 1) * perPage;
            const end = Math.min(start + perPage, filtered.length);
            const shown = new Set(filtered.slice(start, end));
            rows.forEach(row => { row.hidden = !shown.has(row); });

            noResults.hidden = filtered.length !== 0;
            info.textContent = filtered.length ? `${start + 1}–${end} of ${filtered.length}` : '0 records';
            previous.disabled = page === 1;
            next.disabled = page === pages;
        };

        Array.from(table.tHead?.rows[0]?.cells || []).forEach((header, column, headers) => {
            if (column === headers.length - 1 || header.dataset.sortable === 'false') return;
            header.classList.add('admin-sortable');
            header.tabIndex = 0;
            header.setAttribute('role', 'button');
            header.setAttribute('aria-sort', 'none');
            const sort = () => {
                const direction = sortColumn === column ? sortDirection * -1 : 1;
                table.querySelectorAll('th[aria-sort]').forEach(th => th.setAttribute('aria-sort', 'none'));
                sortColumn = column;
                sortDirection = direction;
                header.setAttribute('aria-sort', direction === 1 ? 'ascending' : 'descending');
                render();
            };
            header.addEventListener('click', sort);
            header.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); sort(); }
            });
        });

        search.addEventListener('input', () => { page = 1; render(); });
        length.addEventListener('change', () => { page = 1; render(); });
        previous.addEventListener('click', () => { page -= 1; render(); });
        next.addEventListener('click', () => { page += 1; render(); });
        render();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initialiseAdminDataTables();
        initialisePasswordToggles();
    });
} else {
    initialiseAdminDataTables();
    initialisePasswordToggles();
}

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
