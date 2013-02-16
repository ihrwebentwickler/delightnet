/* main-site-functions
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   public
 * @version   1.0
 */

jQuery(document).ready(function () {
    function _init() {
        if (jQuery('body #formSendedSuccess').length > 0) {
            var tmpLanguage = jQuery.session.get('sessionCurrentLangFile');
            jQuery.session.clear();
            jQuery.session.set('sessionCurrentLangFile', tmpLanguage);
        }
        
        if (typeof jQuery.session.get('sessionCurrentLangFile')  == "undefined" && jQuery('#languageNavigation img:first').length > 0){
            jQuery.session.set('sessionCurrentLangFile', jQuery('#languageNavigation img:first').attr('id').split('_')[1]);
        }

        if (jQuery('#languageNavigation img:first').length > 0) {
            jQuery.session.set('sessionHtmlContent', jQuery('#content').html());            
            replaceLangTexts(jQuery.session.get('sessionCurrentLangFile'));
        }
    }
    
    function replaceLangTexts(strLangIdentifier) {
        var strHtmlContent = '';
        
        jQuery('#languageNavigation img').removeClass('activeLang');
        jQuery('#languageNavigation img[id*="lang_' + strLangIdentifier + '"]').addClass('activeLang');
        
        if (jQuery('#languageNavigation img[id="lang_' + strLangIdentifier + '_' + global.currentCommand + '"]').attr('id'))
            jQuery.getJSON('/public/lang/package/' + strLangIdentifier + '_' + global.currentCommand + '.json',function(data){       
                for (var siteblock in data[global.currentCommand]) {
                    strHtmlContent = jQuery.session.get('sessionHtmlContent');
                    strHtmlContent = strHtmlContent.replace( new RegExp("\{L:" + siteblock.toUpperCase() + "\}","m"),data[global.currentCommand][siteblock]);
                }
                
                jQuery('#content').html(strHtmlContent);
            })
    }

    _init();
    
    jQuery('#languageNavigation img').click(function(){
        jQuery.session.set('sessionCurrentLangFile', this.id.split('_')[1]);
        replaceLangTexts(this.id.split('_')[1]);
    });
});