<style type="text/css">
/* ################################################################## */
.ui-widget-header {
    background: #F3E540;
    border: 1px solid #F3E540;
}
/* ################################################################## */
</style>

{DIALOGEDITOR}

<div>
    <span>&nbsp;Texteditor ist aktiviert/ nicht aktiviert</span>
    <input type="checkbox" id="boolTexteditor" checked="checked"/>
</div>

<div id="ContainerDialog" class="content">
    <div>
        <div class="leftfloat">
            <p>
                &nbsp;Inhalts-Seiten:
            </p>
            <select name="c1" size="22" class="selectStandard ContainerDialogEditor">
                {CONTENT_TPLHELPER1}
            </select>
        </div>

        <div class="leftfloat leftmargin">
            <p>
                &nbsp;Inhalts-Seiten geschützt:
            </p>
            <select name="c2" size="22" class="selectStandard ContainerDialogEditor bannedContentSite">
                {CONTENT_TPLHELPER2}
            </select>
        </div>

        <div class="clear">
        </div>
    </div>
    <div class="topmargin">
        <div class="leftfloat">
            <p>
                &nbsp;Helper:
            </p>
            <select name="c3" size="22" class="selectStandard ContainerDialogEditor">
                {CONTENT_TPLHELPER3}
            </select>
        </div>

        <div class="leftfloat leftmargin">
            <p>
                &nbsp;Helper geschützt:
            </p>
            <select name="c4" size="22" class="selectStandard ContainerDialogEditor bannedContentSite">
                {CONTENT_TPLHELPER4}
            </select>
        </div>

        <div class="clear">
        </div>
    </div>
    <div class="topmargin">
        <div class="leftfloat">
            <p>
                &nbsp;Erweiterungen:
            </p>
            <select name="c5" size="22" class="selectStandard ContainerDialogEditor">
                {CONTENT_TPLHELPER5}
            </select>
        </div>

        <div class="leftfloat leftmargin">
            <p>
                &nbsp;Erweiterungen geschützt:
            </p>
            <select name="c6" size="22" class="selectStandard ContainerDialogEditor bannedContentSite">
                {CONTENT_TPLHELPER6}
            </select>
        </div>

        <div class="clear">
        </div>
    </div>
    <div class="topmargin">
        <div class="leftfloat">
            <p>
                &nbsp;Erweiterungen Helper:
            </p>
            <select name="c7" size="22" class="selectStandard ContainerDialogEditor">
                {CONTENT_TPLHELPER7}
            </select>
        </div>

        <div class="leftfloat leftmargin">
            <p>
                &nbsp;Erweiterungen Helper geschützt:
            </p>
            <select name="c8" size="22" class="selectStandard ContainerDialogEditor bannedContentSite">
                {CONTENT_TPLHELPER8}
            </select>
        </div>

        <div class="clear">
        </div>
    </div>
</div>