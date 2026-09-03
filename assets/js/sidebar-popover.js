/* ==========================================================================
   SIDEBAR SUBMENU FLYOUT (HOVER)
   --------------------------------------------------------------------------
   Load this AFTER jQuery and Bootstrap's JS.

   Behaviour:
   - Sidebar EXPANDED (no .sidenav-collapsed): does nothing — grouped links
     behave like a normal Bootstrap accordion (click to expand inline).
   - Sidebar COLLAPSED (.sidenav-collapsed) AND viewport >= 992px: hovering
     a grouped icon (Point of Sale, Purchasing, Products, etc.) opens its
     submenu as a small floating panel to the right of the icon. Moving the
     mouse into the panel keeps it open; moving away closes it after a
     short delay (so crossing the small gap between icon and panel doesn't
     close it prematurely).
   - Below 992px the sidebar always shows full labels, so this script
     doesn't engage — the CSS's mobile override takes care of that.

   Note: this does NOT hijack clicks, so it never fights with Bootstrap's
   own data-toggle="collapse" handler.
   ========================================================================== */
(function ($) {
    'use strict';

    var DESKTOP_MIN_WIDTH = 992;
    var OPEN_DELAY = 80;   // ms — small delay avoids flicker when just passing over icons
    var CLOSE_DELAY = 250; // ms — enough time to move the mouse into the flyout
    var GAP = 12;            // px between the rail and the flyout (room for the comment-box tail)

    var openTimer = null;
    var closeTimer = null;

    function isDesktop() {
        return window.innerWidth >= DESKTOP_MIN_WIDTH;
    }

    function isSidebarCollapsed() {
        return $('.sidenav').hasClass('sidenav-collapsed');
    }

    function active() {
        return isDesktop() && isSidebarCollapsed();
    }

    function ensureHeader($item, $collapse) {
        if ($collapse.find('> .qp-popover-header').length > 0) {
            return;
        }
        var title = $item.attr('data-flyout-title')
            || $item.find('> a .nav-link-text').first().text()
            || '';
        var $header = $('<div class="qp-popover-header"><span></span></div>');
        $header.find('span').text(title);
        $collapse.prepend($header);
    }

    function positionFlyout($item, $collapse) {
        var rect = $item[0].getBoundingClientRect();
        var top = rect.top;
        var left = rect.right + GAP;

        var h = $collapse.outerHeight() || 0;
        var vh = window.innerHeight;
        if (top + h > vh - 12) {
            top = Math.max(12, vh - h - 12);
        }

        $collapse.css({ top: top + 'px', left: left + 'px' });
    }

    function closeItem($item) {
        $item.removeClass('qp-popover-open');
        $item.find('> .collapse').removeClass('show');
    }

    function closeAll(exceptItem) {
        $('.sidenav-item.qp-popover-open').each(function () {
            if (exceptItem && this === exceptItem[0]) {
                return;
            }
            closeItem($(this));
        });
    }

    function openItem($item) {
        var $collapse = $item.find('> .collapse');
        if ($collapse.length === 0) {
            return; // no submenu on this item, nothing to flyout
        }
        closeAll($item);
        ensureHeader($item, $collapse);
        $item.addClass('qp-popover-open');
        $collapse.addClass('show');
        positionFlyout($item, $collapse);
    }

    function scheduleOpen($item) {
        clearTimeout(closeTimer);
        clearTimeout(openTimer);
        openTimer = setTimeout(function () {
            openItem($item);
        }, OPEN_DELAY);
    }

    function scheduleClose($item) {
        clearTimeout(openTimer);
        clearTimeout(closeTimer);
        closeTimer = setTimeout(function () {
            closeItem($item);
        }, CLOSE_DELAY);
    }

    // Hovering a grouped sidenav item (icon row) opens its flyout.
    // Note: even though the flyout renders at a fixed screen position far
    // to the right, it's still a DOM child of .sidenav-item, so hovering
    // over the flyout itself keeps firing mouseenter on this same item —
    // no extra wiring needed to "stay open while hovering the panel".
    $(document).on('mouseenter', '.sidenav-menu .sidenav-item', function () {
        if (!active()) {
            return;
        }
        var $item = $(this);
        if ($item.find('> .collapse').length === 0) {
            return;
        }
        scheduleOpen($item);
    });

    $(document).on('mouseleave', '.sidenav-menu .sidenav-item', function () {
        if (!active()) {
            return;
        }
        clearTimeout(openTimer);
        scheduleClose($(this));
    });

    // Clicking a real link inside an open flyout should close it right away
    // (otherwise it can flash open again briefly on the next page load).
    $(document).on('click', '.sidenav-item.qp-popover-open .sidenav-menu-nested .nav-link', function () {
        closeAll();
    });

    // Reposition an open flyout on resize/scroll
    $(window).on('scroll resize', function () {
        $('.sidenav-item.qp-popover-open').each(function () {
            var $item = $(this);
            positionFlyout($item, $item.find('> .collapse'));
        });
    });

    // Drop any open flyout if we leave collapsed/desktop mode
    $(window).on('resize', function () {
        if (!active()) {
            closeAll();
        }
    });
    $(document).on('click', '#sidebarToggle', function () {
        setTimeout(closeAll, 0);
    });

})(jQuery);