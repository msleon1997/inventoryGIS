</style>
<!-- MAIN SIDEBAR CONTAINER -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
  <!-- BRAND LOGO -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-warning text-sm">
    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="STORE LOGO" class="brand-image img-circle elevation-3 bg-black" style="width: 1.8rem;height: 1.8rem;max-height: unset">
    <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
  </a>
  <!-- SIDEBAR -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
    <div class="os-resize-observer-host observed">
      <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
    </div>
    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
      <div class="os-resize-observer"></div>
    </div>
    <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
    <div class="os-padding">
      <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
        <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
          <!-- SIDEBAR USER PANEL (OPTIONAL) -->
          <div class="clearfix"></div>
          <!-- SIDEBAR MENU -->
          <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item dropdown">
                <a href="./" class="nav-link nav-home">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    DASHBOARD
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url ?>admin/?page=ordenes" class="nav-link nav-purchase_order">
                  <i class="nav-icon fas fa-th-list"></i>
                  <p>
                    ÓRDENES DE COMPRA
                  </p>
                </a>
              </li>
             <!--  <li class="nav-item">
                <a href="<?php echo base_url ?>admin/?page=productosRecividos" class="nav-link nav-receiving">
                  <i class="nav-icon fas fa-boxes"></i>
                  <p>
                    PRODUCTOS RECIBIDOS
                  </p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="<?php echo base_url ?>admin/?page=devoluciones" class="nav-link nav-return">
                  <i class="nav-icon fas fa-undo"></i>
                  <p>
                    LISTA DE DEVOLUCIONES
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url ?>admin/?page=existencias" class="nav-link nav-stocks">
                  <i class="nav-icon fas fa-table"></i>
                  <p>
                    EXISTENCIAS
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url ?>admin/?page=ventas" class="nav-link nav-sales">
                  <i class="nav-icon fas fa-file-invoice-dollar"></i>
                  <p>
                    VENTAS
                  </p>
                </a>
              </li>
              <?php if ($_settings->userdata('type') == 1) : ?>
                <li class="nav-header">SISTEMA</li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=proveedores" class="nav-link nav-maintenance_supplier">
                    <i class="nav-icon fas fa-truck-loading"></i>
                    <p>
                      PROVEEDORES
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=proveedoresMap" class="nav-link nav-maintenance_supplier">
                    <i class="nav-icon fas fa-map"></i>
                    <p>
                      PROVEEDORES MAP
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=productos" class="nav-link nav-maintenance_item">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                      PRODUCTOS
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=clientes" class="nav-link nav-clients">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <p>
                      CLIENTES
                    </p>
                  </a>
                </li>
                <li class="nav-header">PERFIL</li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      USUARIOS
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                      CONFIGURACIÓN
                    </p>
                  </a>
                </li>
              <?php endif; ?>

            </ul>
          </nav>
          <!-- /.SIDEBAR MENU -->
        </div>
      </div>
    </div>
    <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
      </div>
    </div>
    <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
      </div>
    </div>
    <div class="os-scrollbar-corner"></div>
  </div>
  <!-- /.SIDEBAR -->
</aside>
<script>
  var page;
  $(document).ready(function() {
    page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    page = page.replace(/\//gi, '_');

    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active')
      if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }
      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open')
      }

    }

    $('#receive-nav').click(function() {
      $('#uni_modal').on('shown.bs.modal', function() {
        $('#find-transaction [name="tracking_code"]').focus();
      })
      uni_modal("ENTER TRACKING NUMBER", "transaction/find_transaction.php");
    })
  })
</script>
