<script type="text/javascript">
function checkAll(formname, checktoggle)
{
     var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');
		alert(checkboxes.length);
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = checktoggle;
          }
      }
}
</script>
         <a onclick="javascript:checkAll('myformtt', true);">Check All</a> | 
         <a onclick="javascript:checkAll('myformtt', false);">UnCheck All</a>
<?php if(isset($_POST['submit']))
{
	print_r ($trt = $_POST['content']);

}
?>
<form name="myformtt" method="post">
<input type="submit" name="submit" value="Submit">
    <input class="cbva" type="checkbox" name="content[]" value="1"/>
    <input class="cvbc" type="checkbox" name="content[]" value="2">
    <input class="cvbx" type="checkbox" name="content[]" value="3">

</form>