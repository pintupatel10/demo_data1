<div id="image_container">
    <img src="blabla" />
    <img src="blabla" />
    ...
</div>
<form ...>
    <input id="image_from_list" name="image_from_list" type="hidden" value="" />
    <input id="image_from_file" name="image_from_file" type="file" />
</form>
// JS
<script>
$('div#image_container img').click(function(){
// set the img-source as value of image_from_list
$('input#image_from_list').val( $(this).attr("src") );
});
</script>