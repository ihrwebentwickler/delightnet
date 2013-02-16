/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license

Edited by Ihr webentwickler, Gunnar von Spreckelsen
*/

CKEDITOR.editorConfig = function( config ) {
    // all options api: http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
    
    // File-Manager in use
    config.filebrowserBrowseUrl = 'elfinder.html';
    
    // Language
    config.language = 'de';

    // UI-Color
    config.uiColor = '#5C87CF';
    
    // The color of the dialog background cover
    config.dialog_backgroundCoverColor = '#F3E540';

    // no create wrapping-blocks around inline-styles
    config.autoParagraph = false;

    // no basic html-escaping
    config.basicEntities = false;

    // no basic html-escaping
    config.entities = false;

    // min-height of editor
    config.height = 362;

    config.ignoreEmptyParagraph = false;
 
    // remove dialogs
    // config.removeDialogTabs = 'image:Link';
    
    // This is actually the default value.
    // see: http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html#.toolbar_Full
    config.toolbar_Full =
    [
        { name: 'document',    items : [ 'Source','DocProps','-','Print','-' ] },
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'forms',       items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        '/',
        { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
        { name: 'tools',       items : [ '-', 'ShowBlocks','-','About' ] }
    ];
};