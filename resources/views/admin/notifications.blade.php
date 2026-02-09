@extends('layouts.master')
@section('title')
Notifications
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
    .notification-item {
        transition: all 0.3s ease;
    }
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .unread-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* ✅ ألوان أغمق للإشعارات غير المقروءة */
    .notification-item.bg-light-primary {
        background-color: #e1f0ff !important;
        border-right: 4px solid #3699ff;
    }
    
    [data-bs-theme="dark"] .notification-item.bg-light-primary {
        background-color: #1e3a5f !important;
        border-right: 4px solid #3699ff;
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
        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">NOTIFICATIONS</li>
    </ul>
</div>
@endsection
@section('content')
<div id="kt_app_content_container" class="app-container container-fluid">
    <!-- Header Section -->
<div class="card mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">All Notifications</span>
            <span class="text-muted mt-1 fw-semibold fs-7">{{ $notifications->total() }} total notifications</span>
        </h3>
        <div class="card-toolbar">
            @if($unreadCount > 0)
            <button type="button" onclick="markAllAsRead()" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-check-circle fs-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Mark All as Read ({{ $unreadCount }})
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card mb-5">
    <div class="card-body p-5">
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'all' ? 'active' : '' }}" 
                   href="{{ route('admin.notifications.index', ['filter' => 'all']) }}">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'unread' ? 'active' : '' }}" 
                   href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}">
                    Unread
                    @if($unreadCount > 0)
                    <span class="badge badge-circle badge-primary ms-2">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'read' ? 'active' : '' }}" 
                   href="{{ route('admin.notifications.index', ['filter' => 'read']) }}">
                    Read
                </a>
            </li>
        </ul>

        <!-- Type Filters -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.notifications.index') }}" 
               class="btn btn-sm {{ !$type ? 'btn-primary' : 'btn-light-primary' }}">
                All Types
            </a>
            <a href="{{ route('admin.notifications.index', ['type' => 'new_booking']) }}" 
               class="btn btn-sm {{ $type == 'new_booking' ? 'btn-danger' : 'btn-light-danger' }}">
                <i class="ki-duotone ki-calendar fs-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Bookings
            </a>
            <a href="{{ route('admin.notifications.index', ['type' => 'booking_cancellation']) }}" 
               class="btn btn-sm {{ $type == 'booking_cancellation' ? 'btn-warning' : 'btn-light-warning' }}">
                <i class="ki-duotone ki-cross-circle fs-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Cancellations
            </a>
            <a href="{{ route('admin.notifications.index', ['type' => 'new_review']) }}" 
               class="btn btn-sm {{ $type == 'new_review' ? 'btn-warning' : 'btn-light-warning' }}">
                <i class="ki-duotone ki-star fs-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Reviews
            </a>
            <a href="{{ route('admin.notifications.index', ['type' => 'new_contact_message']) }}" 
               class="btn btn-sm {{ $type == 'new_contact_message' ? 'btn-info' : 'btn-light-info' }}">
                <i class="ki-duotone ki-message-text fs-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Messages
            </a>
            <a href="{{ route('admin.notifications.index', ['type' => 'new_chat_message']) }}" 
               class="btn btn-sm {{ $type == 'new_chat_message' ? 'btn-success' : 'btn-light-success' }}">
                <i class="ki-duotone ki-messages fs-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Chats
            </a>
        </div>
    </div>
</div>

