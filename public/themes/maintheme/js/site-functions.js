$(document).ready(() => {
    $('.device-dehaze').click(function () {
        let classDeviceMenu = '.device-menu';
        if ($(classDeviceMenu).css('display') === 'block') {
            $(classDeviceMenu).hide();
            $('#content').show();
        } else {
            $(classDeviceMenu).show();
            $('#content').hide();
        }
    });

    // dropdown-menu show and hide by click (tablet-friendly)
    $('.dropdown a').click(function (e) {
        e.stopPropagation();
        let $el = $(this).next().hasClass('dropdown-menu') ? $(this).next() : $(this).next().next();
        if ($el.hasClass('dropdown-menu')) {
            if ($el.is(':hidden')) {
                $el.show();
            } else {
                $el.hide();
            }
        }
    });

    $('body').click(() => {
        $('.dropdown-menu').hide();
    });
});