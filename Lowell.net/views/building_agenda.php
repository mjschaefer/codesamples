<h1 style="margin-bottom:10px;">The Building Department</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'building_department'): ?>
    <li><a href="<?=url::site('departments/building')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('departments/building/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('departments/building/reports')?>">Minutes and Agendas</a></li>
    <li><a href="<?=url::site('boards/zoning')?>">Board of Zoning Appeals</a></li>
    <li><a href="<?=url::site('boards/plan')?>">Plan Commission</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <h1><?=date("F d, Y", $agenda->date)?></h1>
  
  <?=$agenda->body?>
</div>