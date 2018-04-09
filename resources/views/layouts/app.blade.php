<!DOCTYPE html>
<html lang="en">
@include('includes.top-head')
<body>

<!-- Wrapper -->
<div class="wrapper push-wrapper">

    @include('includes.header')


    <!-- Main Content -->
    @yield('content')
    <!-- Footer -->
    @include('includes.footer')
    <!-- Footer -->

</div>
<!-- Wrapper -->

<!-- Slide Menu -->
@include('includes.slideMenu')
<!-- Slide Menu -->

<!-- back To Button -->
<span id="scrollup" class="scrollup circle-btn"><i class="fa fa-angle-up"></i></span>
<!-- back To Button -->

<!-- Java Script -->
@include('includes.scripts')

{{--<script src="js/prettyPhoto.js"></script>--}}
{{--<script src="http://maps.google.com/maps/api/js?sensor=false"></script>--}}
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyARvxnSY3F_zXnrZ0apCmbKgcyfCE6iUp0"></script>

</body>
</html>