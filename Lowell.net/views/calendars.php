<h1>Calendar Manager</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/calendars/add')?>">New Calendar</a></li>
  </ul>
</div>

<?php if(count($calendars) == 0): ?>
  <p style="text-align:center">There are no calendars to list.</p>
<?php else: ?>
  
<?php foreach($calendars as $calendar): ?>
<div id="<?=$calendar->id?>" class="list-item">
  <h1><?=$calendar->name?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/calendars/edit/' . $calendar->url)?>">Edit</a>
    <a class="manage-link" href="<?=url::site('admin/calendars/' . $calendar->url)?>">Manage Events</a>
    <a class="delete-link" href="<?=url::site('admin/calendars/delete/' . $calendar->url)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>