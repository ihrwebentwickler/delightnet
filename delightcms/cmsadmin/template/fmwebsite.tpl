<style type="text/css">
/* ################################################################## */
.ui-widget-header {
    background: #F3E540;
    border: 1px solid #F3E540;
}
/* ################################################################## */
</style>

<div id="finder">finder</div>

<script type="text/javascript" charset="utf-8">
		
  $().ready(function() {
      var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");
 
      $('#finder').elfinder({
          url : '../../../cmsadmin/extension/elfinder-1.2/connectors/php/connectorFilemanager.php',
          lang : 'de',
          docked : true,

          dialog : {
            title: 'Filemanager für Web-Inhalte',
            height: 500
          }
			})
			
		})
	</script>