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

        <!-- start page title -->  
        @include('frontend.songs.pagetitle')
        <!-- end page title -->  

        <!-- start section -->
        @include('frontend.songs.section')
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
        <script type="text/javascript" src="{{ asset('frontend/js/main.js')}}"></script>
    </body>
</html>