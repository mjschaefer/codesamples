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

<a href="<?=url::site('admin/treasurers')?>">&laquo; Back</a>
<h1>Edit a Page</h1>

<form class="item-form" method="post" action="<?=url::site('admin/treasurers/update/page')?>">
  <label>Title</label>
  <input class="text" type="text" name="title" value="<?=$page->title?>"/>
  
  <label>Content</label>
  <textarea name="body" rows="10" cols="60"><?=$page->body?></textarea>
  
  <input type="hidden" name="id" value="<?=$page->id?>"/>
  <input class="button" type="submit" value="Update!"/>
  <input class="button" type="reset" value="Reset!"/>
</form>