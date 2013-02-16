<style type="text/css">
/* ################################################################## */
.ui-widget-header {
    background: #F3E540;
    border: 1px solid #F3E540;
}
/* ################################################################## */
</style>

{DIALOGEDITOR}

<div id="ContainerDialog" class="css">
    <div class="leftfloat">
        <p>
        &nbsp;CSS-Dateien der Webseite:
        <p>
        <select name="css_website" size="22" class="selectStandard ContainerDialogTextarea">
        {CONTENT_CSSHELPER1}
        </select>
    </div>

    <div class="leftfloat leftmargin">
        <p>
        &nbsp;CSS-Dateien der Plugins:
        <p>
        <select name="css_plugins" size="22" class="selectStandard ContainerDialogTextarea">
        {CONTENT_CSSHELPER2}
        </select>
    </div>

    <div class="clear">
    </div>
</div>