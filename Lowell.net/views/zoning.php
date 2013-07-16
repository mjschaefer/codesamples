<h1 style="margin-bottom:10px;">Board of Zoning Appeals</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'board_of_zoning_appeals'): ?>
    <li><a href="<?=url::site('boards/zoning')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('boards/zoning/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('boards/zoning/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>