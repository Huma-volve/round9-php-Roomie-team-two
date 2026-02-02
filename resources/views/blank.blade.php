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
        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">الرئيسية</li>
    </ul>
</div>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid text-center" >
    <h1 class="text-dark fw-bolder fs-1 lh-0 ">بِسْمِ اللَّـهِ الرَّحْمَـٰنِ الرَّحِيمِ</h1>
    <h2 class="fs-3 text-success m-3"  style="margin-top: 20px !important;">السلام عليكم</h2>
    
    <!-- إضافة اللوغو في الوسط -->
    <div class="logo">
        <img src="{{ asset('assets/images/ORG-1024x844.png') }}" alt="Logo" style="max-width: 250px;">
    </div>
</div>
@endsection

@section('javascript')

@endsection
