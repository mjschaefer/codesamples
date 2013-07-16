<h1 style="margin-bottom:10px;">Stormwater Drainage</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'stormwater_drainage_dept'): ?>
    <li><a href="<?=url::site('boards/stormwater_dept')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('boards/stormwater_dept/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('boards/stormwater_dept/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <h1><?=date("F d, Y", $agenda->date)?></h1>
  
  <?=$agenda->body?>
</div>