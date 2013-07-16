<h1>Page Manager</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/pages/add')?>">New Page</a></li>
  </ul>
</div>

<?php if(count($pages) == 0): ?>
  <p style="text-align:center">There are no pages to list.</p>
<?php else: ?>
  
<?php foreach($pages as $page): ?>
<div id="<?=$page->id?>" class="list-item">
  <h1><?=$page->title?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/pages/edit/' . $page->url)?>">Edit</a>
    <a class="delete-link" href="<?=url::site('admin/pages/delete/' . $page->url)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>