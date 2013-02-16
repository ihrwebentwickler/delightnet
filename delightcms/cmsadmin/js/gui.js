/* 
    Document   : gui.js
    Version    : 0.9 (05.12.2012)
    Author     : Gunnar von Spreckelsen <service@ihrwebentwickler.de>
    Description: dynamic edit of extension
 */
;
(function(jQuery) {
    jQuery.fn.gui = function(options) {        
        var options = jQuery.extend( {}, jQuery.fn.gui.defaults, options);
        
        var strDeleteInstanceHtml = jQuery('#gui #deleteInstanceMessage').html();
        var strDeleteEntryHtml = jQuery('#deleteEntryMessage').html();
        var strDomInstanceOrigin = jQuery('<div>')
        .append(jQuery('#' + options.instanceName + '-2').clone()).html()
        .replace(/value\=".+"/g, 'value=""')
        .replace(/\>.+\<\/textarea>/g, '></textarea>')
        .replace(/checked="{.+}"/g, 'checked="off"');
        
        var strDomEntryOrigin = jQuery('<div>')
        .append(jQuery('#gui .instanceContent-2:first').clone()).html()
        .replace(/value\=".+"/g, 'value=""')
        .replace(/\>.+\<\/textarea>/g, '></textarea>')
        .replace(/checked="{.+}"/g, 'checked="off"');
        
        removeDomCopyData();
        resetDom();
        
        return this.each(function() {
            jQuery(document).on('click', 'select[name="select_instance_' + options.instanceName + '"] option', function () {
                var intInstanceIdent = jQuery(this).attr('alt').split('.')[1];
                showInstance(intInstanceIdent);
            });
            
            jQuery(document).on('click', '#gui form div[class^="instanceContent"] .headerText', function () {
                toogleDomEntryHeader(this);
            });
            
            jQuery('#newInstance, #deleteInstance, #newEntry, #deleteEntry').click(function () {
                deleteAndBuildDomByAction(this);
            });
        });
        
        function removeDomCopyData() {
            // remove dom-copy of instance and dom-copy of entry from main-dom
            jQuery('#' + options.instanceName + '-2').remove();
            jQuery('.instanceContent-2').remove();
        }
        
        function resetDom() {            
            // by start show in navigation: new instance (always possible) 
            if (jQuery('#gui #selectInstance').length && jQuery('#gui #selectEntry').length) {
                jQuery('#gui #deleteInstance, #gui #newEntry, #gui #deleteEntry').hide();
            }
            
            // hide all entries of instances and data
            jQuery('#gui form div[id^="' + options.instanceName + '"]').hide();
            jQuery('#gui form div[class^="instanceContent"] .headerText, #gui form div[class^="instanceContent"] .colorbox').hide();
            
            if (jQuery('#gui #messages').length) {
                jQuery('#gui #newInstanceMessage, #gui #deleteInstanceMessage, #gui #newEntryMessage, #gui #deleteEntryMessage').hide();
            }
            
            // all entry-arrows are closed and in standard-color
            jQuery('#gui form div[class^="instanceContent"] .headerText p .dynamicarrow').removeClass('activeHeader').text('\u25ba');
        };
        
        function showInstance(intInstanceIdent) {
            resetDom();
            
            // reset instance-dom-env
            jQuery('#instance form div[id^="' + options.instanceName + '"]').removeClass('activeInstance');
            jQuery('#instance form div[id^="' + options.instanceName + '"]').hide();
            jQuery('#instance form div[class^="instanceContent"]').hide();
            
            if (intInstanceIdent) {                
                jQuery('#' + options.instanceName + intInstanceIdent).show();
                showContent(intInstanceIdent);
                jQuery('#' + options.instanceName + intInstanceIdent).addClass('activeInstance');
                jQuery('#gui #deleteInstance, #gui #newEntry').show();
            }

            updateDomSelectInstance();
        };
        
        function toogleDomEntryHeader(objClicked) {
            jQuery('#gui form div[class^="instanceContent"]').removeClass('activeContent');
            jQuery('#gui form div[class^="instanceContent"] .headerText p .dynamicarrow').removeClass('activeHeader').text('\u25ba');
            jQuery('#gui form div[class^="instanceContent"] .colorbox').not(jQuery(objClicked).parent().next('.colorbox')).hide();
            jQuery(objClicked).children().children('.dynamicarrow').addClass('activeHeader').text('\u25bc');
            jQuery(objClicked).parent().addClass('activeContent');
            jQuery(objClicked).next().show();
            updateDomSelectInstance('activeContent');
        };
        
        function showContent(intInstanceIdent) {
            jQuery('.instanceContent' + intInstanceIdent + ', .instanceContent' + intInstanceIdent + ' .headerText').show();
        };
        
        function updateDomSelectInstance(activ) {
            if (jQuery('#gui div').hasClass('activeInstance') === true) {
                jQuery('#gui #deleteEntry').hide();
            }
            
            if (activ == 'activeContent') {
                jQuery('#gui #selectEntry, #gui #deleteEntry').show(1200);
            }
        };
        
        function addSelectInstanceEntry(intInstanceIdent) {
            var strOptionSelectListHtml = jQuery('<div>').append(jQuery('select[name="select_instance_' + options.instanceName + '"] option:first').clone()).html();
            
            jQuery('select[name="select_instance_' + options.instanceName + '"] option').attr("selected", false)                         
            jQuery('select[name="select_instance_' + options.instanceName + '"]').last().append(strOptionSelectListHtml);
            jQuery('select[name="select_instance_' + options.instanceName + '"] option:last').attr("selected", true);
            jQuery('select[name="select_instance_' + options.instanceName + '"] option:last').attr("alt", options.instanceName + '.' + (intInstanceIdent + 1));
            jQuery('select[name="select_instance_' + options.instanceName + '"] option:last').html(options.instanceName + ' ' + (intInstanceIdent + 1));
        };
        
        function deleteSelectInstanceEntry(intintInstanceIdent) {
            jQuery('select[name="select_instance_' + options.instanceName + '"] option:eq(' + intintInstanceIdent + ')').remove();
            jQuery('select[name="select_instance_' + options.instanceName + '"]').val(0);
        };

        function addNewEntry(intInstanceIdent, intEntryIdent) {
            var strDomNewEntry;

            jQuery('#gui form div[class^="instanceContent"]').removeClass('activeContent');
            jQuery('#gui form div[class^="instanceContent"] .headerText p .dynamicarrow').removeClass('activeHeader').text('\u25ba');
            jQuery('#gui form div[class^="instanceContent"] .colorbox').hide();
            
            strDomNewEntry = strDomEntryOrigin.replace(/-2/g, intInstanceIdent).replace(/-1/g, intEntryIdent);
            jQuery('#instance div[class^="instanceContent"]:last').after(strDomNewEntry);
            
            jQuery('#instance div[class^="instanceContent"]:last').addClass('activeContent');
            jQuery('#instance div[class^="instanceContent"]:last .colorbox').show(500);
            jQuery('#instance div[class^="instanceContent"]:last .headerText p .dynamicarrow').addClass('activeHeader').text('\u25bc');
        };
        
        function deleteAndBuildDomByAction(objMessage) {
            var strMessageHtml = '';
            var strDomNewInstance;
            var intInstanceIdent;
            var intEntryIdent;
            
            switch (objMessage.id)
            {
                case 'newInstance':
                    // build message-string new instance
                    strMessageHtml = '#' + objMessage.id + 'Message';
                    
                    // get max Instance-Nb and add option to Instance-Select-List
                    intInstanceIdent = jQuery('select[name="select_instance_' + options.instanceName + '"] option:last')
                    .attr('alt')
                    .split('.')[1];
                    intInstanceIdent = parseInt(intInstanceIdent,10);
                    addSelectInstanceEntry(intInstanceIdent);
                    
                    // replace and set new instance-string to dom
                    strDomNewInstance = strDomInstanceOrigin.replace(/-2/g, intInstanceIdent + 1);
                    jQuery('#instance form div[id^="' + options.instanceName + '"]:last').after(strDomNewInstance);
                    jQuery('#instance form div[id^="' + options.instanceName + '"]:last').addClass('activeInstance').show(500);
                    
                    showInstance(intInstanceIdent + 1);
                    break;
                case 'deleteInstance':
                    // build message-string delete instance
                    strMessageHtml = '#' + objMessage.id + 'Message';
                    
                    // get id-number of delete-instance
                    intInstanceIdent =  jQuery('#gui div.activeInstance').attr('id').split(options.instanceName)[1];
                    
                    // replace del-message-string with current del-id
                    jQuery(strMessageHtml).html(strDeleteInstanceHtml.replace(/{MESSAGE_NB_INSTANCE}/g, intInstanceIdent));
                    
                    // delete instance and entries from instance
                    jQuery('#' + options.instanceName + intInstanceIdent).remove();
                    jQuery('.instanceContent' + intInstanceIdent).remove();
                    
                    // delete instance
                    deleteSelectInstanceEntry(intInstanceIdent);
                    resetDom();
                    break;
                case 'newEntry':
                    // message-string new entry
                    strMessageHtml = '#' + objMessage.id + 'Message';
                    
                    intInstanceIdent = parseInt(jQuery('.activeInstance').attr('id').split(options.instanceName)[1]);
                    
                    if (jQuery('.instanceContent' + intInstanceIdent + ':last>div:first').length) {
                        intEntryIdent = parseInt(jQuery('.instanceContent' + intInstanceIdent + ':last>div:first').attr('id').split('_')[1],10) + 1;           
                    }
                    else {
                        intEntryIdent = 1;
                    }
 
                    addNewEntry(intInstanceIdent, intEntryIdent);
                    break;
                case 'deleteEntry':
                    // build and set message-str delete entry
                    strMessageHtml = jQuery('#deleteEntryMessage')
                    .html(strDeleteEntryHtml.replace(/{MESSAGE_NB_CONTENT}/g, jQuery('#gui .activeContent')
                        .children(':first')
                        .attr('id')
                        .split('_')[1]));
                    
                    // delete entry
                    jQuery('#gui .activeContent').remove();
                    
                    // hide-delete-btn (current entry is deleted)
                    jQuery('#deleteEntry').hide();
                    break;
                default:
                    strMessageHtml = 'undefined';
            }
            
            if (strMessageHtml) {
                animateMessage(strMessageHtml);
            }
        };
        
        function animateMessage(strMessageHtml) {
            jQuery(strMessageHtml).fadeIn(1000).delay(4200).fadeOut(1);
        };

        jQuery.fn.gui.defaults = {
            instanceName: 'error'
        };
    };
})(jQuery, jQuery);