<!-- plugins:js -->
<script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{ asset('purple/assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('purple/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('purple/assets/js/misc.js') }}"></script>
<script src="{{ asset('purple/assets/js/settings.js') }}"></script>
<script src="{{ asset('purple/assets/js/todolist.js') }}"></script>
<script src="{{ asset('purple/assets/js/jquery.cookie.js') }}"></script>
<!-- endinject -->

<script src="{{ asset('js/dashboard.js') }}"></script>

@stack('scripts')