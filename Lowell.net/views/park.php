<h1 style="margin-bottom:10px;">Park Board</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'park_board'): ?>
    <li><a href="<?=url::site('boards/park')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('boards/park/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('boards/park/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>