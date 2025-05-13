<aside
  class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
  id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
      aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0"
      target="_blank">
      <img src="../assets/img/Logo.jpg" width="26px" height="26px" class="navbar-brand-img h-100"
        alt="main_logo">
      <span class="ms-1 font-weight-bold">Salud y Esperanza</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      
      <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>" href="../pages/dashboard_admin.php">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Reportes</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'asignacion') ? 'active' : ''; ?>" href="../pages/Asignacion.php">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Servicios</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'usuarios') ? 'active' : ''; ?>" href="../pages/administracion_usuarios.php">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Usuarios</span>
        </a>
      </li>

    </ul>
  </div>

  <div class="sidenav-footer mx-3">
    <div class="card card-plain shadow-none" id="sidenavCard">
    </div>
    <a href="../pages/index.php"
      class="btn btn-dark btn-sm w-100 mb-3">Salir</a>
  </div>
</aside>
