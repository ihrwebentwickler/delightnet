/* functions of contact-form
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   extensions/Contact
 * @version   1.0
 */

(function($) {
    jQuery.fn.contact = function(arg) {
        var options = $.extend({},$.fn.contact.defaults,arg);
        var isError = true;
        var strMessageClass = '#' + jQuery(this).attr('id') + ' .message';
        var strFormMain = '#' + jQuery(this).attr('id') + ' form[name="mainform"]';
        getInputFieldValues(strFormMain);

        return this.each(function() {   
            jQuery(strFormMain).submit(function(event) {          
                isError = hasError('#' + jQuery(this).parent().attr('id'));
                if (isError === true) {
                    event.preventDefault();
                    setErrorMessage(strMessageClass);
                }
                else {                    
                    setInputFieldValues(strFormMain);
                    jQuery('.wait').show();
                    jQuery(this).submit(function(event){
                        event.preventDefault();
                    });
                }
            });
            
            return false;
        });
        
        function hasError(strSelectorId) {
            var i;
            var strCurrentInputType;
            for (i = 0; i < options.obligation.length; i++) {   
                strCurrentInputType = (typeof jQuery(strSelectorId + " textarea[name='emaildata[" + (i + 1) + "]']").attr('name') === 'undefined') ?
                'input' : 'textarea';
                if (
                    options.obligation[i] == 1
                    && typeof jQuery(strSelectorId + " " + strCurrentInputType + "[name='emaildata[" + (i + 1) + "]']").attr('name') !== 'undefined'
                    && jQuery(strSelectorId + " " + strCurrentInputType + "[name='emaildata[" + (i + 1) + "]']").val() == ""
                    ) {
                    return true;
                }
            }
            
            return false;
        };
        
        function setErrorMessage(strMessageClass) {
            jQuery(strMessageClass).html(options.standardError);
            jQuery(strMessageClass).attr('style', options.cssError);
        };
        
        function setInputFieldValues(strFormElem) {
            jQuery(strFormElem).children().each(function(index, value) {
                if ((jQuery(value).context.tagName == 'INPUT' || jQuery(value).context.tagName == 'TEXTAREA')
                    && jQuery(value).attr('name') !== 'captcha'
                    && jQuery(value).attr('name') !== 'captchaKey')
                    {  
                    $.session.set('contactForm' + index, jQuery(value).val());
                }
            });
        };
        
        function getInputFieldValues(strFormElem) {
            jQuery(strFormElem).children().each(function(index, value) {
                if ((jQuery(value).context.tagName == 'INPUT' || jQuery(value).context.tagName == 'TEXTAREA')
                    && jQuery(value).attr('name') !== 'captcha'
                    && jQuery(value).attr('name') !== 'captchaKey')
                    {
                    if (typeof $.session.get('contactForm' + index) !== 'undefined') {
                        jQuery(value).val($.session.get('contactForm' + index));
                    }
                }
            });
        };
    };

    $.fn.contact.defaults = {
        'obligation': [],
        'fontColorError': 'ff0000',
        'styleInputFieldError': '1px solid #ff0000',
        'standardError': 'Error:',
        'messageStart': 'Welcome:'
    };
})(jQuery);