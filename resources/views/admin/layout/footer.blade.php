</div>
</div>
</div>

<!-- <footer class="footer footer-static footer-light navbar-border">
      <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright  &copy; 2017 <a href="https://themeforest.net/user/pixinvent/portfolio?ref=pixinvent" target="_blank" class="text-bold-800 grey darken-2">PIXINVENT </a>, All rights reserved. </span><span class="float-md-right d-xs-block d-md-inline-block">Hand-crafted & Made with <i class="icon-heart5 pink"></i></span></p>
    </footer> -->

    <!-- BEGIN VENDOR JS-->
    <script src="{{asset('admin/js/core/libraries/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/ui/tether.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/core/libraries/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/ui/perfect-scrollbar.jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/ui/unison.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/ui/blockUI.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/ui/jquery.matchHeight-min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/ui/screenfull.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/vendors/js/extensions/pace.min.js')}}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('admin/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <script src="{{asset('admin/js/core/app-menu.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/core/app.js')}}" type="text/javascript"></script>
    <!-- END ROBUST JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="{{asset('admin/js/scripts/pages/dashboard-lite.js')}}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->

    <!-- for adding extra script  -->
  @yield('script')

  </body>
</html>