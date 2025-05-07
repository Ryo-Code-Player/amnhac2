<!doctype html>
<html class="no-js" lang="en">
    @include('frontend.layouts.head')
    <body data-mobile-nav-style="full-screen-menu" data-mobile-nav-bg-color="#353642" class="custom-cursor overflow-x-hidden"> 
        <!-- start cursor -->
        <div class="cursor-page-inner">
            <div class="circle-cursor circle-cursor-inner"></div>
            <div class="circle-cursor circle-cursor-outer"></div>
        </div>
        <!-- end cursor -->
        <!-- start header --> 
        @include('frontend.layouts.header')
        <!-- end header --> 
    
        <!-- start section -->
        @include('frontend.blog.section')
        <!-- end section -->

        <!-- start footer -->
        @include('frontend.layouts.footer')
        <!-- end footer -->

        <!-- start scroll progress -->
        @include('frontend.layouts.scroll')
        <!-- end scroll progress -->

        <!-- javascript libraries -->
         
        <script type="text/javascript" src="{{ asset('frontend/js/jquery.js')}}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/vendors.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/index.js')}}"></script>
        <!-- <script type="text/javascript" src="{{ asset('frontend/js/main.js')}}"></script> -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="{{ asset('frontend/lib/owl-carousel/dist/owl.carousel.min.js')}}"></script>

</body>
</html>
