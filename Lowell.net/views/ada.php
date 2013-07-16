<h1 style="margin-bottom:10px;">Americans With Disabilities Act</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'ada'): ?>
    <li><a href="<?=url::site('ada')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('ada/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>