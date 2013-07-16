<h1 style="margin-bottom:10px;">EDC</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'edc'): ?>
    <li><a href="<?=url::site('boards/edc')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('boards/edc/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('boards/edc/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <h1><?=date("F d, Y", $minutes->date)?></h1>
  
  <?=$minutes->body?>
</div>