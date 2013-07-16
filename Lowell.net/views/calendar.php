<?php

// Previous and next month timestamps
$next = mktime(0, 0, 0, $month + 1, 1, $year);
$prev = mktime(0, 0, 0, $month - 1, 1, $year);

// Previous and next month query URIs
$prev = url::site('calendar').'/'.$calendar_page->url.'/'.date('n', $prev).'/'.date('Y', $prev);
$next = url::site('calendar').'/'.$calendar_page->url.'/'.date('n', $next).'/'.date('Y', $next);

?>

<div id="calendar">
  <h1 style="text-align:center;"><?=$calendar_page->name?> Event Calendar</h1>
  
  <table class="calendar">
  <tr class="controls">
  <td class="prev"><?php echo html::anchor($prev, '&laquo;') ?></td>
  <td class="title" colspan="5"><?php echo strftime('%B %Y', mktime(0, 0, 0, $month, 1, $year)) ?></td>
  <td class="next"><?php echo html::anchor($next, '&raquo;') ?></td>
  </tr>
  <?=$calendar?>
</div>