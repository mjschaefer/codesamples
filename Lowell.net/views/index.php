<?php if(count($events) > 0): ?>
<div id="events-box">
  <h1>Upcoming Events</h1>
  
  <?php foreach($events as $event): ?>
  <p><span class="event-date"><?=date('M j', $event->time)?></span> - <a href="<?=url::site('calendar/event/'.$event->id)?>"><?=$event->name?></a></p>
  <?php endforeach; ?>
  <p class="to-events"><a href="<?=url::site('calendar/town')?>">More Events &raquo;</a></p>
</div>
<?php endif; ?>

<div id="news-box">
  
  <?php if(count($posts) == 0): ?>
    <p style="text-align:center">There are no posts to display.</p>
  <?php else: ?>
  <?php foreach($posts as $post): ?>
  <div class="news-post">
    <h1><?=$post->title?></h1>
    <h2>Posted on <?=date("F d, Y", $post->time);?></h2>
    <p>
      <?=$post->body?>
    </p>
  </div>
  <?php endforeach; ?>
  
  <p class="pagination-links"><?=$pagination?></p>
  <?php endif; ?>

</div>