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
<h1>Create a New Event</h1>

<form class="item-form" method="post" action="<?=url::site('admin/calendars/' . $calendar->url . '/create/event')?>">
  <label>Name</label>
  <input class="text" type="text" name="name" value=""/>
  
  <label>Date &amp; Time</label>
  <p>
    <select class= "datetime" name="month">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"><?=date("M", mktime(0,0,0,$i,1) )?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="day">
      <?php for($i = 1; $i < 32; $i++): ?>
      <option value="<?=money_format('%=0!1#1.0n', $i)?>"><?=money_format('%=0!1#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  <!--testing-->
    <select class="datetime" name="year">
      <?php for($i = 2009; $i < 2014; $i++): ?>
      <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
    
    <br/>from
  
    <select class="datetime" name="start_hour">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
    
    :
  
    <select class="datetime" name="start_minute">
      <?php for($i = 0; $i < 60; $i++): ?>
      <option value="<?=money_format('%=0!1#1.0n', $i)?>"><?=money_format('%=0!1#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="start_meridiem">
      <option value="AM">AM</option>
      <option value="PM">PM</option>
    </select>
    
    to
    
    <select class="datetime" name="end_hour">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
    
    :
  
    <select class="datetime" name="end_minute">
      <?php for($i = 0; $i < 60; $i++): ?>
      <option value="<?=money_format('%=0!1#1.0n', $i)?>"><?=money_format('%=0!1#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="end_meridiem">
      <option value="AM">AM</option>
      <option value="PM">PM</option>
    </select>
  </p>
  
  <label>Description</label>
  <textarea id="body" name="description" rows="10" cols="60"></textarea>
  
  <input type="hidden" name="calendar_id" value="<?=$calendar->id?>"/>
  <input class="button" type="submit" value="Create!"/>
  <input class="button" type="reset" value="Clear!"/>
</form>