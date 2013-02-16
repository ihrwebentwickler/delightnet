<div id="dialogEditor">
    <form action="dialogeditor.html" method="post" enctype="multipart/form-data">
        <div id="editor">
            <textarea class="fullarea" id="ckEditorStandard" name="strTextarea" cols="40" rows="8"></textarea>    
        </div>

        <div class="topmarginSmall">
            <input type="hidden" name="filelink" />
            <input type="hidden" name="DialogContentSite" />
            <input class="button" name="submitDialogForm" type="submit" value="Speichern" />
            <span>&nbsp;&nbsp;&nbsp;Sie bearbeiten:</span>
            <span id="currentFileName"></span>
        </div>
    </form>
</div>