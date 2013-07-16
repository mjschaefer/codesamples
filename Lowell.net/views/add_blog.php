<a href="<?=url::site('admin/blogs')?>">&laquo; Back</a>
<h1>Create a New Blog</h1>

<form class="item-form" method="post" action="<?=url::site('admin/blogs/create')?>">
  <label>Name</label>
  <input class="text" type="text" name="name"/>
  
  <input class="button" type="submit" value="Create!"/>
  <input class="button" type="reset" value="Clear!"/>
</form>