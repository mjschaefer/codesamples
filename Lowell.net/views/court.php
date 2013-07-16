<h1 style="margin-bottom:10px;">Lowell Town Court</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'town_court'): ?>
    <li><a href="<?=url::site('court')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('court/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>