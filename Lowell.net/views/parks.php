<h1 style="margin-bottom:10px;">The Parks Department</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'parks_department'): ?>
    <li><a href="<?=url::site('departments/parks')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('departments/parks/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('calendar/park')?>">Event Calendar</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>