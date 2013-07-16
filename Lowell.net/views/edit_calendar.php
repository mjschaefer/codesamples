<a href="<?=url::site('admin/calendars')?>">&laquo; Back</a>
<h1>Edit Calendar</h1>

<form class="item-form" method="post" action="<?=url::site('admin/calendars/update')?>">
  <label>Name</label>
  <input class="text" type="text" name="name" value="<?=$calendar->name?>"/>
  
  <input type="hidden" name="id" value="<?=$calendar->id?>"/>
  <input class="button" type="submit" value="Update!"/>
  <input class="button" type="reset" value="Reset!"/>
</form>