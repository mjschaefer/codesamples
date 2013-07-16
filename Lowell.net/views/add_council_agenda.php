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

<a href="<?=url::site('admin/councils/agendas')?>">&laquo; Back</a>
<h1>Add Agenda</h1>

<form id="item-form" method="post" action="<?=url::site('admin/councils/create/agenda')?>">

  <label>On What Date?</label>
  <p>
    <select class= "datetime" name="month">
      <?php for($i = 1; $i < 13; $i++): ?>
      <option value="<?=$i?>"><?=date("M", mktime(0,0,0,$i,1) )?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="day">
      <?php for($i = 1; $i < 32; $i++): ?>
      <option value="<?=money_format('%=0!2#1.0n', $i)?>"><?=money_format('%=0!2#1.0n', $i)?></option>
      <?php endfor; ?>
    </select>
  
    <select class="datetime" name="year">
      <?php for($i = 2009; $i < 2014; $i++): ?>
      <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
    <div style="clear:both;padding-top:10px;"></div>
  </p>
  
  <label>Report</label>
  <textarea class="body" name="body" rows="10" cols="60"></textarea>
  
  <input class="button" type="submit" value="Create!"/>
  <input class="button" type="reset" value="Clear!"/>
</form>