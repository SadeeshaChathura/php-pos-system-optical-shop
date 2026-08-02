<nav class="topnav navbar navbar-expand shadow navbar-light" id="sidenavAccordion">

    <a class="navbar-brand d-flex d-lg-none align-items-center" href="<?php echo base_url() ?>Welcome/Dashboard">
        <img src="<?php echo base_url() ?>images/QP logo transparent.png" class="navbar-brand-logo" alt="Logo">
    </a>

    <!-- single toggle: opens the sidebar on mobile, collapses it on desktop -->
    <button class="btn btn-icon order-1 order-lg-0 mr-lg-3" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
        <i data-feather="chevron-left"></i>
    </button>

    <!-- welcome / company name -->
    <div class="qp-welcome d-none d-md-flex flex-column">
        <span class="qp-welcome-label">Welcome back,</span>
        <span class="qp-welcome-company"><?php echo isset($_SESSION['company']) ? $_SESSION['company'] : ucfirst($_SESSION['name']); ?></span>
    </div>

    <ul class="navbar-nav align-items-center ml-auto">

        <li class="nav-item mr-3 d-none d-md-block">
            <span class="qp-clock-badge">
                <i data-feather="clock"></i>
                <span id="currentDateTime"></span>
            </span>
        </li>

        <!-- Notifications -->
        <li class="nav-item dropdown no-caret mr-2 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark qp-notif-trigger" id="navbarDropdownNotifications" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i data-feather="bell"></i>
                <span class="qp-notif-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up qp-notif-menu" aria-labelledby="navbarDropdownNotifications">
                <div class="qp-notif-header">
                    <span>Notifications</span>
                    <span class="qp-notif-count-pill">3 new</span>
                </div>

                <div class="qp-notif-list">
                    <a class="qp-notif-item qp-notif-unread" href="javascript:void(0);">
                        <div class="qp-notif-icon qp-notif-icon-info"><i data-feather="file-text"></i></div>
                        <div class="qp-notif-content">
                            <div class="qp-notif-text">New invoice <strong>INV-000231</strong> created</div>
                            <div class="qp-notif-time">2 min ago</div>
                        </div>
                        <span class="qp-notif-dot"></span>
                    </a>

                    <a class="qp-notif-item qp-notif-unread" href="javascript:void(0);">
                        <div class="qp-notif-icon qp-notif-icon-warning"><i data-feather="alert-triangle"></i></div>
                        <div class="qp-notif-content">
                            <div class="qp-notif-text">Stock low: <strong>Paracetamol 500mg</strong></div>
                            <div class="qp-notif-time">1 hour ago</div>
                        </div>
                        <span class="qp-notif-dot"></span>
                    </a>

                    <a class="qp-notif-item" href="javascript:void(0);">
                        <div class="qp-notif-icon qp-notif-icon-success"><i data-feather="truck"></i></div>
                        <div class="qp-notif-content">
                            <div class="qp-notif-text">PO <strong>KND/PO-000045</strong> approved</div>
                            <div class="qp-notif-time">Yesterday</div>
                        </div>
                    </a>
                </div>

                <a href="javascript:void(0);" class="qp-notif-footer">View all notifications</a>
            </div>
        </li>

        <!-- Appearance: mode (light/dark/system) + accent color -->
        <li class="nav-item dropdown no-caret mr-2">
            <a class="btn btn-icon btn-transparent-dark qp-theme-trigger" id="navbarDropdownTheme" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- STABLE wrapper — Feather replaces the <i> inside it with an <svg>,
                     but this outer <span> (and its id) never gets touched/replaced,
                     so JS can always safely find it and rebuild the icon inside. -->
                <span id="qpThemeTriggerIcon"><i data-feather="droplet"></i></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up qp-theme-menu" aria-labelledby="navbarDropdownTheme">

                <div class="qp-theme-menu-title">Mode</div>
                <div class="qp-mode-switch" id="qpModeSwitch">
                    <button type="button" class="qp-mode-btn" data-mode-value="light">
                        <i data-feather="sun"></i>
                        <span>Light</span>
                    </button>
                    <button type="button" class="qp-mode-btn" data-mode-value="dark">
                        <i data-feather="moon"></i>
                        <span>Dark</span>
                    </button>
                    <button type="button" class="qp-mode-btn" data-mode-value="system">
                        <i data-feather="monitor"></i>
                        <span>System</span>
                    </button>
                </div>

                <div class="qp-theme-menu-title">Color theme</div>
                <div class="qp-theme-swatches" id="qpThemeSwatches">
                    <button type="button" class="qp-theme-swatch" data-theme-value="blue"   title="Blue"></button>
                    <button type="button" class="qp-theme-swatch" data-theme-value="indigo" title="Indigo"></button>
                    <button type="button" class="qp-theme-swatch" data-theme-value="teal"   title="Teal"></button>
                    <button type="button" class="qp-theme-swatch" data-theme-value="rose"   title="Rose"></button>
                    <button type="button" class="qp-theme-swatch" data-theme-value="amber"  title="Amber"></button>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown no-caret dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle qp-user-trigger" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?php echo base_url() ?>images/user7.png" alt="User avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php echo base_url() ?>images/user7.png" alt="User avatar" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo ucfirst($_SESSION['name']); ?></div>
                        <div class="text-light"><?php echo $_SESSION['typename']; ?></div>
                    </div>
                </h6>

                <!-- Contact Support -->
                <div class="qp-support-section">
                    <div class="qp-support-label">Need help?</div>
                    <a class="dropdown-item qp-support-item" 
                    href="https://wa.me/94702186386?text=<?php echo rawurlencode('Hi, I need support with QuantumPay ERP for ' . (isset($_SESSION['company']) ? $_SESSION['company'] : ucfirst($_SESSION['name']))); ?>" 
                    target="_blank" rel="noopener">
                        <div class="qp-support-icon qp-support-icon-whatsapp"><i class="fab fa-whatsapp"></i></div>
                        <div class="qp-support-text">
                            <span class="qp-support-title">WhatsApp Support</span>
                            <span class="qp-support-sub">Chat with our team</span>
                        </div>
                    </a>
                    <a class="dropdown-item qp-support-item" 
                    href="mailto:info@calcitexit.com?subject=<?php echo rawurlencode('QuantumPay ERP Support Request - ' . (isset($_SESSION['company']) ? $_SESSION['company'] : ucfirst($_SESSION['name']))); ?>" >
                        <div class="qp-support-icon qp-support-icon-email"><i data-feather="mail"></i></div>
                        <div class="qp-support-text">
                            <span class="qp-support-title">Email Support</span>
                            <span class="qp-support-sub">info@calcitexit.com</span>
                        </div>
                    </a>
                </div>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url() ?>Welcome/Logout">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<script>
