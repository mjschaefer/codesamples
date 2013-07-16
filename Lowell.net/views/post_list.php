<a href="<?=url::site('admin/blogs')?>">&laquo; Back</a>
<h1><?=$blog->name?> Posts Manager</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/blogs/' . $blog->id . '/add_post')?>">New Post</a></li>
  </ul>
</div>

<?php if(count($posts) <= 0): ?>
  <p style="text-align:center">There are no posts to list.</p>
<?php else: ?>
  
<?php foreach($posts as $post): ?>
<div id="<?=$post->id?>" class="list-item">
  <h1><?=$post->title?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/blogs/' . $blog->id . '/edit_post/' . $post->url)?>">Edit</a>
    <a class="delete-link" href="<?=url::site('admin/blogs/' . $blog->id . '/delete_post/' . $post->url)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>