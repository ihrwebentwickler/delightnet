'use strict';
// 1.) click-event change language
$(document).ready(() => {
    $('.lang-change-icon').on('click', function () {
        const lang = $(this).data("location") ? $(this).data("location") : null;
        if (lang) {
            window.location.href = window.location.protocol + "//" + window.location.host + "/" + lang + ".html";
        }
    })
});