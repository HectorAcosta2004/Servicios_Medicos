<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
  data-scroll="false">
  <div class="container-fluid py-1 px-3">

    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group">
          <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
          <input type="text" class="form-control" placeholder="Buscar">
        </div>
      </div>
      <ul class="navbar-nav  justify-content-end">
        <li class="nav-item d-flex align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar sesión</span>
          </a>
        </li>
        </li>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </a>
        </li>
        <li class="nav-item px-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-white p-0">
            <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
          </a>
        </li>

        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa fa-bell cursor-pointer"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <!-- Modal de edición de perfil -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" id="contenidoFormularioPerfil">
    </div>
  </div>
</div>

<!-- Script para abrir el modal cuando se haga clic en el ícono -->
<script>
  document.querySelector('.fixed-plugin-button-nav').addEventListener('click', function () {
    fetch('modals/modal_editar_perfil.php')
      .then(response => response.text())
      .then(html => {
        document.getElementById('contenidoFormularioPerfil').innerHTML = html;
        var modal = new bootstrap.Modal(document.getElementById('modalEditarPerfil'));
        modal.show();
      })
      .catch(error => console.error('Error al cargar el formulario:', error));
  });
</script>

<!-- Asegúrate de incluir los archivos de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</nav>
<!-- End Navbar -->