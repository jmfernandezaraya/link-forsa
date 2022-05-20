<!DOCTYPE html>
<html lang="{{ app()->getLocale()}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Link Forsa - Admin</title>
        
        <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">

        <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}" />
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{asset('assets/datatables/datatables.min.css')}}">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{asset('assets/js/tag/css/jquery.tagit.css')}}" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
        
        @toastr_css
        @livewireStyles
        @yield('css')
    </head>
    <body>
        <div id="loader"></div>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            @include('schooladmin.include.header')
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_sidebar.html -->
                @include('schooladmin.include.sidebar')
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    @include('schooladmin.include.footer')
    </body>
    <div id="loading"></div>
</html>