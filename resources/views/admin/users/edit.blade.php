@extends('layouts.admin')

@section('title', 'Edit User - Roomie Admin')

@section('content')
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Edit User</h2>
            <p class="text-gray-500 mt-1">Update user account information.</p>
        </div>
        
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors shadow-sm">
            Cancel
        </a>
    </header>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address  -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">address (Optional)</label>
                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- City -->
            <div class="mb-6">
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">city</label>
                <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role & Verification -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="is_admin" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="is_admin" id="is_admin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="0" {{ old('is_admin', $user->is_admin) == '0' ? 'selected' : '' }}>Regular User</option>
                        <option value="1" {{ old('is_admin', $user->is_admin) == '1' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
                
                <div>
                    <label for="is_verified" class="block text-sm font-medium text-gray-700 mb-2">Verification Status</label>
                    <select name="is_verified" id="is_verified" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="1" {{ old('is_verified', $user->is_verified) == '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ old('is_verified', $user->is_verified) == '0' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-500/20">
                    Update Account
                </button>
            </div>
        </form>
    </div>
@endsection
