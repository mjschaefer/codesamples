<script type="text/javascript" src="<?=url::base()?>js/fckeditor/fckeditor.js"></script>
<script type="text/javascript" charset="utf-8">
  window.onload = function()
  {
    var Editor = new FCKeditor( 'body' );
    Editor.BasePath = "<?=url::base()?>js/fckeditor/";
    Editor.ToolbarSet = 'Lowell';
    Editor.Height = 700;
    Editor.ReplaceTextarea();
  }
</script>

<a href="<?=url::site('admin/calendars/' . $calendar->url)?>">&laquo; Back</a>
<h1>Edit an Event</h1>

<form class="item-form" method="post" action="<?=url::site('admin/calendars/' . $calendar->url . '/update/event')?>">
  <label>Name</label>
  <input class="text" type="text" name="name" value="<?=$event->name?>"/>
  
  <label>Date &amp; Time</label>
  <p>
    <select class= "datetime" name="month">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"<?php if($i == $event->month){echo ' selected="selected"';}?>><?=date("M", mktime(0,0,0,$i,1) )?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="day">
      <?php for($i = 1; $i < 32; $i++): ?>
      <option value="<?=money_format('%=0!1#1.0n', $i)?>"<?php if($i == $event->day){echo ' selected="selected"';}?>><?=money_format('%=0!1#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="year">
      <?php for($i = 2009; $i < 2013; $i++): ?>
      <option value="<?=$i?>"<?php if($i == $event->year){echo ' selected="selected"';}?>><?=$i?></option>
      <?php endfor; ?>
    </select>
    
    <br/>from
  
    <select class="datetime" name="start_hour">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"<?php if($i == $event->start_hour){echo ' selected="selected"';}?>><?=$i?></option>
      <?php endfor; ?>
    </select>
    
    :
  
    <select class="datetime" name="start_minute">
      <?php for($i = 0; $i < 60; $i++): ?>
      <option value="<?=money_format('%=0!1#1.0n', $i)?>"<?php if($i == $event->start_minute){echo ' selected="selected"';}?>><?=money_format('%=0!1#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="start_meridiem">
      <option value="AM"<?php if($event->start_meridiem == 'AM'){echo ' selected="selected"';}?>>AM</option>
      <option value="PM"<?php if($event->start_meridiem == 'PM'){echo ' selected="selected"';}?>>PM</option>
    </select>
    
    to
    
    <select class="datetime" name="end_hour">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"<?php if($i == $event->end_hour){echo ' selected="selected"';}?>><?=$i?></option>
      <?php endfor; ?>
    </select>
    
    :
  
    <select class="datetime" name="end_minute">
      <?php for($i = 0; $i < 60; $i++): ?>
      <option value="<?=money_format('%=0!1#1.0n', $i)?>"<?php if($i == $event->end_minute){echo ' selected="selected"';}?>><?=money_format('%=0!1#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="end_meridiem">
      <option value="AM"<?php if($event->end_meridiem == 'AM'){echo ' selected="selected"';}?>>AM</option>
      <option value="PM"<?php if($event->end_meridiem == 'PM'){echo ' selected="selected"';}?>>PM</option>
    </select>
  </p>
  
  <label>Description</label>
  <textarea id="body" name="description" rows="10" cols="60"><?=$event->description?></textarea>
  
  <input type="hidden" name="id" value="<?=$event->id?>"/>
  <input class="button" type="submit" value="Update!"/>
  <input class="button" type="reset" value="Reset!"/>
</form>