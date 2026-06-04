/* ═══════════════════════════════════════════════
   FarmaPlus – scripts.js
   T5P2 – Cookies & Sesiones PHP
═══════════════════════════════════════════════ */

/* ──────────────────────────────────────────────
   UTILIDADES DE COOKIES
────────────────────────────────────────────── */

/**
 * Obtiene el valor de una cookie por nombre.
 * @param {string} nombre
 * @returns {string|null}
 */
function getCookie(nombre) {
  const clave = encodeURIComponent(nombre) + '=';
  const partes = document.cookie.split(';');
  for (let i = 0; i < partes.length; i++) {
    let parte = partes[i].trim();
    if (parte.indexOf(clave) === 0) {
      return decodeURIComponent(parte.substring(clave.length));
    }
  }
  return null;
}

/**
 * Elimina una cookie estableciendo su fecha de expiración en el pasado.
 * @param {string} nombre
 */
function deleteCookie(nombre) {
  document.cookie = encodeURIComponent(nombre) +
    '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
}

/* ──────────────────────────────────────────────
   DETECCIÓN DE PÁGINA ACTUAL
────────────────────────────────────────────── */
const pagina = window.location.pathname.split('/').pop(); // "index.php", "procesar.php", "destruir.php", etc.

/* ══════════════════════════════════════════════
   PÁGINA: index.php / index.html
   - Leer cookies y pre-llenar formulario
   - Mostrar panel de cookies activas
══════════════════════════════════════════════ */
if (pagina === 'index.php' || pagina === 'T5_P2.php' || pagina === 'index.html' || pagina === '' || pagina === '/') {

  const nombreGuardado  = getCookie('nombre_cliente');
  const entregaGuardada = getCookie('tipo_entrega');

  // ── Pre-llenar campos (si no los llenó PHP) ──────────────────────
  if (nombreGuardado) {
    const campoNombre = document.getElementById('nombre');
    if (campoNombre && !campoNombre.value) campoNombre.value = nombreGuardado;
  }
  if (entregaGuardada) {
    const selectEntrega = document.getElementById('tipo_entrega');
    if (selectEntrega && selectEntrega.selectedIndex === 0) {
      for (let opt of selectEntrega.options) {
        if (opt.value === entregaGuardada) { opt.selected = true; break; }
      }
    }
  }

  // ── Mostrar aviso cookie ───────────────────
  if (nombreGuardado || entregaGuardada) {
    const aviso = document.getElementById('aviso-cookie');
    if (aviso) {
      aviso.style.removeProperty('display');
      aviso.style.display = 'flex';
    }
    const spanNombre  = document.getElementById('cookie-nombre');
    const spanEntrega = document.getElementById('cookie-entrega');
    if (spanNombre  && nombreGuardado)  spanNombre.textContent  = nombreGuardado;
    if (spanEntrega && entregaGuardada) spanEntrega.textContent = entregaGuardada;
  }

  // ── Panel cookies activas ──────────────────
  const panelCookies = document.getElementById('panel-cookies');
  if (panelCookies) {
    if (nombreGuardado || entregaGuardada) {
      let html = '<ul class="list-unstyled mb-0" style="font-size:.88rem;">';
      if (nombreGuardado) {
        html += `
          <li class="mb-2 d-flex align-items-start gap-2">
            <i class="bi bi-check-circle-fill text-success mt-1"></i>
            <div>
              <span class="text-muted">nombre_cliente</span><br>
              <strong>${nombreGuardado}</strong>
            </div>
          </li>`;
      }
      if (entregaGuardada) {
        html += `
          <li class="d-flex align-items-start gap-2">
            <i class="bi bi-check-circle-fill text-success mt-1"></i>
            <div>
              <span class="text-muted">tipo_entrega</span><br>
              <strong>${entregaGuardada}</strong>
            </div>
          </li>`;
      }
      html += '</ul>';
      panelCookies.innerHTML = html;
    }
  }

  // ── Fecha mínima en campo fecha ────────────
  const campoFecha = document.getElementById('fecha_retiro');
  if (campoFecha) {
    campoFecha.min = new Date().toISOString().split('T')[0];
  }

  // ── Validación de inputs (sin letra 'e' ni caracteres especiales) ──
  const cantidadInput = document.getElementById('cantidad');
  if (cantidadInput) {
    cantidadInput.addEventListener('keydown', function(e) {
      if (['e', 'E', '+', '-', '.', ','].includes(e.key)) {
        e.preventDefault();
      }
    });
    cantidadInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  }

  const telefonoInput = document.getElementById('telefono');
  if (telefonoInput) {
    telefonoInput.addEventListener('keydown', function(e) {
      if (['e', 'E', '+', '-', '.', ','].includes(e.key)) {
        e.preventDefault();
      }
    });
    telefonoInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
      if (this.value.length > 0 && this.value.length !== 8) {
        this.setCustomValidity('el formato de numero debe tener 8 digitos');
      } else {
        this.setCustomValidity('');
      }
    });
  }
}



/* ══════════════════════════════════════════════
   PÁGINA: destruir.html / destruir.php
   - Eliminar cookies del lado cliente (JS)
   - Contar cookies eliminadas
══════════════════════════════════════════════ */
if (pagina === 'destruir.html' || pagina === 'destruir.php') {

  const cookiesObjetivo = ['nombre_cliente', 'tipo_entrega'];
  const eliminadas = [];

  cookiesObjetivo.forEach(c => {
    if (getCookie(c) !== null) {
      deleteCookie(c);
      eliminadas.push(c);
    }
  });

  // ── Mostrar resultado de cookies ──────────
  const contenedorCookies = document.getElementById('cookies-eliminadas');
  if (contenedorCookies) {
    if (eliminadas.length > 0) {
      let html = '';
      eliminadas.forEach(c => {
        html += `<div class="status-item">
          <i class="bi bi-check-circle-fill status-icon-ok"></i>
          <span>Cookie <strong>${c}</strong> eliminada (cliente)</span>
        </div>`;
      });
      contenedorCookies.innerHTML = html;
    } else {
      contenedorCookies.innerHTML = `
        <div class="status-item">
          <i class="bi bi-info-circle-fill status-icon-info"></i>
          <span>No había cookies activas en el navegador</span>
        </div>`;
    }
  }

}

/* ──────────────────────────────────────────────
   AJUSTE DINÁMICO DE RETORNO (PORTAL VS STANDALONE)
   ────────────────────────────────────────────── */
const referer = document.referrer;
const btnVolver = document.querySelector('.btn-volver');
if (btnVolver && referer.includes('T5_P2.php')) {
  if (pagina === 'confirmacion.html' || pagina === 'procesar.php') {
    btnVolver.href = '../../T5_P2.php';
  } else if (pagina === 'destruir.html' || pagina === 'destruir.php') {
    btnVolver.href = '../../T5_P2.php';
  }
}

