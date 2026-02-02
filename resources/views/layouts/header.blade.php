<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <!--begin::Header main-->
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
            
            <div class="app-navbar-item ms-3 ms-lg-4 me-lg-2" id="kt_header_user_menu_toggle">
                <div class="cursor-pointer symbol symbol-30px symbol-lg-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <!-- الصورة الافتراضية بناءً على الـ Theme -->
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
                                    Admin
                                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                        admin
                                    </span>
                                </div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                    Hello@gmail.com
                                </a>
                            </div>
                        </div>
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
                        <a class="menu-link px-5" href="#" onclick="event.preventDefault(); alert('تسجيل الخروج');">
                            {{ __('تسجيل خروج') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="app-header-separator"></div>
</div>

<style>
body {
    font-family: 'Cairo', sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--bs-app-bg-color);
}

/* ضبط مظهر القائمة المنسدلة */
.menu-sub-dropdown {
    z-index: 1050 !important;
}

/* تحسين مظهر الأيقونات والنصوص */
.menu-link {
    display: flex;
    align-items: center;
}

.menu-icon {
    margin-right: 10px;
}

/* إخفاء الصور حسب الـ Theme */
[data-bs-theme="dark"] .theme-light-show,
.theme-light-show[data-bs-theme="dark"] {
    display: none !important;
}

[data-bs-theme="light"] .theme-dark-show,
.theme-dark-show[data-bs-theme="light"] {
    display: none !important;
}
</style>