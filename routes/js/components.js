function buildNavbar() {
  const cartCount = getCart().reduce((s, i) => s + i.qty, 0);
  const usuario   = getUsuario();

  const userBtn = usuario
    ? `<span class="nav-btn">
         👤 ${usuario.nome.split(' ')[0]}
       </span>
       <button class="nav-btn" onclick="logout()">Sair</button>`
    : `<a href="login.html" class="nav-btn">
         <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
           <circle cx="12" cy="8" r="4"/>
           <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
           <path d="M19 8h2m-1-1v2" stroke-width="2.5"/>
         </svg>
         <span class="btn-label">Entrar</span>
       </a>
       <a href="cadastro.html" class="nav-btn">
         <span class="btn-label">Cadastro</span>
       </a>`;

  return `
  <nav class="navbar">
    <div class="container nav-inner">
      <a href="index.html" class="nav-logo">
        <image src="logo.svg" width="28" height="28">
        Saiba<span style="color:#C3FF1F">Plus</span>
      </a>
      <div class="nav-actions">
        ${userBtn}
        <a href="matricula.html" class="nav-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
          </svg>
          <span class="btn-label">Selecionados</span>
          <span class="cart-badge" style="display:${cartCount > 0 ? 'inline-flex' : 'none'}">${cartCount}</span>
        </a>
      </div>
    </div>
  </nav>`;
}

function buildFooter() {
  return `<footer class="footer"><div class="container">© 2026 SaibaPlus · Plataforma de Cursos Online · <a href="index.html">Início</a></div></footer>`;
}