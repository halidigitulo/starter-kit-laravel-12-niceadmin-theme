
@include('layouts.header')

<body data-topbar="dark" class="">
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    {{-- @include('layout.right-sidebar') --}}

    @include('layouts.script')