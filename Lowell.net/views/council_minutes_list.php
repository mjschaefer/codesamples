<a href="<?=url::site('admin/councils')?>">&laquo; Back</a>
<h1>Council Minutes</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/councils/add/minutes')?>">New Minutes</a></li>
  </ul>
</div>

<?php if(count($minutes) == 0): ?>
  <p style="text-align:center">There are no reports to list.</p>
<?php else: ?>
  
<?php foreach($minutes as $minutes): ?>
<div id="<?=$minutes->id?>" class="list-item">
  <h1><?=date("F d, Y", $minutes->date);?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/councils/edit/minutes/' . $minutes->id)?>">Edit</a>
    <a class="delete-link" href="<?=url::site('admin/councils/delete/minutes/' . $minutes->id)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>