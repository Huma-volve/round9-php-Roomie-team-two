@extends('layouts.master')

@section('title')
test innn
@endsection

@section('css')
<style>
    .page-title {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .breadcrumb {
        justify-content: center;
    }
    .logo {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>
@endsection

@section('page-header')
<div class="page-title d-flex flex-column gap-1 me-3 mb-2">
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
            <i class="ki-duotone ki-home fs-3 text-gray-400 me-n1"></i>
        </li>
        <!--end::Item-->
        <li class="breadcrumb-item">
            <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">HOME</li>
    </ul>
</div>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid text-center" >
    
    <h2 class="fw-bold text-gray-800">Welcome to Roomie</h2>
    <div class="logo">
       <img class="mx-auto h-150px h-lg-175px mb-4" src="assets/media/misc/saul-welcome.png" alt="" />
								<!--end::Illustration-->
								
    </div>
</div>
@endsection

@section('javascript')

@endsection
