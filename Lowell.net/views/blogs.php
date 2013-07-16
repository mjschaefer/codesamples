<h1>Blog Manager</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/blogs/add')?>">New Blog</a></li>
  </ul>
</div>

<?php if(count($blogs) == 0): ?>
  <p style="text-align:center">There are no blogs to list.</p>
<?php else: ?>
  
<?php foreach($blogs as $blog): ?>
<div id="<?=$blog->id?>" class="list-item">
  <h1><?=$blog->name?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/blogs/edit/' . $blog->url)?>">Edit</a>
    <a class="manage-link" href="<?=url::site('admin/blogs/' . $blog->url)?>">Manage Posts</a>
    <a class="delete-link" href="<?=url::site('admin/blogs/delete/' . $blog->url)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>