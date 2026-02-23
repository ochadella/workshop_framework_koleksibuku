```blade
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }} - @yield('title', 'Dashboard') </title>

<!-- plugins:css -->
<link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('purple/assets/vendors/ti-icons/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('purple/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
<!-- endinject -->

<!-- Plugin css for this page -->
<link rel="stylesheet" href="{{ asset('purple/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<!-- End plugin css for this page -->

<!-- Layout styles -->
<link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
<!-- End layout styles -->

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
```
