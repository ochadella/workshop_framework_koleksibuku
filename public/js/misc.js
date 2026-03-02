var ChartColor = ["#5D62B4", "#54C3BE", "#EF726F", "#F9C446", "rgb(93.0, 98.0, 180.0)", "#21B7EC", "#04BCCC"];
var primaryColor = getComputedStyle(document.body).getPropertyValue('--primary');
var secondaryColor = getComputedStyle(document.body).getPropertyValue('--secondary');
var successColor = getComputedStyle(document.body).getPropertyValue('--success');
var warningColor = getComputedStyle(document.body).getPropertyValue('--warning');
var dangerColor = getComputedStyle(document.body).getPropertyValue('--danger');
var infoColor = getComputedStyle(document.body).getPropertyValue('--info');
var darkColor = getComputedStyle(document.body).getPropertyValue('--dark');
var lightColor = getComputedStyle(document.body).getPropertyValue('--light');

(function($) {
  'use strict';
  $(function() {
    var body = $('body');
    var sidebar = $('.sidebar');

    // =========================
    // ACTIVE MENU: CLICK ONLY
    // =========================

    var STORAGE_KEY = 'sidebar_active_href';

    function isRealLink(href) {
      return href && href !== '#' && !href.startsWith('javascript:');
    }

    function clearAllActive() {
      // bersihin semua kemungkinan active dari template bawaan
      $('#sidebar .nav-item').removeClass('active');
      $('#sidebar .nav-link').removeClass('active');
      $('#sidebar .collapse').removeClass('show');
    }

    function setActiveByHref(href) {
      if (!isRealLink(href)) return;

      clearAllActive();

      // cari link yang href-nya sama persis
      var $link = $('#sidebar .nav-link[href="' + href.replace(/"/g, '\\"') + '"]');

      // fallback: kalau href di DOM absolute vs relative, coba match pakai pathname
      if (!$link.length) {
        try {
          var targetPath = new URL(href, window.location.origin).pathname.replace(/\/$/, '');
          $('#sidebar .nav-link').each(function() {
            var h = $(this).attr('href');
            if (!isRealLink(h)) return;
            var p = new URL(h, window.location.origin).pathname.replace(/\/$/, '');
            if (p === targetPath) {
              $link = $(this);
              return false; // break
            }
          });
        } catch (e) {}
      }

      if ($link && $link.length) {
        $link.closest('.nav-item').addClass('active');

        // kalau ada sub-menu/collapse, buka yang terkait
        if ($link.parents('.sub-menu').length) {
          $link.closest('.collapse').addClass('show');
          $link.addClass('active');
        }
      }
    }

    // 1) Override hasil template bawaan (kalau ada) setelah DOM ready
    // pakai setTimeout supaya jalan "paling akhir"
    setTimeout(function() {
      clearAllActive();

      // 2) kalau mau persist setelah reload: restore dari localStorage
      var savedHref = localStorage.getItem(STORAGE_KEY);
      if (savedHref) {
        setActiveByHref(savedHref);
      }
    }, 0);

    // 3) set active hanya saat klik
    $(document).on('click', '#sidebar .nav-item .nav-link', function() {
      var href = $(this).attr('href');
      if (!isRealLink(href)) return;

      localStorage.setItem(STORAGE_KEY, href);
      setActiveByHref(href);
      // tidak preventDefault, biar tetap navigasi normal
    });

    // =========================
    // Close other submenu
    // =========================
    sidebar.on('show.bs.collapse', '.collapse', function() {
      sidebar.find('.collapse.show').collapse('hide');
    });

    // =========================
    // applyStyles (as-is)
    // =========================
    applyStyles();

    function applyStyles() {
      if (!body.hasClass("rtl")) {
        if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
          const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
        }
        if ($('.chats').length) {
          const chatsScroll = new PerfectScrollbar('.chats');
        }
        if (body.hasClass("sidebar-fixed")) {
          var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
        }
      }
    }

    $('[data-toggle="minimize"]').on("click", function() {
      if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
        body.toggleClass('sidebar-hidden');
      } else {
        body.toggleClass('sidebar-icon-only');
      }
    });

    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    $("#fullscreen-button").on("click", function toggleFullScreen() {
      if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) ||
          (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) ||
          (document.mozFullScreen !== undefined && !document.mozFullScreen) ||
          (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
          document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
          document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (document.documentElement.msRequestFullscreen) {
          document.documentElement.msRequestFullscreen();
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
      }
    });

  });
})(jQuery);