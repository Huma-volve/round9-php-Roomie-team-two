

{{-- <link href="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.rtl.css')}}" rel="stylesheet" type="text/css" /> --}}
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />

<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />

<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

<link rel="shortcut icon" href="assets/media/logos/newicon.ico" />
<link rel="icon" href="{{ asset('newicon.ico') }}" type="image/png">
<!--begin::Fonts(mandatory for all pages)-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
{{-- <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" /> --}}

<style>
  body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: px;
        }
</style>
@yield('css')
