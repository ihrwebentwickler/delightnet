/* 
    Document   : cmsadmin.js
    Version    : 0.8 (11.12.2012)
    Author     : Gunnar von Spreckelsen <service@ihrwebentwickler.de>
    Description: basic-site-functionality of cms-admin-env
 */
$(document).ready(function(){
    function _options(){
        jQuery("#nav li").css('zIndex', 510);
        
        // unset unused current-lang-link
        jQuery("#flaglinks a").each(function(index, link) {            
            if (jQuery(this).children().hasClass('choisedLang') === true) {
                jQuery(this).children().unwrap('a');
            }     
        });
    }
    
    function mainmenu(){
        $(" #nav ul ").css({
            display: "none"
        }); // Opera Fix
        $(" #nav li").hover(function(){
            $(this).find('ul:first').css({
                visibility: "visible",
                display: "none"
            }).show(180);
        },function(){
            $(this).find('ul:first').css({
                visibility: "hidden"
            });
        });
    }
    
    // close jQuery-UI-Dialog-Box always by submit
    jQuery('.dialogForm').submit(function() {
        jQuery("#dialogContainer").dialog('close');
    });
   
    // load jQuery-UI-Dialog-Box with textarea-content
    jQuery('.ContainerDialogTextarea option, .ContainerDialogEditor option').click(function() {
        var filelink = jQuery(this).attr('alt');
        var ckElement = '#ckEditorStandard';
        var editor = (jQuery('#boolTexteditor:checked').val() == null) ? "Standard-Editor" : "HTML-Editor";
        editor = (jQuery(this).parent().parent().hasClass('bannedContentSite') === true) ? "Schutz-Editor" : editor;
        var formActionSite;
        
        jQuery.ajax({
            'url': filelink,
            'cache': false,
            'success': function (data) {
                jQuery(ckElement).val('').val(data);
                jQuery('#dialogEditor #currentFileName').text(filelink);
                formActionSite = jQuery('#ContainerDialog').attr("class");
                jQuery('input[name="DialogContentSite"]').attr('value', formActionSite);
                jQuery('input[name="filelink"]').attr('value', filelink);
            },
            'error': function () {
                jQuery('#gui').remove();
            }
        });
 
        jQuery('#dialogEditor').dialog({
            open:function(){
                if (editor == 'HTML-Editor') {
                    jQuery(ckElement).ckeditor();
                }
            },
            close:function(){
                if (editor == 'HTML-Editor') {
                    jQuery(ckElement).ckeditorGet().destroy();
                }
            },
            title: '&nbsp;Sie bearbeiten:&nbsp;&nbsp;&nbsp;' + filelink + '&nbsp;&nbsp;&nbsp;im&nbsp;' + editor,
            modal: true,
            draggable: true,
            closeOnEscape: true,
            width: 903,
            height: 582
        });
 
        return false;
    });
    
    _options();
    mainmenu();
});