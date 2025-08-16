<!DOCTYPE html>
<html lang="es">
  <meta charset="utf-8">
  <title>Dashboard EVA2</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Arial,sans-serif;max-width:900px;margin:32px auto;padding:0 16px}
    .badge{display:inline-block;background:#eef;border:1px solid #99f;padding:6px 10px;border-radius:8px}
    table{width:100%;border-collapse:collapse;margin-top:16px}
    th,td{border:1px solid #ddd;padding:8px}
    th{text-align:left;background:#fafafa}
    small{color:#666}
    button{cursor:pointer}
  </style>
</head>
<body>
  <h1>EVA2 — Proyectos</h1>

  <p class="badge" id="ufBadge">UF: cargando…</p>

  <!-- Formulario Crear/Editar -->
  <form id="formProyecto" style="margin:18px 0; display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px; align-items:end;">
    <div>
      <label>Nombre</label>
      <input id="f_nombre" type="text" required style="width:100%">
    </div>
    <div>
      <label>Fecha inicio</label>
      <input id="f_fecha" type="date" required style="width:100%">
    </div>
    <div>
      <label>Estado</label>
      <select id="f_estado" required style="width:100%">
        <option>Planificado</option>
        <option>En curso</option>
        <option>Finalizado</option>
      </select>
    </div>
    <div>
      <label>Responsable</label>
      <input id="f_responsable" type="text" required style="width:100%">
    </div>
    <div>
      <label>Monto (CLP)</label>
      <input id="f_monto" type="number" min="0" step="1" required style="width:100%">
    </div>
    <div style="display:flex; gap:8px;">
      <button id="btnGuardar" type="submit">Guardar</button>
      <button id="btnCancelar" type="button" style="display:none;">Cancelar</button>
    </div>
    <input id="f_id" type="hidden">
  </form>

  <div id="errores" style="color:#b00; margin:-6px 0 12px 0;"></div>

  <table id="tabla">
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Fecha inicio</th>
        <th>Estado</th>
        <th>Responsable</th>
        <th>Monto CLP / UF</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

<script>
let UF_GLOBAL = null;

async function cargarUF(){
  const r = await fetch('/api/uf');
  const d = await r.json();
  const uf = Number(d.uf);
  UF_GLOBAL = uf;
  const fecha = new Date(d.fecha).toLocaleDateString('es-CL');
  document.getElementById('ufBadge').textContent = 'UF: ' + uf.toLocaleString('es-CL') + ' (al ' + fecha + ')';
  return uf;
}

async function cargarProyectos(uf){
  const r = await fetch('/api/proyectos');
  const data = await r.json();
  const proyectos = Array.isArray(data) ? data : (data.data ?? []);

  const tbody = document.querySelector('#tabla tbody');
  tbody.innerHTML = '';

  if (!proyectos.length) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#666;">Sin registros</td></tr>';
    return;
  }

  proyectos.forEach(p => {
    const montoCLP = Number(p.monto ?? 0);
    const montoUF  = uf ? (montoCLP / uf) : null;

    const tr = document.createElement('tr');
    tr.innerHTML =
      '<td>' + p.id + '</td>' +
      '<td>' + p.nombre + '</td>' +
      '<td>' + p.fecha_inicio + '</td>' +
      '<td>' + p.estado + '</td>' +
      '<td>' + p.responsable + '</td>' +
      '<td>$ ' + montoCLP.toLocaleString('es-CL') + '<br><small>' + (montoUF ? montoUF.toFixed(2) + ' UF' : '—') + '</small></td>' +
      '<td>' +
        '<button onclick="editar(' + p.id + ')">Editar</button> ' +
        '<button onclick="eliminar(' + p.id + ')" style="color:#b00;">Eliminar</button>' +
      '</td>';
    tbody.appendChild(tr);
  });
}

function setErrores(msgs){
  const div = document.getElementById('errores');
  if (!msgs) { div.textContent=''; return; }
  if (Array.isArray(msgs)) div.innerHTML = msgs.join('<br>');
  else if (typeof msgs === 'object') div.innerHTML = Object.values(msgs).flat().join('<br>');
  else div.textContent = msgs;
}

document.getElementById('formProyecto').addEventListener('submit', async (e)=>{
  e.preventDefault();
  setErrores(null);

  const body = {
    nombre: document.getElementById('f_nombre').value.trim(),
    fecha_inicio: document.getElementById('f_fecha').value,
    estado: document.getElementById('f_estado').value,
    responsable: document.getElementById('f_responsable').value.trim(),
    monto: Number(document.getElementById('f_monto').value)
  };

  const id = document.getElementById('f_id').value;
  const url = id ? '/api/proyectos/' + id : '/api/proyectos';
  const method = id ? 'PUT' : 'POST';

  const r = await fetch(url, {
    method,
    headers: { 'Content-Type':'application/json' },
    body: JSON.stringify(body)
  });

  if (r.status === 422) {
    const err = await r.json();
    setErrores(err.errors || 'Datos inválidos');
    return;
  }
  if (!r.ok) { setErrores('Ocurrió un error al guardar'); return; }

  cancelarEdicion();
  await cargarProyectos(UF_GLOBAL);
});

function editar(id){
  fetch('/api/proyectos/' + id)
    .then(r=>r.json())
    .then(p=>{
      document.getElementById('f_id').value = p.id;
      document.getElementById('f_nombre').value = p.nombre;
      document.getElementById('f_fecha').value = p.fecha_inicio;
      document.getElementById('f_estado').value = p.estado;
      document.getElementById('f_responsable').value = p.responsable;
      document.getElementById('f_monto').value = p.monto;
      document.getElementById('btnGuardar').textContent = 'Actualizar';
      document.getElementById('btnCancelar').style.display = 'inline-block';
    });
}

function cancelarEdicion(){
  document.getElementById('f_id').value = '';
  document.getElementById('formProyecto').reset();
  document.getElementById('btnGuardar').textContent = 'Guardar';
  document.getElementById('btnCancelar').style.display = 'none';
  setErrores(null);
}
document.getElementById('btnCancelar').addEventListener('click', cancelarEdicion);

async function eliminar(id){
  if (!confirm('¿Eliminar este proyecto?')) return;
  const r = await fetch('/api/proyectos/' + id, { method:'DELETE' });
  if (!r.ok) { alert('No se pudo eliminar'); return; }
  await cargarProyectos(UF_GLOBAL);
}

(async () => {
  const uf = await cargarUF();
  await cargarProyectos(uf);
})();
</script>
</body>
</html>
