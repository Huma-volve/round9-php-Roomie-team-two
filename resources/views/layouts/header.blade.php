<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
<div class="d-flex align-items-center flex-stack flex-grow-1">
    <div class="app-header-logo d-flex align-items-center flex-stack px-lg-11 mb-2" id="kt_app_header_logo">
        <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
            <i class="ki-duotone ki-abstract-14 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <a href="#" class="app-sidebar-logo">
            <img alt="Logo" src="{{ asset('assets/images/ORG-1024x844.png') }}" class="h-35px theme-light-show" />
            <img alt="Logo" src="{{ asset('assets/images/ORG-1024x844.png') }}" class="h-35px theme-dark-show" />
        </a>
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-sm btn-icon btn-color-warning me-n2 d-none d-lg-flex" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-exit-left fs-2x rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>
    
    <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
        <!-- Notifications -->
        <div class="app-navbar-item me-lg-1">
            <!--begin::Menu wrapper-->
            <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px position-relative" 
                 data-kt-menu-trigger="{default: 'click', lg: 'hover'}" 
                 data-kt-menu-attach="parent" 
                 data-kt-menu-placement="bottom-end"
                 id="notifications-toggle">
                <i class="ki-duotone ki-notification-on fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>
                <!-- Badge للعدد -->
                <span id="unread-count" class="badge badge-circle badge-danger position-absolute top-0 end-0" style="display: none;">0</span>
            </div>

            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
                <!--begin::Heading-->
                <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset('assets/media/misc/menu-header-bg.jpg') }}')">
                    <!--begin::Title-->
                    <h3 class="text-black fw-semibold px-9 mt-10 mb-6">
                        Notifications
                        <span class="fs-8 opacity-75 ps-3" id="total-notifications">0 notifications</span>
                    </h3>
                    <!--end::Title-->
                    
                    <!--begin::Tabs-->
                    <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                        <li class="nav-item">
                            <a class="nav-link text-black opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_all">
                                All
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-black opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_unread">
                                Unread (<span id="unread-tab-count">0</span>)
                            </a>
                        </li>
                    </ul>
                    <!--end::Tabs-->
                </div>
                <!--end::Heading-->

                <!--begin::Tab content-->
                <div class="tab-content">
                    <!--begin::Tab panel - All-->
                    <div class="tab-pane fade show active" id="kt_topbar_notifications_all" role="tabpanel">
                        <!--begin::Items-->
                        <div class="scroll-y mh-325px my-5 px-8" id="notifications-list-all">
                            <!-- Loading State -->
                            <div class="text-center py-10">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted mt-3">Loading notifications...</p>
                            </div>
                        </div>
                        <!--end::Items-->
                        
                        <!--begin::Actions-->
                        <div class="py-3 text-center border-top">
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-color-gray-600 btn-active-color-primary">
                                View All
                                <i class="ki-duotone ki-arrow-right fs-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Tab panel-->

                    <!--begin::Tab panel - Unread-->
                    <div class="tab-pane fade" id="kt_topbar_notifications_unread" role="tabpanel">
                        <!--begin::Items-->
                        <div class="scroll-y mh-325px my-5 px-8" id="notifications-list-unread">
                            <!-- Loading State -->
                            <div class="text-center py-10">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <!--end::Items-->
                        
                        <!--begin::Actions-->
                        <div class="py-3 text-center border-top" id="mark-all-container" style="display: none;">
                            <button class="btn btn-sm btn-light-primary" onclick="markAllAsReadFromDropdown()">
                                <i class="ki-duotone ki-check-circle fs-5 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Mark All as Read
                            </button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Tab panel-->
                </div>
                <!--end::Tab content-->
            </div>
            <!--end::Menu-->
        </div>

        <!-- User Menu -->
        <div class="app-navbar-item ms-3 ms-lg-4 me-lg-2" id="kt_header_user_menu_toggle">
            <div class="cursor-pointer symbol symbol-30px symbol-lg-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                <img src="{{ asset('assets/images/light.jpg') }}" alt="user" class="theme-light-show" id="navbarProfileImageLight"/>
                <img src="{{ asset('assets/images/dark.webp') }}" alt="user" class="theme-dark-show" id="navbarProfileImageDark" />
            </div>
            
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                <div class="menu-item px-3">
                    <div class="menu-content d-flex align-items-center px-3">
                        <div class="symbol symbol-50px me-5">
                            <img src="{{ asset('assets/images/light.jpg') }}" alt="user" class="theme-light-show" />
                            <img src="{{ asset('assets/images/dark.webp') }}" alt="user" class="theme-dark-show" />
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fw-bold d-flex align-items-center fs-5">
                                {{ auth()->user()->first_name ?? 'Admin' }} {{ auth()->user()->last_name ?? '' }}
                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                    Admin
                                </span>
                            </div>
                            <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                {{ auth()->user()->email ?? 'admin@example.com' }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="separator my-2"></div>
                
                <div class="menu-item px-5">
                    <a href="{{ route('admin.notifications.index') }}" class="menu-link px-5">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-notification fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Notifications</span>
                        @if(auth()->check())
                        <span class="badge badge-light-success" id="user-menu-badge">0</span>
                        @endif
                    </a>
                </div>
                
                <div class="separator my-2"></div>
                
                <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                    <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">Mode
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="ki-duotone ki-moon theme-dark-show fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </span>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-sun fs-2"></i>
                                </span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-moon fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-screen fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">System</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="menu-item px-5">
                    <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                        @csrf
                        <a class="menu-link px-5" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-exit-right fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="app-header-separator"></div>
</div>
<style>
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--bs-app-bg-color);
}

