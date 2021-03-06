
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.gCmsApiBase = window.location.origin + '/cms/api';

var CURRENT_URL = window.location.href.split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer'),
    $NOTIFICATION = $('#menu1');

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? 0 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    $SIDEBAR_MENU.find('a').on('click', function(ev) {
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            $('ul:first', $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').slideUp();
            }

            $li.addClass('active');

            $('ul:first', $li).slideDown(function() {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu
    $MENU_TOGGLE.on('click', function() {
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }

        $BODY.toggleClass('nav-md nav-sm');

        setContentHeight();
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    $(window).smartresize(function(){
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel:{ preventDefault: true }
        });
    }
});
// /Sidebar

// Panel toolbox
$(document).ready(function() {
    $('.collapse-link').on('click', function() {
        var $BOX_PANEL = $(this).closest('.x_panel'),
            $ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content');

        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function(){
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200);
            $BOX_PANEL.css('height', 'auto');
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.x_panel');

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
// /Tooltip

// Progressbar
if ($(".progress .progress-bar")[0]) {
    $('.progress .progress-bar').progressbar();
}
// /Progressbar

// Switchery
$(document).ready(function() {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
});
// /iCheck

// Table
$('table input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('table input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});

var checkState = '';

$('.bulk_action input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('.bulk_action input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});
$('.bulk_action input#check-all').on('ifChecked', function () {
    checkState = 'all';
    countChecked();
});
$('.bulk_action input#check-all').on('ifUnchecked', function () {
    checkState = 'none';
    countChecked();
});

function countChecked() {
    if (checkState === 'all') {
        $(".bulk_action input[name='table_records']").iCheck('check');
    }
    if (checkState === 'none') {
        $(".bulk_action input[name='table_records']").iCheck('uncheck');
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $('.column-title').hide();
        $('.bulk-actions').show();
        $('.action-cnt').html(checkCount + ' Records Selected');
    } else {
        $('.column-title').show();
        $('.bulk-actions').hide();
    }
}

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != 'undefined') {
    $(document).ready(function () {
        NProgress.done();
    });

    $(window).on('load', function () {
        NProgress.start();
    });
}

/**
 * Uppercase the given string.
 *
 * @param  string string
 * @return string
 */
window.ucFirst = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/**
 * Get context id meta content.
 *
 * @param  {Boolean} isInt
 * @return vary
 */
window.getContextId = function (isInt=true) {
    let container = $('meta[name="context-id"]');
    if (container.length < 1) {
        console.error('Context ID not found.');
        return null;
    }

    let contextId = container.prop('content');
    if (contextId.length < 1) {
        console.error('Context ID is empty.');
        if (isInt) {return NaN} else {return null};
    }

    if (isInt) {
        contextId = parseInt(contextId);
        if (isNaN(contextId)) {
            console.error('Cannot parse int from context ID.');
            return NaN;
        }
    }
    return contextId;
}

/**
 * Show confirmation dialogue using sweet alert.
 *
 * @param  {String} title
 * @param  {String} text
 * @param  callable confirmCallback
 * @param  {String} type
 * @param  callable cancelCallback
 * @return void
 */
window.swalConfirm = function (confirmCallback, title='Are you sure?', text='Confirm the action.', type='warning', cancelCallback=function(){}){
    swal({
    title: title,
    text: text,
    type: type,
    showCancelButton: true,
    confirmButtonColor: '#337ab7',
    cancelButtonColor: '#d9534f',
    confirmButtonText: 'Yes'
    }).then(confirmCallback, cancelCallback)
}

window.handleNotification = function (notification) {
    if (/NewsReportBeyondThreshold/i.test(notification.type)) {
        var val = parseInt($('#noox-notification-badge').html());
        if (isNaN( val )) { val = 0; }
        $('#noox-notification-badge').html( val + 1 );

        if ( val === 0 ) { $NOTIFICATION.find('li').remove(); }
        if ( (val + 1) > 5 ) { $NOTIFICATION.find('li').eq(4).remove(); }
        $NOTIFICATION.prepend(notifGenerator(notification));

        spawnNoty(notification.text, 'warning');
    }
};

window.spawnNoty = function (text, type = 'info', onCloseCallback = function(){}) {
    if (text.length > 0) {
       return new Noty({
          type: type,
          layout: 'topRight',
          theme: 'relax',
          text: text,
          timeout: 5000,
          progressBar: true,
          closeWith: ['click', 'button'],
          animation: {
            open: 'noty_effects_open',
            close: 'noty_effects_close'
          },
          id: false,
          force: false,
          killer: false,
          queue: 'global',
          container: false,
          buttons: [],
          sounds: {
            sources: [],
            volume: 1,
            conditions: []
          },
          titleCount: {
            conditions: []
          },
          modal: false,
          callbacks: {
            onClose: onCloseCallback,
          }
        }).show();
    }
};

function notifGenerator(notification) {
    var html = '';
    html += '<li>';
    html += '<a href="' + window.location.origin + '/cms/' + notification.target_url + '">';
    html += '<span class="image"><img src="' + window.location.origin + '/admin/images/user.png" alt="Profile Image" /></span>';
    html += '<span>';
    html += '<span><strong>' + notification.title + '</strong></span>';
    html += '<span class="time">Some moment ago</span>';
    html += '</span>';
    html += '<span class="message">';
    html += $("<div>").text(notification.text).html();
    html += '</span>';
    html += '</a>';
    html += '</li>';
    return html;
}

// Noox event listeners

/**
 * Handle the dismiss all notification button in top menu.
 */
$('.notif-dismiss-button').on('click', function (e){
    axios.post(gCmsApiBase + '/notifications/dismiss_all')
    .then(function (response){
      let html = 
      `
      <li class="notif-button">
        <div class="text-center">
            <strong>No Notifications</strong>
        </div>
      </li>
      `;
      $('#menu1').html(html);
      $('#noox-notification-badge').html('');
    })
    .catch(function (error){
      spawnNoty('Cannot dismiss notifications!', 'error');
    });
});

// Init laravel-echo
import Echo from 'laravel-echo';

var jwt = document.head.querySelector('meta[name="jwt"]');

if ((typeof io !== "undefined") && jwt && (jwt.content.length > 0)) {
    window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname+':6001',
    auth:
    {
        headers:
        {
            'Authorization': 'Bearer ' + jwt.content
        }
    }
});
}