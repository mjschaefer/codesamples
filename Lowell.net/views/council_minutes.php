<h1 style="margin-bottom:10px;">Lowell Town Council</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'town_council'): ?>
    <li><a href="<?=url::site('council')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('council/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('council/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <h1><?=date("F d, Y", $minutes->date)?></h1>
  
  <?=$minutes->body?>
</div>