<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Home | Mantis Bootstrap 5 Admin Template</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description"
    content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords"
    content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="../assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="../assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="../assets/css/style-preset.css">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
  <!-- [ Sidebar Menu ] start -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="../dashboard/index.html" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo">
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item">
            <a href="../dashboard/index.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>Management</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item">
            <a href="/user" class="pc-link">
              <span class="pc-micon"><i class="ti ti-user"></i></span>
              <span class="pc-mtext">User</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="/payment" class="pc-link">
              <span class="pc-micon"><i class="ti ti-receipt"></i></span>
              <span class="pc-mtext">Payment</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="/download" class="pc-link">
              <span class="pc-micon"><i class="ti ti-history"></i></span>
              <span class="pc-mtext">Download</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="/scheduled" class="pc-link">
              <span class="pc-micon"><i class="ti ti-access-point"></i></span>
              <span class="pc-mtext">Scheduled</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="/activity" class="pc-link">
              <span class="pc-micon"><i class="ti ti-calendar-time"></i></span>
              <span class="pc-mtext">Activity</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="/billing" class="pc-link">
              <span class="pc-micon"><i class="ti ti-sport-billard"></i></span>
              <span class="pc-mtext">Billing</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
  <header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="dropdown pc-h-item d-inline-flex d-md-none">
            <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-search"></i>
            </a>
          </li>
        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="pc-h-item d-none d-md-inline-flex">
            <form class="header-search">
              <i data-feather="search" class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search here. . .">
            </form>
          </li>
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false" x>
              <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
              <span>Stebin Ben</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <!-- [ Header ] end -->
  
  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Activity Log</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->
       <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">Previous</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
      </nav>
      <!-- [ Main Content ] start -->
      <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="text-end">ID</th>
                    <th class="text-end">User ID</th>
                    <th>Aksi</th>
                    <th class="text-end">Instance ID</th>
                    <th class="text-end">Waktu</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-end">1</td>
                    <td class="text-end">101</td>
                    <td><span class="badge bg-primary">Create</span></td>
                    <td class="text-end">9001</td>
                    <td class="text-end">2025-05-16 09:30:25</td>
                  </tr>
                  <tr>
                    <td class="text-end">2</td>
                    <td class="text-end">102</td>
                    <td><span class="badge bg-success">Start</span></td>
                    <td class="text-end">9002</td>
                    <td class="text-end">2025-05-16 09:45:00</td>
                  </tr>
                  <tr>
                    <td class="text-end">3</td>
                    <td class="text-end">103</td>
                    <td><span class="badge bg-warning text-dark">Stop</span></td>
                    <td class="text-end">9003</td>
                    <td class="text-end">2025-05-16 10:05:42</td>
                  </tr>
                  <tr>
                    <td class="text-end">4</td>
                    <td class="text-end">101</td>
                    <td><span class="badge bg-danger">Terminate</span></td>
                    <td class="text-end">9004</td>
                    <td class="text-end">2025-05-16 10:10:12</td>
                  </tr>
                  <!-- Tambahkan baris lain jika perlu -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm my-1">
          <p class="m-0">Mantis &#9829; crafted by Team <a href="https://themeforest.net/user/codedthemes"
              target="_blank">Codedthemes</a> Distributed by <a href="https://themewagon.com/">ThemeWagon</a>.</p>
        </div>
        <div class="col-auto my-1">
          <ul class="list-inline footer-link mb-0">
            <li class="list-inline-item"><a href="../index.html">Home</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- [Page Specific JS] start -->
  <script src="../assets/js/plugins/apexcharts.min.js"></script>
  <script src="../assets/js/pages/dashboard-default.js"></script>
  <!-- [Page Specific JS] end -->
  <!-- Required Js -->
  <script src="../assets/js/plugins/popper.min.js"></script>
  <script src="../assets/js/plugins/simplebar.min.js"></script>
  <script src="../assets/js/plugins/bootstrap.min.js"></script>
  <script src="../assets/js/fonts/custom-font.js"></script>
  <script src="../assets/js/pcoded.js"></script>
  <script src="../assets/js/plugins/feather.min.js"></script>

  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Public-Sans");</script>
</body>
<!-- [Body] end -->
</html>