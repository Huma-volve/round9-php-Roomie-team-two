@extends('layouts.admin')

@section('title', 'Admin Dashboard - Roomie')

@section('content')
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Welcome Back, Admin! 👋</h2>
            <p class="text-gray-500 mt-1">Here's what's happening with Roomie today.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-gray-500 font-medium">Total Users</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
            <span class="text-green-500 text-sm font-medium">All registered accounts</span>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h3 class="text-gray-500 font-medium">Total Properties</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_properties']) }}</p>
            <span class="text-gray-500 text-sm font-medium">Listed properties</span>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-gray-500 font-medium">Pending Verifications</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_verifications']) }}</p>
            <span class="text-orange-500 text-sm font-medium">Users awaiting verification</span>
        </div>
    </div>

    <!-- Content Placeholder -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-96 flex items-center justify-center">
        <p class="text-gray-400">Select an item from the sidebar to verify data.</p>
    </div>
@endsection
