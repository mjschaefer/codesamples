<script type="text/javascript" src="<?=url::base()?>js/fckeditor/fckeditor.js"></script>
<script type="text/javascript" charset="utf-8">
  window.onload = function()
  {
    var Editor = new FCKeditor( 'body' );
    Editor.BasePath = "<?=url::base()?>js/fckeditor/";
    Editor.ToolbarSet = 'Lowell';
    Editor.Height = 700;
    Editor.ReplaceTextarea();
  }
</script>

<a href="<?=url::site('admin/councils')?>">&laquo; Back</a>
<h1>Create a New Page</h1>

<form class="item-form" method="post" action="<?=url::site('admin/councils/create/page')?>">
  <label>Title</label>
  <input class="text" type="text" name="title"/>
  
  <label>Content</label>
  <textarea id="body" name="body" rows="10" cols="60"></textarea>
  
  <input class="button" type="submit" value="Create!"/>
  <input class="button" type="reset" value="Clear!"/>
</form>