<p><a href="<?=url::site('calendar/'.$calendar->url)?>">Back to Calendar</a></p>

<div id="event">
  <h1><?=$event->name?></h1>
  
  <h3>Date: <?=date('F j, Y', $event->time)?></h3>
  <h3>Start Time: <?=$event->start_hour.':'.$event->start_minute.$event->start_meridiem?></h3>
  <h3>End Time: <?=$event->end_hour.':'.$event->end_minute.$event->end_meridiem?></h3>
</div>

<div id="static-page">
  <?=$event->description?>
</div>