<header class="bg-white shadow-sm" style="height: var(--header-height);">
    <div class="d-flex align-items-center h-100 px-3">
        <button class="btn btn-link d-md-none" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <!-- Search and Profile -->
        <div class="ms-auto d-flex align-items-center gap-3">
            <div class="dropdown">
                <a class="btn btn-link text-dark" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    Admin User
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#"><i
                                class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
