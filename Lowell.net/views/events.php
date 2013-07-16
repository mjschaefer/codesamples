<a href="<?=url::site('admin/calendars')?>">&laquo; Back</a>
<h1><?=$calendar->name?> Event Manager</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/calendars/' . $calendar->url . '/add/event')?>">New Event</a></li>
  </ul>
</div>

<?php if(count($events) <= 0): ?>
  <p style="text-align:center">There are no events to list.</p>
<?php else: ?>
  
<?php foreach($events as $event): ?>
<div id="<?=$event->id?>" class="list-item">
  <h1><?=$event->name?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/calendars/' . $calendar->url . '/edit/event/' . $event->url)?>">Edit</a>
    <a class="delete-link" href="<?=url::site('admin/calendars/' . $calendar->url . '/delete/event/' . $event->url)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>