.menu-sub-dropdown {
    z-index: 1050 !important;
}

.menu-link {
    display: flex;
    align-items: center;
}

.menu-icon {
    margin-right: 10px;
}

[data-bs-theme="dark"] .theme-light-show,
.theme-light-show[data-bs-theme="dark"] {
    display: none !important;
}

[data-bs-theme="light"] .theme-dark-show,
.theme-dark-show[data-bs-theme="light"] {
    display: none !important;
}

/* Notification Styles */
.notification-item {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #f1f1f2;
    background-color: #fff;
}

[data-bs-theme="dark"] .notification-item {
    background-color: #1e1e2d;
    border-color: #2b2b40;
}

.notification-item:hover {
    background-color: #f9f9f9;
    transform: translateX(-3px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

[data-bs-theme="dark"] .notification-item:hover {
    background-color: #2b2b40;
}

.notification-item.unread {
    background-color: #f0f8ff;
    border-left: 3px solid #3699ff;
}

[data-bs-theme="dark"] .notification-item.unread {
    background-color: #1a2332;
    border-left: 3px solid #3699ff;
}

.notification-item.read {
    opacity: 0.65;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.badge-circle {
    min-width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
}

/* Pulse Animation for New Notifications */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

.pulse-animation {
    animation: pulse 2s infinite;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state i {
    opacity: 0.5;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Notifications system initializing...');
    
    // Load notifications immediately
    loadNotifications();
    
    // Refresh every 30 seconds
    setInterval(loadNotifications, 30000);
});

/**
 * Load notifications from server
 */
function loadNotifications() {
    console.log('📡 Loading notifications...');
    
    fetch('{{ route("admin.notifications.recent") }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Notifications loaded:', data);
        updateNotificationUI(data.notifications || []);
        updateUnreadCount(data.unread_count || 0);
    })
    .catch(error => {
        console.error('❌ Error loading notifications:', error);
        showErrorState();
    });
}

/**
 * Update notification UI with data
 */
function updateNotificationUI(notifications) {
    const allList = document.getElementById('notifications-list-all');
    const unreadList = document.getElementById('notifications-list-unread');
    const markAllContainer = document.getElementById('mark-all-container');
    
    if (!allList || !unreadList) {
        console.error('❌ Notification containers not found');
        return;
    }
    
    // Clear existing content
    allList.innerHTML = '';
    unreadList.innerHTML = '';
    
    if (!notifications || notifications.length === 0) {
        allList.innerHTML = getEmptyState('all');
        unreadList.innerHTML = getEmptyState('unread');
        markAllContainer.style.display = 'none';
        return;
    }
    
    let hasUnread = false;
    
    notifications.forEach(notification => {
        const html = createNotificationHTML(notification);
        allList.insertAdjacentHTML('beforeend', html);
        
        if (!notification.is_read) {
            unreadList.insertAdjacentHTML('beforeend', html);
            hasUnread = true;
        }
    });
    
    if (!hasUnread) {
        unreadList.innerHTML = getEmptyState('unread');
        markAllContainer.style.display = 'none';
    } else {
        markAllContainer.style.display = 'block';
    }
}

/**
 * Create HTML for a single notification
 */
function createNotificationHTML(notification) {
    const typeConfig = {
        'new_booking': { icon: 'calendar', color: 'danger', bg: 'light-danger' },
        'booking_cancellation': { icon: 'cross-circle', color: 'warning', bg: 'light-warning' },
        'upcoming_booking': { icon: 'notification-bing', color: 'info', bg: 'light-info' },
        'new_review': { icon: 'star', color: 'warning', bg: 'light-warning' },
        'new_contact_message': { icon: 'message-text', color: 'info', bg: 'light-info' },
        'new_chat_message': { icon: 'messages', color: 'success', bg: 'light-success' },
        'payment_received': { icon: 'wallet', color: 'primary', bg: 'light-primary' }
    };
    
    const config = typeConfig[notification.type] || { icon: 'notification', color: 'secondary', bg: 'light-secondary' };
    
    return `
        <div class="notification-item ${notification.is_read ? 'read' : 'unread'} d-flex align-items-start" 
             data-id="${notification.id}" 
             onclick="markNotificationAsRead(${notification.id}, this)">
            <div class="notification-icon bg-${config.bg} me-3">
                <i class="ki-duotone ki-${config.icon} fs-2 text-${config.color}">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-gray-800 fs-6">${escapeHtml(notification.title || 'Notification')}</div>
                <div class="text-gray-600 fs-7 mt-1">${escapeHtml(notification.message || '')}</div>
                <div class="text-gray-400 fs-8 mt-1">${notification.created_at || 'Just now'}</div>
            </div>
            ${!notification.is_read ? '<div class="badge badge-sm badge-circle badge-primary ms-2">●</div>' : ''}
        </div>
    `;
}

/**
 * Get empty state HTML
 */
function getEmptyState(type) {
    if (type === 'unread') {
        return `
            <div class="empty-state">
                <i class="ki-duotone ki-check-circle fs-5x text-success">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <p class="text-muted mt-3 mb-0">No unread notifications</p>
            </div>
        `;
    }
    
    return `
        <div class="empty-state">
            <i class="ki-duotone ki-notification fs-5x text-muted">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <p class="text-muted mt-3 mb-0">No notifications yet</p>
        </div>
    `;
}

/**
 * Show error state
 */
function showErrorState() {
    const allList = document.getElementById('notifications-list-all');
    const unreadList = document.getElementById('notifications-list-unread');
    
    const errorHtml = `
        <div class="text-center py-10">
            <i class="ki-duotone ki-cross-circle fs-5x text-danger">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <p class="text-muted mt-3">Failed to load notifications</p>
            <button class="btn btn-sm btn-light-primary mt-2" onclick="loadNotifications()">
                Try Again
            </button>
        </div>
    `;
    
    if (allList) allList.innerHTML = errorHtml;
    if (unreadList) unreadList.innerHTML = errorHtml;
}

/**
 * Update unread count badge
 */
function updateUnreadCount(count) {
    const badge = document.getElementById('unread-count');
    const totalSpan = document.getElementById('total-notifications');
    const unreadTabCount = document.getElementById('unread-tab-count');
    const userMenuBadge = document.getElementById('user-menu-badge');
    
    console.log('📊 Updating count to:', count);
    
    if (count > 0) {
        if (badge) {
            badge.style.display = 'flex';
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.add('pulse-animation');
        }
        if (totalSpan) totalSpan.textContent = count + ' unread';
        if (unreadTabCount) unreadTabCount.textContent = count;
        if (userMenuBadge) {
            userMenuBadge.textContent = count > 99 ? '99+' : count;
            userMenuBadge.style.display = 'inline-block';
        }
    } else {
        if (badge) badge.style.display = 'none';
        if (totalSpan) totalSpan.textContent = '0 notifications';
        if (unreadTabCount) unreadTabCount.textContent = '0';
        if (userMenuBadge) userMenuBadge.style.display = 'none';
    }
}

/**
 * Mark single notification as read
 */
window.markNotificationAsRead = function(notificationId, element) {
    if (!notificationId) return;
    
    console.log('✅ Marking notification as read:', notificationId);
    
    fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            if (element) {
                element.classList.remove('unread');
                element.classList.add('read');
                
                const badge = element.querySelector('.badge-circle');
                if (badge) badge.remove();
            }
            
            // Update count
            const currentCount = parseInt(document.getElementById('unread-count')?.textContent || 0);
            updateUnreadCount(Math.max(0, currentCount - 1));
            
            // Reload after a short delay
            setTimeout(loadNotifications, 500);
        }
    })
    .catch(error => {
        console.error('❌ Error marking as read:', error);
    });
};

/**
 * Mark all notifications as read
 */
window.markAllAsReadFromDropdown = function() {
    console.log('✅ Marking all as read...');
    
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
        if (data.success) {
            // Update all notification items
            document.querySelectorAll('.notification-item.unread').forEach(el => {
                el.classList.remove('unread');
                el.classList.add('read');
            });
            
            updateUnreadCount(0);
            
            // Show success message
            showToast('All notifications marked as read', 'success');
            
            // Reload notifications
            setTimeout(loadNotifications, 500);
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        showToast('Failed to mark all as read', 'danger');
    });
};

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Check if toastr is available
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
        return;
    }
    
    // Fallback to simple toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.style.minWidth = '250px';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>