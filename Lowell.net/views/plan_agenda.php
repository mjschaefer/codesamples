<h1 style="margin-bottom:10px;">Plan Commission</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'plan_commission'): ?>
    <li><a href="<?=url::site('boards/plan')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('boards/plan/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('boards/plan/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <h1><?=date("F d, Y", $agenda->date)?></h1>
  
  <?=$agenda->body?>
</div>