<h1 style="margin-bottom:10px;">Redevelopment Commission</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'redevelopment_commission'): ?>
    <li><a href="<?=url::site('boards/redevelopment')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('boards/redevelopment/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li><a href="<?=url::site('boards/redevelopment/reports')?>">Minutes and Agendas</a></li>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>