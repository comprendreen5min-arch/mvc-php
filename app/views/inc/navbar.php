  <?php
    $help = new Helper();
    ?>
  <!-- Begin page -->
  <div id="layout-wrapper">


      <header id="page-topbar">
          <div class="navbar-header">
              <div class="d-flex">
                  <!-- LOGO -->
                  <div class="navbar-brand-box">
                      <a href="<?php print URLROOT; ?>/pages/index" class="logo logo-dark">
                          <span class="logo-sm">
                              <img src="<?php print URLROOT . '/' . THEME_IMAGES; ?>/logo-sm.png" alt="" height="22">
                          </span>
                          <span class="logo-lg">
                              <img src="<?php print URLROOT . '/' . UPLOADS; ?>/logo-dark.png" alt="" height="17">
                          </span>
                      </a>

                      <a href="<?php print URLROOT; ?>/pages/index" class="logo logo-light">
                          <span class="logo-sm">
                              <img src="<?php print URLROOT . '/' . THEME_IMAGES; ?>/logo-sm.png" alt="" height="22">
                          </span>
                          <span class="logo-lg">
                              <img src="<?php print URLROOT . '/' . UPLOADS; ?>/logo-light.png" alt="" height="18">
                          </span>
                      </a>
                  </div>

                  <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"
                      id="vertical-menu-btn">
                      <i class="mdi mdi-menu"></i>
                  </button>


              </div>

              <div class="d-flex">
                  <!-- App Search-->


                  <div class="dropdown d-inline-block">
                      <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                          data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <img class="rounded-circle header-profile-user p-1"
                              src="<?php print URLROOT . '/' . THEME_IMAGES; ?>/logo-sm.png" alt="Header Avatar">
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->

                          <a class="dropdown-item text-danger" href="<?php print URLROOT; ?>/logins/logout"><i
                                  class="bx bx-power-off font-size-17 align-middle me-1 text-danger"></i>Logout</a>
                      </div>
                  </div>

                  <div class="dropdown d-inline-block">
                      <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                          <i class="mdi mdi-cog-outline"></i>
                      </button>
                  </div>

              </div>
          </div>
      </header>