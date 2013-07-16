<h1>The Sewer Department</h1>
<h2 style="margin-bottom:10px;">of the Public Works</h2>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'sewer_dept'): ?>
    <li><a href="<?=url::site('departments/sewer')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('departments/sewer/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>