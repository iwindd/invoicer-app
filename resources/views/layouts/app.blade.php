<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Invoicer">
  <meta name="author" content="SiamIT">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
  <link href="{{ asset('css/selectize.bootstrap4.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <script src="{{ asset('js/formatter.js') }}"></script>
</head>

<body id="page-top">
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('invoices')}}">
        <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }}</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      @php
        $navItems = [
            ['label' => __('nav.invoices'), 'icon' => 'fas fa-fw fa-receipt', 'href' => 'invoices'],
            ['label' => __('nav.customers'), 'icon' => 'fas fa-fw fa-users', 'href' => 'customers'],
            ['label' => __('nav.payments'), 'icon' => 'fas fa-fw fa-credit-card', 'href' => 'payments'],
        ];

        if (Auth::user()->role == 'user') {
            $navItems = array_merge($navItems, [
                ['label' => __('nav.applications'), 'icon' => 'fas fa-fw fa-cogs', 'href' => 'applications'],
            ]);
        }
      @endphp

      @foreach ($navItems as $item)
        <li class="nav-item">
          <a class="nav-link" href="{{ Route::has($item['href']) ? route($item['href']) : '#' }}">
            <i class="{{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
          </a>
        </li>
      @endforeach
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown ">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  {{ __('nav.profile') }}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  {{ __('nav.logout') }}
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          @yield('heading')
          @yield('content')

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; {{ config('app.name', 'Laravel') }} 2024</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('ui.dialogHeader') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">{{ __('nav.logoutDetail') }}</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('ui.dialogCancel') }}</button>
          <button class="btn btn-primary" type="submit" form="logout-form">{{ __('ui.dialogConfirm') }}</button>
        </div>
      </div>
    </div>
  </div>

  @yield('modals')
  @if (Auth::user()->role == "user")
    @yield('modals:user')
  @else
    @yield('modals:application')
  @endif

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

  <!-- Page level plugins -->
  <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

  <!-- Page level custom scripts -->
  <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('js/dayjs.min.js') }}"></script>
  <script src="{{ asset('js/dayjs.isBetween.min.js') }}"></script>
  <script>
    dayjs.extend(window.dayjs_plugin_isBetween)
  </script>
  <script src="{{ asset('js/validation.js') }}"></script>
  <script src="{{ asset('js/autoformatter.js') }}"></script>
  <script src="{{ asset('js/selectize.min.js') }}"></script>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: "bottom-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });

    const Confirmation = Swal.mixin({
      title: "{{ __('ui.dialogHeader') }}",
      customClass: {
        confirmButton: "btn shadow-sm btn-primary mr-1",
        cancelButton: "btn shadow-sm btn-secondary ml-1",
        icon: "text-warning border-warning"
      },
      showCancelButton: true,
      buttonsStyling: false,
      cancelButtonText: "{{ __('ui.dialogCancel') }}",
      confirmButtonText: "{{ __('ui.dialogConfirm') }}",
      icon: "warning",
    });

    const Alert = {
      success: Swal.mixin({
        title: "{{ __('ui.dialogHeaderSuccess') }}",
        customClass: {
          confirmButton: "btn shadow-sm btn-primary mr-1",
        },
        buttonsStyling: false,
        confirmButtonText: "{{ __('ui.dialogConfirm') }}",
        icon: "success",
      })
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
    })
  </script>

  @yield('scripts')
  @if (Auth::user()->role == "user")
    @yield('scripts:user')
  @else
    @yield('scripts:application')
    <script src="{{route('notice.api', ['id' => Auth::user()->id, 'only'=> 1])}}"></script>
  @endif
</body>

</html>
