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
                <span class="qp-notif-badge" id="qpNotifBadge" style="display:none;">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up qp-notif-menu" aria-labelledby="navbarDropdownNotifications">
                <div class="qp-notif-header">
                    <span>Notifications</span>
                    <button type="button" class="qp-notif-markall" id="qpNotifMarkAll" style="display:none;">Mark all read</button>
                    <span class="qp-notif-count-pill" id="qpNotifCountPill" style="display:none;">0 new</span>
                </div>

                <div class="qp-notif-list" id="qpNotifList">
                    <div class="qp-notif-empty">Loading...</div>
                </div>

                <a href="<?php echo base_url() . 'Welcome/Dashboard'; ?>" class="qp-notif-footer">View dashboard</a>
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

// ---- live notification feed (with click-to-read + mark all read) ----
(function () {
    var listEl      = document.getElementById('qpNotifList');
    var badgeEl     = document.getElementById('qpNotifBadge');
    var pillEl      = document.getElementById('qpNotifCountPill');
    var markAllBtn  = document.getElementById('qpNotifMarkAll');
    var endpoint    = '<?php echo base_url() . "Welcome/Getnotifications"; ?>';
    var READ_KEY    = 'qp-notif-read-ids';

    function getReadIds() {
        try { return JSON.parse(localStorage.getItem(READ_KEY)) || []; }
        catch (e) { return []; }
    }

    function markRead(id) {
        var ids = getReadIds();
        if (ids.indexOf(id) === -1) {
            ids.push(id);
            if (ids.length > 200) ids = ids.slice(-200); // cap growth
            localStorage.setItem(READ_KEY, JSON.stringify(ids));
        }
    }

    function timeAgo(rawTime) {
        var diffSec = Math.floor((Date.now() - new Date(rawTime.replace(' ', 'T')).getTime()) / 1000);
        if (diffSec < 60) return 'Just now';
        if (diffSec < 3600) return Math.floor(diffSec / 60) + ' min ago';
        if (diffSec < 86400) return Math.floor(diffSec / 3600) + ' hour' + (Math.floor(diffSec / 3600) > 1 ? 's' : '') + ' ago';
        if (diffSec < 172800) return 'Yesterday';
        return Math.floor(diffSec / 86400) + ' days ago';
    }

    function iconClass(type) {
        return { warning: 'qp-notif-icon-warning', danger: 'qp-notif-icon-danger',
                 info: 'qp-notif-icon-info', success: 'qp-notif-icon-success' }[type] || 'qp-notif-icon-info';
    }

    function updateCounters(unread) {
        if (unread > 0) {
            badgeEl.textContent = unread > 9 ? '9+' : unread;
            badgeEl.style.display = '';
            pillEl.textContent = unread + ' new';
            pillEl.style.display = '';
            markAllBtn.style.display = '';
        } else {
            badgeEl.style.display = 'none';
            pillEl.style.display = 'none';
            markAllBtn.style.display = 'none';
        }
    }

    function bindItemClicks() {
        listEl.querySelectorAll('.qp-notif-item').forEach(function (el) {
            el.addEventListener('click', function () {
                if (!el.classList.contains('qp-notif-unread')) return; // already read

                var id = el.getAttribute('data-notif-id');
                markRead(id);
                el.classList.remove('qp-notif-unread');
                var dot = el.querySelector('.qp-notif-dot');
                if (dot) dot.remove();

                var currentUnread = parseInt(badgeEl.textContent, 10) || 0;
                updateCounters(Math.max(0, currentUnread - 1));
                // link navigation proceeds normally after this
            });
        });
    }

    function render(data) {
        var notifications = data.notifications || [];
        var readIds = getReadIds();
        var unread = notifications.filter(function (n) { return readIds.indexOf(n.id) === -1; }).length;

        updateCounters(unread);

        if (!notifications.length) {
            listEl.innerHTML = '<div class="qp-notif-empty">You\'re all caught up</div>';
            return;
        }

        listEl.innerHTML = notifications.map(function (n) {
            var isUnread = readIds.indexOf(n.id) === -1;
            return '<a class="qp-notif-item' + (isUnread ? ' qp-notif-unread' : '') + '" href="' + n.link + '" data-notif-id="' + n.id + '">' +
                       '<div class="qp-notif-icon ' + iconClass(n.type) + '"><i data-feather="' + n.icon + '"></i></div>' +
                       '<div class="qp-notif-content">' +
                           '<div class="qp-notif-text">' + n.text + '</div>' +
                           '<div class="qp-notif-time">' + timeAgo(n.raw_time) + '</div>' +
                       '</div>' +
                       (isUnread ? '<span class="qp-notif-dot"></span>' : '') +
                   '</a>';
        }).join('');

        if (typeof feather !== 'undefined') feather.replace();
        bindItemClicks();
    }

    function loadNotifications() {
        fetch(endpoint, { credentials: 'same-origin' })
            .then(function (res) { return res.json(); })
            .then(render)
            .catch(function () {
                listEl.innerHTML = '<div class="qp-notif-empty">Couldn\'t load notifications</div>';
            });
    }

    markAllBtn.addEventListener('click', function () {
        listEl.querySelectorAll('.qp-notif-item.qp-notif-unread').forEach(function (el) {
            markRead(el.getAttribute('data-notif-id'));
            el.classList.remove('qp-notif-unread');
            var dot = el.querySelector('.qp-notif-dot');
            if (dot) dot.remove();
        });
        updateCounters(0);
    });

    loadNotifications();
    setInterval(loadNotifications, 60000); // refresh every minute
})();
</script>