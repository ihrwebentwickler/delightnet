$(document).ready(() => {
    setSpamKeyImage();

    $(".load-captcha").click(function() {
            setSpamKeyImage();
        }
    );

    function setSpamKeyImage() {
        $.ajax({
            url: "/index.php?cmd=contact&action=" + GLOBAL.STATUS.lang + "&query=getCaptchaimage",
            data: {},
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#captchaImage').html(data);
            },
            error: function (xhr, status) {
                console.log("An error occured (get spam-key-image-data in extension contact):");
                console.log(xhr);
                console.log(status);
            }
        });
    }
});