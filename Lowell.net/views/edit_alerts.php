<h1>Alerts</h1>

<form class="item-form" method="post" action="<?=url::site('admin/update_alerts')?>">
  <label>Warning</label>
  <input class="text" type="text" name="warning" value="<?=$alert->warning?>"/>
  
  <label>Notice</label>
  <input class="text" type="text" name="notice" value="<?=$alert->notice?>"/>
  
  <input class="button" type="submit" value="Update!"/>
</form>
<form class="item-form" method="post" action="<?=url::site('admin/clear_alerts')?>">
  <input class="button" type="submit" value="Clear!"/>
</form>