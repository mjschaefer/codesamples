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

<a href="<?=url::site('admin/blogs/' . $blog->url)?>">&laquo; Back</a>
<h1>Edit Post</h1>

<form class="item-form" method="post" action="<?=url::site('admin/blogs/' . $blog->url . '/update/post')?>">
  <label>Title</label>
  <input class="text" type="text" name="title" value="<?=$post->title?>"/>
  
  <label>Content</label>
  <textarea id="blog" name="body" rows="10" cols="60"><?=$post->body?></textarea>
  
  <input type="hidden" name="id" value="<?=$post->id?>"/>
  <input class="button" type="submit" value="Update!"/>
  <input class="button" type="reset" value="Reset!"/>
</form>