<div>
    <form action="iniedit.html" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="{NAME_SYSTEMEXT}" class="button" />
        <div>
            <textarea class="fullarea" name="strTextarea" cols="40" rows="8">{EXTCONTENT}</textarea>    
        </div>
        <div class="topmarginSmall">
            <input type="submit" name="action_extlist" value="speichern" class="button" />
        </div>

        {SAVEBUTTON_PAGEHELPER}
    </form>
</div>