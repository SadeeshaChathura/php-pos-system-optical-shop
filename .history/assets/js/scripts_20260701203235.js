/*!
    * Start Bootstrap - SB Admin Pro v1.1.2 (https://shop.startbootstrap.com/product/sb-admin-pro)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under SEE_LICENSE (https://github.com/BlackrockDigital/sb-admin-pro/blob/master/LICENSE)
    */
    (function($) {
  "use strict";

  // Enable Bootstrap tooltips via data-attributes globally
  $('[data-toggle="tooltip"]').tooltip();

  // Enable Bootstrap popovers via data-attributes globally
  $('[data-toggle="popover"]').popover();

  $(".popover-dismiss").popover({
    trigger: "focus"
  });

  function initCollapsedMenuTooltips() {
    var collapsed = $("body").hasClass("sidenav-toggled") || $("#layoutSidenav_nav .sidenav").hasClass("sidenav-collapsed");
    $("#layoutSidenav_nav .sidenav-menu .nav-link[data-title]").each(function() {
      var $link = $(this);
      var title = $link.attr("data-title");
      if (collapsed && title) {
        $link.attr("title", title);
        $link.attr("data-toggle", "tooltip");
        $link.attr("data-placement", "right");
        if (!$link.data('bs.tooltip')) {
          $link.tooltip({
            trigger: "hover",
            placement: "right"
          });
        }
      } else {
        if ($link.data('bs.tooltip')) {
          $link.tooltip('dispose');
        }
        $link.removeAttr("title data-toggle data-placement");
      }
    });
  }

  // Add active state to sidbar nav links
  var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
  $("#layoutSidenav_nav .sidenav a.nav-link").each(function() {
    if (this.href === path) {
      $(this).addClass("active");
    }
  });

  // Toggle the side navigation
  $("#sidebarToggle").on("click", function(e) {
    e.preventDefault();
    $("body").toggleClass("sidenav-toggled");
    $("#layoutSidenav_nav .sidenav").toggleClass("sidenav-collapsed");
    initCollapsedMenuTooltips();

    if ($.fn.dataTable) {
      setTimeout(function() {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
      }, 250);
    }
  });

  // Initialize collapsed state if the page already has sidenav-toggled
  if ($("body").hasClass("sidenav-toggled")) {
    $("#layoutSidenav_nav .sidenav").addClass("sidenav-collapsed");
  }
  initCollapsedMenuTooltips();
  if ($.fn.dataTable) {
    setTimeout(function() {
      $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    }, 250);
  }

  // Activate Feather icons
  feather.replace();

  // Activate Bootstrap scrollspy for the sticky nav component
  $("body").scrollspy({
    target: "#stickyNav",
    offset: 82
  });

  // Scrolls to an offset anchor when a sticky nav link is clicked
  $('.nav-sticky a.nav-link[href*="#"]:not([href="#"])').click(function() {
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
      if (target.length) {
        $("html, body").animate(
          {
            scrollTop: target.offset().top - 81
          },
          200
        );
        return false;
      }
    }
  });

  // Click to collapse responsive sidebar
  $("#layoutSidenav_content").click(function() {
    const BOOTSTRAP_LG_WIDTH = 992;
    if (window.innerWidth >= 992) {
      return;
    }
    if ($("body").hasClass("sidenav-toggled")) {
        $("body").toggleClass("sidenav-toggled");
    }
  });

  // Init sidebar
  let activatedPath = window.location.pathname.match(/([\w-]+\.html)/, '$1');

  if (activatedPath) {
    activatedPath = activatedPath[0];
  }
  else {
    activatedPath = 'index.html';
  }
    
  let targetAnchor = $('[href="' + activatedPath + '"]');
  let collapseAncestors = targetAnchor.parents('.collapse');
  
  targetAnchor.addClass('active');
  
  collapseAncestors.each(function() {
    $(this).addClass('show');
    $('[data-target="#' + this.id +  '"]').removeClass('collapsed');
     
  })

})(jQuery);