<!-- Notifications List -->
@if($notifications->count() > 0)
    @foreach($notifications as $notification)
    <div class="card mb-4 notification-item {{ !$notification->is_read ? 'bg-light-primary' : '' }}" 
         id="notification-{{ $notification->id }}">
        <div class="card-body d-flex align-items-center py-5">
            <!-- Icon -->
            <div class="symbol symbol-50px me-5">
                @php
                    $typeConfig = [
                        'new_booking' => ['color' => 'danger', 'icon' => 'calendar'],
                        'booking_cancellation' => ['color' => 'warning', 'icon' => 'cross-circle'],
                        'upcoming_booking' => ['color' => 'info', 'icon' => 'notification-bing'],
                        'new_review' => ['color' => 'warning', 'icon' => 'star'],
                        'new_contact_message' => ['color' => 'info', 'icon' => 'message-text'],
                        'new_chat_message' => ['color' => 'success', 'icon' => 'messages'],
                        'payment_received' => ['color' => 'primary', 'icon' => 'wallet'],
                    ];
                    $config = $typeConfig[$notification->type] ?? ['color' => 'secondary', 'icon' => 'notification'];
                @endphp
                <span class="symbol-label bg-light-{{ $config['color'] }}">
                    <i class="ki-duotone ki-{{ $config['icon'] }} fs-2x text-{{ $config['color'] }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
            </div>

            <!-- Content -->
            <div class="flex-grow-1 me-3">
                <div class="d-flex align-items-center mb-2">
                    <span class="fw-bold text-gray-800 fs-5 me-3">{{ $notification->title }}</span>
                    @if(!$notification->is_read)
                    <span class="badge badge-primary badge-sm unread-badge">New</span>
                    @endif
                    @if($notification->priority)
                    <span class="badge badge-sm ms-2
                        @if($notification->priority == 'high' || $notification->priority == 'urgent') badge-danger
                        @elseif($notification->priority == 'medium') badge-warning
                        @else badge-info
                        @endif">
                        {{ ucfirst($notification->priority) }}
                    </span>
                    @endif
                </div>
                <p class="text-gray-600 fw-semibold fs-6 mb-2">{{ $notification->message }}</p>
                
                @if($notification->data)
                    @if(isset($notification->data['user_name']))
                    <span class="text-muted fw-semibold fs-7">
                        By: <span class="text-gray-700">{{ $notification->data['user_name'] }}</span>
                    </span>
                    @endif
                    
                    @if(isset($notification->data['check_in']) && isset($notification->data['check_out']))
                    <div class="text-muted fw-semibold fs-7 mt-1">
                        <i class="ki-duotone ki-calendar-tick fs-6 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        {{ $notification->data['check_in'] }} → {{ $notification->data['check_out'] }}
                    </div>
                    @endif
                    
                    @if(isset($notification->data['total_price']))
                    <div class="text-muted fw-semibold fs-7 mt-1">
                        <i class="ki-duotone ki-dollar fs-6 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Total: ${{ number_format($notification->data['total_price'], 2) }}
                    </div>
                    @endif
                    
                    @if(isset($notification->data['rating']))
                    <div class="text-muted fw-semibold fs-7 mt-1">
                        <i class="ki-duotone ki-star fs-6 me-1 text-warning">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Rating: {{ $notification->data['rating'] }}/5
                    </div>
                    @endif
                @endif
            </div>

            <!-- Time & Actions -->
            <div class="text-end">
                <div class="text-muted fw-semibold fs-7 mb-3">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
                <div class="d-flex gap-2">
                    @if($notification->action_url)
                    <a href="{{ $notification->action_url }}" 
                       class="btn btn-icon btn-sm btn-light-primary" 
                       data-bs-toggle="tooltip" 
                       title="View Details">
                        <i class="ki-duotone ki-eye fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                    @endif
                    
                    @if(!$notification->is_read)
                    <button onclick="markAsRead({{ $notification->id }}, this)" 
                            class="btn btn-icon btn-sm btn-light-success mark-read-btn" 
                            data-bs-toggle="tooltip" 
                            title="Mark as read">
                        <i class="ki-duotone ki-check fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                    @endif
                    
                    <button onclick="deleteNotification({{ $notification->id }}, this)" 
                            class="btn btn-icon btn-sm btn-light-danger" 
                            data-bs-toggle="tooltip" 
                            title="Delete">
                        <i class="ki-duotone ki-trash fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="card">
        <div class="card-body text-center py-20">
            <div class="mb-10">
                <i class="ki-duotone ki-notification-bing fs-10x text-gray-400">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </div>
            <h3 class="fw-bold text-gray-800 mb-5">No Notifications Found</h3>
            <p class="text-gray-600 fs-5">
                @if($filter == 'unread')
                    You have no unread notifications. Great job staying on top of things!
                @elseif($filter == 'read')
                    No read notifications to display.
                @else
                    You're all caught up! Notifications will appear here.
                @endif
            </p>
        </div>
    </div>
@endif
</div>
@endsection
@section('javascript')
<script>
    // ✅ Mark single notification as read
    function markAsRead(id, buttonElement) {
        console.log('Marking notification as read:', id);
        
        // Disable button to prevent double clicks
        if(buttonElement) {
            buttonElement.disabled = true;
            buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        }
        
        fetch(`/admin/notifications/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if(data.success) {
                // Update UI without reload
                const card = document.getElementById(`notification-${id}`);
                if(card) {
                    card.classList.remove('bg-light-primary');
                    
                    // Remove "New" badge
                    const badge = card.querySelector('.unread-badge');
                    if(badge) badge.remove();
                    
                    // Remove mark as read button
                    if(buttonElement) buttonElement.remove();
                }
                
                toastr.success(data.message);
            } else {
                toastr.error('Failed to mark as read');
                if(buttonElement) {
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = '<i class="ki-duotone ki-check fs-3"><span class="path1"></span><span class="path2"></span></i>';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error marking notification as read');
            if(buttonElement) {
                buttonElement.disabled = false;
                buttonElement.innerHTML = '<i class="ki-duotone ki-check fs-3"><span class="path1"></span><span class="path2"></span></i>';
            }
        });
    }

    // ✅ Mark all notifications as read
    function markAllAsRead() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Mark all notifications as read?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark all!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/admin/notifications/mark-all-as-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Mark all response:', data);
                    
                    if(data.success) {
                        toastr.success(data.message);
                        // Reload after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error marking all as read');
                });
            }
        });
    }

    // ✅ Delete notification
    function deleteNotification(id, buttonElement) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading on button
                if(buttonElement) {
                    buttonElement.disabled = true;
                    buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                }
                
                fetch(`/admin/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Delete response:', data);
                    
                    if(data.success) {
                        const element = document.getElementById(`notification-${id}`);
                        if(element) {
                            // Fade out animation
                            element.style.transition = 'opacity 0.3s';
                            element.style.opacity = '0';
                            setTimeout(() => {
                                element.remove();
                            }, 300);
                        }
                        toastr.success(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error deleting notification');
                    if(buttonElement) {
                        buttonElement.disabled = false;
                        buttonElement.innerHTML = '<i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
                    }
                });
            }
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
