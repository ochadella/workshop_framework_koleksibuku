<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  </head>

  <body>
    <div class="container-scroller">
      <!-- NAVBAR -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
          </a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>

          <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>
          </div>

          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">David Greymaax</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="#">
                  <i class="mdi mdi-cached me-2 text-success"></i> Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                  <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                </a>
              </div>
            </li>

            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
              </a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-email-outline"></i>
                <span class="count-symbol bg-warning"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Messages</h6>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="{{ asset('assets/images/faces/face4.jpg') }}" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a message</h6>
                    <p class="text-gray mb-0"> 1 Minutes ago </p>
                  </div>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="{{ asset('assets/images/faces/face2.jpg') }}" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a message</h6>
                    <p class="text-gray mb-0"> 15 Minutes ago </p>
                  </div>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="{{ asset('assets/images/faces/face3.jpg') }}" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture updated</h6>
                    <p class="text-gray mb-0"> 18 Minutes ago </p>
                  </div>
                </a>

                <div class="dropdown-divider"></div>
                <h6 class="p-3 mb-0 text-center">4 new messages</h6>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="mdi mdi-calendar"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                    <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today </p>
                  </div>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                      <i class="mdi mdi-cog"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                    <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                  </div>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-link-variant"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                    <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                  </div>
                </a>

                <div class="dropdown-divider"></div>
                <h6 class="p-3 mb-0 text-center">See all notifications</h6>
              </div>
            </li>

            <li class="nav-item nav-logout d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-power"></i>
              </a>
            </li>

            <li class="nav-item nav-settings d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-format-line-spacing"></i>
              </a>
            </li>
          </ul>

          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- END NAVBAR -->

      <div class="container-fluid page-body-wrapper">
        <!-- SIDEBAR -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
                  <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">David Grey. H</span>
                  <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ url('/dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Basic UI Elements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <div class="collapse show" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/buttons') }}">Buttons</a>
                  </li>
                </ul>
              </div>
            </li>

          </ul>
        </nav>
        <!-- END SIDEBAR -->

        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Buttons </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">UI Elements</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Buttons</li>
                </ol>
              </nav>
            </div>

            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Gradient buttons</h4>
                    <p class="card-description">Add class <code>.btn-gradient-{color}</code> for gradient buttons</p>
                    <div class="template-demo">
                      <button type="button" class="btn btn-gradient-primary btn-fw">Primary</button>
                      <button type="button" class="btn btn-gradient-secondary btn-fw">Secondary</button>
                      <button type="button" class="btn btn-gradient-success btn-fw">Success</button>
                      <button type="button" class="btn btn-gradient-danger btn-fw">Danger</button>
                      <button type="button" class="btn btn-gradient-warning btn-fw">Warning</button>
                      <button type="button" class="btn btn-gradient-info btn-fw">Info</button>
                      <button type="button" class="btn btn-gradient-light btn-fw">Light</button>
                      <button type="button" class="btn btn-gradient-dark btn-fw">Dark</button>
                      <button type="button" class="btn btn-link btn-fw">Link</button>
                    </div>
                  </div>

                  <div class="card-body">
                    <h4 class="card-title">Rounded buttons</h4>
                    <p class="card-description">Add class <code>.btn-rounded</code></p>
                    <div class="template-demo">
                      <button type="button" class="btn btn-gradient-primary btn-rounded btn-fw">Primary</button>
                      <button type="button" class="btn btn-gradient-secondary btn-rounded btn-fw">Secondary</button>
                      <button type="button" class="btn btn-gradient-success btn-rounded btn-fw">Success</button>
                      <button type="button" class="btn btn-gradient-danger btn-rounded btn-fw">Danger</button>
                      <button type="button" class="btn btn-gradient-warning btn-rounded btn-fw">Warning</button>
                      <button type="button" class="btn btn-gradient-info btn-rounded btn-fw">Info</button>
                      <button type="button" class="btn btn-gradient-light btn-rounded btn-fw">Light</button>
                      <button type="button" class="btn btn-gradient-dark btn-rounded btn-fw">Dark</button>
                      <button type="button" class="btn btn-link btn-rounded btn-fw">Link</button>
                    </div>
                  </div>

                  <div class="card-body">
                    <h4 class="card-title">Outlined buttons</h4>
                    <p class="card-description">Add class <code>.btn-outline-{color}</code> for outline buttons</p>
                    <div class="template-demo">
                      <button type="button" class="btn btn-outline-primary btn-fw">Primary</button>
                      <button type="button" class="btn btn-outline-secondary btn-fw">Secondary</button>
                      <button type="button" class="btn btn-outline-success btn-fw">Success</button>
                      <button type="button" class="btn btn-outline-danger btn-fw">Danger</button>
                      <button type="button" class="btn btn-outline-warning btn-fw">Warning</button>
                      <button type="button" class="btn btn-outline-info btn-fw">Info</button>
                      <button type="button" class="btn btn-outline-light text-black btn-fw">Light</button>
                      <button type="button" class="btn btn-outline-dark btn-fw">Dark</button>
                      <button type="button" class="btn btn-link btn-fw">Link</button>
                    </div>
                  </div>

                  <div class="card-body">
                    <h4 class="card-title">Single color buttons</h4>
                    <p class="card-description">Add class <code>.btn-{color}</code> for buttons in theme colors</p>
                    <div class="template-demo">
                      <button type="button" class="btn btn-primary btn-fw">Primary</button>
                      <button type="button" class="btn btn-secondary btn-fw">Secondary</button>
                      <button type="button" class="btn btn-success btn-fw">Success</button>
                      <button type="button" class="btn btn-danger btn-fw">Danger</button>
                      <button type="button" class="btn btn-warning btn-fw">Warning</button>
                      <button type="button" class="btn btn-info btn-fw">Info</button>
                      <button type="button" class="btn btn-light btn-fw">Light</button>
                      <button type="button" class="btn btn-dark btn-fw">Dark</button>
                      <button type="button" class="btn btn-link btn-fw">Link</button>
                    </div>
                  </div>

                  <div class="card-body">
                    <h4 class="card-title">Inverse buttons</h4>
                    <p class="card-description">Add class <code>.btn-inverse-{color}</code> for inverse buttons</p>
                    <div class="template-demo">
                      <button type="button" class="btn btn-inverse-primary btn-fw">Primary</button>
                      <button type="button" class="btn btn-inverse-secondary btn-fw">Secondary</button>
                      <button type="button" class="btn btn-inverse-success btn-fw">Success</button>
                      <button type="button" class="btn btn-inverse-danger btn-fw">Danger</button>
                      <button type="button" class="btn btn-inverse-warning btn-fw">Warning</button>
                      <button type="button" class="btn btn-inverse-info btn-fw">Info</button>
                      <button type="button" class="btn btn-inverse-light btn-fw">Light</button>
                      <button type="button" class="btn btn-inverse-dark btn-fw">Dark</button>
                      <button type="button" class="btn btn-link btn-fw">Link</button>
                    </div>
                  </div>

                  <div class="card-body">
                    <h4 class="card-title">Normal buttons</h4>
                    <p class="card-description">Use any of the available button classes to quickly create a styled button.</p>
                    <div class="template-demo">
                      <button type="button" class="btn btn-gradient-primary">Primary</button>
                      <button type="button" class="btn btn-gradient-secondary">Secondary</button>
                      <button type="button" class="btn btn-gradient-success">Success</button>
                      <button type="button" class="btn btn-gradient-danger">Danger</button>
                      <button type="button" class="btn btn-gradient-warning">Warning</button>
                      <button type="button" class="btn btn-gradient-info">Info</button>
                      <button type="button" class="btn btn-gradient-light">Light</button>
                      <button type="button" class="btn btn-gradient-dark">Dark</button>
                      <button type="button" class="btn btn-link">Link</button>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                Copyright © 2023 <a href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>. All rights reserved.
              </span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i>
              </span>
            </div>
          </footer>
        </div>
      </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <!-- endinject -->
  </body>
</html>
