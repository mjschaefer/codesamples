<a href="<?=url::site('admin/blogs')?>">&laquo; Back</a>
<h1>Edit Blog</h1>

<form class="item-form" method="post" action="<?=url::site('admin/blogs/update')?>">
  <label>Name</label>
  <input class="text" type="text" name="name" value="<?=$blog->name?>"/>
  
  <input type="hidden" name="id" value="<?=$blog->id?>"/>
  <input class="button" type="submit" value="Update!"/>
  <input class="button" type="reset" value="Reset!"/>
</form>