function updateDateTime() {
    var currentDate = new Date();
    var day = ('0' + currentDate.getDate()).slice(-2);
    var month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var year = currentDate.getFullYear();
    var hours = ('0' + currentDate.getHours()).slice(-2);
    var minutes = ('0' + currentDate.getMinutes()).slice(-2);
    var seconds = ('0' + currentDate.getSeconds()).slice(-2);
    var dateTimeString = year + '-' + month + '-' + day + '  ' + hours + ':' + minutes + ':' + seconds;
    document.getElementById('currentDateTime').textContent = dateTimeString;
}
updateDateTime();
setInterval(updateDateTime, 1000);

// ---- accent color theme switcher ----
(function () {
    var STORAGE_KEY = 'qp-theme';
    var swatches = document.querySelectorAll('.qp-theme-swatch');

    function markActive(theme) {
        swatches.forEach(function (el) {
            el.classList.toggle('active', el.getAttribute('data-theme-value') === theme);
        });
    }
    function applyTheme(theme) {
        if (theme === 'blue') {
            document.documentElement.removeAttribute('data-theme');
        } else {
            document.documentElement.setAttribute('data-theme', theme);
        }
        markActive(theme);
    }

    var saved = localStorage.getItem(STORAGE_KEY) || 'blue';
    applyTheme(saved);

    swatches.forEach(function (el) {
        el.addEventListener('click', function () {
            var theme = el.getAttribute('data-theme-value');
            localStorage.setItem(STORAGE_KEY, theme);
            applyTheme(theme);
        });
    });
})();

// ---- light / dark / system mode switcher ----
(function () {
    var STORAGE_KEY = 'qp-color-mode'; // 'light' | 'dark' | 'system'
    var modeButtons = document.querySelectorAll('.qp-mode-btn');
    var systemMQ = window.matchMedia('(prefers-color-scheme: dark)');

    function resolvedMode(mode) {
        if (mode === 'system') {
            return systemMQ.matches ? 'dark' : 'light';
        }
        return mode;
    }

    function markActive(mode) {
        modeButtons.forEach(function (el) {
            el.classList.toggle('active', el.getAttribute('data-mode-value') === mode);
        });
    }

    // Rebuilds the icon fresh every time, then asks Feather to render
    // whatever [data-feather] elements currently exist. We always look
    // the wrapper up by id at call time — never cache a reference to the
    // icon itself, since Feather detaches/replaces that node on every render.
    function setTriggerIcon(resolved) {
        var wrap = document.getElementById('qpThemeTriggerIcon');
        if (!wrap) return;
        wrap.innerHTML = '<i data-feather="' + (resolved === 'dark' ? 'moon' : 'sun') + '"></i>';
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    function applyMode(mode) {
        var resolved = resolvedMode(mode);
        document.documentElement.setAttribute('data-color-mode', resolved);
        document.documentElement.setAttribute('data-color-mode-pref', mode);
        markActive(mode);
        setTriggerIcon(resolved);
    }

    var savedMode = localStorage.getItem(STORAGE_KEY) || 'system';
    applyMode(savedMode);

    modeButtons.forEach(function (el) {
        el.addEventListener('click', function () {
            var mode = el.getAttribute('data-mode-value');
            localStorage.setItem(STORAGE_KEY, mode);
            applyMode(mode);
        });
    });

    // live-update if OS theme changes while "System" is selected
    systemMQ.addEventListener('change', function () {
        var currentPref = localStorage.getItem(STORAGE_KEY) || 'system';
        if (currentPref === 'system') {
            applyMode('system');
        }
    });
})();
</script>