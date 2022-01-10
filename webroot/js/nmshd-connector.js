$(document).ready(function(){

    if ($('#qrcode').length > 0) {
        console.log($('#qrcode').length);
        var img = $("<img />").attr('src', '/enmeshed/access')
            .on('load', function () {
                if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
                    $('#qrcode').html('An error occured. The QR code could not be generated. Please try again!');
                } else {
                    $("#qrcode").html(img);
                }
            });
    }
});
