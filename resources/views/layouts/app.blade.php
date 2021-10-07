
<!DOCTYPE html>
<html lang="en">

<head>
  <base href="{{  url('/') }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title></title>
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="images/favicon.ico" />
  <script>
  window.roles = @json(Auth::user()->roles()->get()->pluck('name'));
  </script>
  <style>
  .btn.btn-xm{
    width: 24px;
    height: 24px;
    padding: 2px;
    font-size: 14px;;
  }
  .card .paginate-footer{
    margin-top:20px;
    padding: 0px;
  }
  .txt-red{
    color: red;
  }
  #updateService .form-group{
    margin-bottom: 5px !important;
}
#updateService .modal-body{
    padding: 0px 26px !important;
}
#updateService .col-form-label{
    margin-bottom: 0px !important;
}
.statistics-item, .navbar-nav .badge{
  cursor: pointer;
}
  </style>
</head>
<body class="sidebar-dark">
   
  <div id="app" class="container-scroller" style="display:none;">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{  url('/') }}"><img src="images/logo.jpeg" style="width: 80px;" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{  url('/') }}"><img src="images/logo.jpeg" style="width: 50px;height:50px;" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="fas fa-bars"></span>
        </button>
        <ul class="navbar-nav">
          <!-- <li class="nav-item nav-search d-none d-md-flex">
            <div class="nav-link">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
                <input type="text" class="form-control" placeholder="Search" aria-label="Search">
              </div>
              <div class="dropdown"></div>
            </div>
          </li> -->
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item d-none d-lg-flex">
            <router-link tag="a" :to="{ name:'customers.create'}" class="nav-link">
              <span class="btn btn-primary">+ Create Customer</span>
            </router-link>
          </li>
          <notifications></notifications>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="fas fa-bell mx-0"></i>
              <span class="count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <a class="dropdown-item">
                <p class="mb-0 font-weight-normal float-left">You have 0 new notifications
                </p>
                <span class="badge badge-pill badge-warning float-right">View all</span>
              </a>
              {{--
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-danger">
                    <i class="fas fa-exclamation-circle mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-medium">Application Error</h6>
                  <p class="font-weight-light small-text">
                    Just now
                  </p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              --}}
            </div>
          </li> -->
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="images/avatar.png" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <!-- <a class="dropdown-item">
                <i class="fas fa-cog text-primary"></i>
                Settings
              </a> -->
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/logout">
                <i class="fas fa-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="fas fa-bars"></span>
        </button>
      </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image">
                <img src="images/avatar.png" alt="image"/>
              </div>
              <div class="profile-name">
                <p class="name">
                  {{ Auth::user()->fullname }}
                </p>
                <p class="designation">
                  {{ Auth::user()->getRoleName() }}
                </p>
              </div>
            </div>
          </li>
          <router-link :to="{ name:'dashboard'}" tag="li" class="nav-item">
            <a class="nav-link" href="index.html">
              <i class="fa fa-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </router-link>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#customer-management" aria-expanded="false" aria-controls="customer-management">
              <i class="far fa-user menu-icon"></i>
              <span class="menu-title">Customer Management</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="customer-management">
              <ul class="nav flex-column sub-menu">
                <router-link :to="{ name:'customers'}" tag="li" class="nav-item"> <a class="nav-link">All Customers</a></router-link>
                <router-link :to="{ name:'customers.create'}" tag="li" class="nav-item"> <a class="nav-link">Add New Customer</a></router-link>
                <router-link :to="{ name:'customers.bills'}" tag="li" class="nav-item"> <a class="nav-link">Bills</a></router-link>
              </ul>
              </div>
          </li>
          @if (Auth::user()->hasRole('Super Admin'))

            <li class="nav-item d-none d-lg-block">
              <a class="nav-link" data-toggle="collapse" href="#users-management" aria-expanded="false" aria-controls="users-management">
                <i class="fas fa-user-secret menu-icon"></i>
                <span class="menu-title">User Management</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="users-management">
                <ul class="nav flex-column sub-menu">
                  <router-link :to="{ name:'users'}" tag="li" class="nav-item"> 
                  <a class="nav-link">All Users</a>
                  </router-link>
                  <router-link :to="{ name:'users.create'}" tag="li" class="nav-item"> <a class="nav-link">Add New User</a></router-link>
                  </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#admin-management" aria-expanded="false" aria-controls="admin-management">
                <i class="fas fa-cogs menu-icon"></i>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="admin-management">
                <ul class="nav flex-column sub-menu">
                  <!-- <router-link :to="{ name:'siteSettings'}" tag="li" class="nav-item"> 
                  <a class="nav-link">Site Settings</a>
                  </router-link> -->
                  <router-link :to="{ name:'roles'}" tag="li" class="nav-item"> <a class="nav-link">Roles</a></router-link>
                  <!-- <router-link :to="{ name:'permissions'}" tag="li" class="nav-item"> <a class="nav-link">Permissions</a></router-link> -->
                
                  <router-link :to="{ name:'serviceTypes'}" tag="li" class="nav-item">
                  <a class="nav-link">Service Types</a>
                  </router-link>

                  <router-link :to="{ name:'emailSetting.create'}" tag="li" class="nav-item">
                  <a class="nav-link">Email Settings</a>
                  </router-link>

                  <router-link :to="{ name:'emailSetting.schedule'}" tag="li" class="nav-item">
                  <a class="nav-link">Email Schedule</a>
                  </router-link>

                </ul>
              </div>
            </li>

          @endif
      </nav> 
      <div class="main-panel">
        <router-view></router-view>      
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021 <a href="#" target="_blank">Mercury</a>. All rights reserved.</span>
          </div>
        </footer>
      </div>
    </div>
  </div>
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/vue-tel-input/dist/vue-tel-input.css">
  <script src="https://unpkg.com/vue-tel-input@5.6.1"></script>


  <script src="{{ mix('js/app.js') }}"></script> 
  <script>
   $(document).ready(function() {
     $('#app').show();
    });
  </script>
</body>

</html>
