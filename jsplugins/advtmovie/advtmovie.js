/* 
    Document   : advtmovie.js
    Version    : 1.0 (21.01.2013)
    Author     : Gunnar von Spreckelsen <service@ihrwebentwickler.de>
    Description: playout an advertisement-movie by passing a html-description-file
 */
;
(function(jQuery) {
    jQuery.fn.advtMovie = function (options) { 
        var options = jQuery.extend( {}, jQuery.fn.advtMovie.defaults, options);
        
        return this.each(function () {
            console.log (options.autostart);
        });
        
        var advtMovie = {
            
        }
        
        // default configuration properties
        jQuery.fn.advtMovie.defaults = {			
            autostart: 1,
            instance: 1
        };
    };
})(jQuery, jQuery);