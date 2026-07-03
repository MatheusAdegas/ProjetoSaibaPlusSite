const API = 'http://localhost:8080/SaibaPlus/public/api';

// ===== CART =====
function getCart() {
  try { return JSON.parse(localStorage.getItem('saibaplus_cart') || '[]'); }
  catch { return []; }
}
function saveCart(cart) {
  localStorage.setItem('saibaplus_cart', JSON.stringify(cart));
  updateCartBadge();
}
function addToCart(cursoId, titulo, preco, imagem) {
  const cart = getCart();
  const existing = cart.find(i => i.id === cursoId);
  if (existing) { existing.qty += 1; }
  else { cart.push({ id: cursoId, titulo, preco, imagem, qty: 1 }); }
  saveCart(cart);
  showToast('✅ Curso adicionado à seleção!', 'success');
}
function updateCartBadge() {
  const total = getCart().reduce((s, i) => s + i.qty, 0);
  document.querySelectorAll('.cart-badge').forEach(el => {
    el.textContent = total;
    el.style.display = total > 0 ? 'inline-flex' : 'none';
  });
}

// ===== AUTH =====
function getToken()   { return localStorage.getItem('saibaplus_token'); }
function getUsuario() {
  try { return JSON.parse(localStorage.getItem('saibaplus_usuario') || 'null'); }
  catch { return null; }
}
function isLoggedIn() { return !!getToken(); }
function logout() {
  fetch(`${API}/auth/logout`, {
    method: 'POST',
    headers: { 'Authorization': getToken() }
  }).finally(() => {
    localStorage.removeItem('saibaplus_token');
    localStorage.removeItem('saibaplus_usuario');
    updateCartBadge();
    window.location.href = 'index.html';
  });
}

// ===== API HELPERS =====
async function apiFetch(endpoint) {
  const res = await fetch(`${API}/${endpoint}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

async function apiPost(endpoint, body) {
  const res = await fetch(`${API}/${endpoint}`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify(body),
  });
  return res.json();
}

// ===== TOAST =====
function showToast(msg, type = 'info') {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity .4s';
    setTimeout(() => toast.remove(), 400);
  }, 3500);
}

// ===== HELPERS =====
function levelLabel(l) {
  return { iniciante: 'Iniciante', intermediario: 'Intermediário', avancado: 'Avançado' }[l] || l;
}
function formatPrice(p) {
  return Number(p).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

document.addEventListener('DOMContentLoaded', updateCartBadge);