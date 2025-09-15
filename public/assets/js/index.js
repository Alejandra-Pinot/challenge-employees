const $ = (id) => document.getElementById(id);
const base = () =>
    ((document.getElementById('baseUrl')?.value || '').trim() || window.location.origin).replace(/\/$/, '');

async function api(path, options = {}) {
    const url = base() + path;
    const res = await fetch(url, {
    headers: { 'Content-Type': 'application/json', ...(options.headers||{}) },
    ...options,
    });
    let data = null;
    try { data = await res.json(); } catch { data = { raw: await res.text() } }
    return { ok: res.ok, status: res.status, data };
}

function renderRows(items) {
    const tbody = $('tbody');
    tbody.innerHTML = '';
    for (const it of items) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td style="font-family:ui-monospace, SFMono-Regular, Menlo, monospace;">${it.id}</td>
        <td>${it.name}</td>
        <td>${it.email}</td>
        <td>${it.department}</td>
        <td>${it.role}</td>
        <td><span class="pill">${it.status}</span></td>
        <td>
        <select class="row-status">
            <option value="active" ${it.status==='active'?'selected':''}>Activo</option>
            <option value="inactive" ${it.status==='inactive'?'selected':''}>Inactivo</option>
            <option value="vacation" ${it.status==='vacation'?'selected':''}>Vacaciones</option>
            <option value="terminated" ${it.status==='terminated'?'selected':''}>Terminado</option>
        </select>
        <button class="row-update">Guardar</button>
        </td>
    `;

    tr.querySelector('.row-update').addEventListener('click', async () => {
        const newStatus = tr.querySelector('.row-status').value;
        const r = await api(`/api/employees/${encodeURIComponent(it.id)}/status`, {
        method: 'PATCH',
        body: JSON.stringify({ status: newStatus })
        });
        if (r.ok) loadList(currentPage, currentLimit);
    });

    tr.querySelector('td').addEventListener('click', () => {
        $('u_id').value = it.id;
        $('g_id').value = it.id;
    });

    tbody.appendChild(tr);
    }
}

let currentPage = 1;
let currentLimit = 10;

async function loadList(page = 1, limit = 10) {
    currentPage = page; currentLimit = limit;
    const r = await api(`/api/employees?page=${page}&limit=${limit}`);
    if (!r.ok) { $('l_info').textContent = `✖ ${r.status}`; return; }
    const { total, page: p, limit: l, totalPages, items } = r.data;
    renderRows(items);
    $('l_info').textContent = `Página ${p}/${totalPages} • ${items.length} de ${total} registros`;
    $('l_page').value = p; $('l_limit').value = l;
}


$('btnLoad').addEventListener('click', () => loadList(
    Math.max(1, parseInt($('l_page').value || '1', 10)),
    Math.min(100, Math.max(1, parseInt($('l_limit').value || '10', 10)))
));
$('btnPrev').addEventListener('click', () => {
    const p = Math.max(1, currentPage - 1); loadList(p, currentLimit);
});
$('btnNext').addEventListener('click', () => {
    const p = currentPage + 1; loadList(p, currentLimit);
});


$('btnCreate').addEventListener('click', async () => {
    const payload = {
    name: $('c_name').value.trim(),
    email: $('c_email').value.trim(),
    department: $('c_department').value.trim(),
    role: $('c_role').value.trim(),
    };
    $('btnCreate').disabled = true; $('c_status').textContent = 'Enviando…';
    const r = await api('/api/employees', { method: 'POST', body: JSON.stringify(payload) });
    $('btnCreate').disabled = false; $('c_status').textContent = r.ok ? '✔ Hecho' : '✖ Error';
    $('c_out').textContent = JSON.stringify(r, null, 2);
    if (r.ok && r.data && r.data.id) {
    $('u_id').value = r.data.id;
    $('g_id').value = r.data.id;
    loadList(currentPage, currentLimit);
    }
});

$('btnUpdate').addEventListener('click', async () => {
    const id = $('u_id').value.trim();
    const status = $('u_status').value;
    if (!id) { $('u_statusLbl').textContent = 'Ingresa un ID'; return; }
    $('btnUpdate').disabled = true; $('u_statusLbl').textContent = 'Enviando…';
    const r = await api(`/api/employees/${encodeURIComponent(id)}/status`, { method: 'PATCH', body: JSON.stringify({ status }) });
    $('btnUpdate').disabled = false; $('u_statusLbl').textContent = r.ok ? '✔ Actualizado' : '✖ Error';
    $('u_out').textContent = JSON.stringify(r, null, 2);
    if (r.ok) loadList(currentPage, currentLimit);
});

const btnGet = document.getElementById('btnGet');
if (btnGet) {
    btnGet.addEventListener('click', async () => {
    const id = $('g_id').value.trim();
    if (!id) { $('g_status').textContent = 'Ingresa un ID'; return; }
    $('btnGet').disabled = true; $('g_status').textContent = 'Buscando…';
    const r = await api(`/api/employees/${encodeURIComponent(id)}`);
    $('btnGet').disabled = false; $('g_status').textContent = r.ok ? '✔ OK' : `✖ ${r.status}`;
    $('g_out').textContent = JSON.stringify(r, null, 2);
    });
}

loadList(1, 10);