<h1 style="margin-bottom:10px;">Stormwater Management (MS4)</h1>

<div id="sub-menu">
  <h1>Menu</h1>
  <ul>
    <?php foreach($page_list as $link): ?>
    <?php if($link->url == 'stormwater_drainage_ms4'): ?>
    <li><a href="<?=url::site('departments/stormwater')?>">Home</a></li>
    <?php else: ?>
    <li><a href="<?=url::site('departments/stormwater/' . $link->url)?>"><?=$link->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
    <!--<li><a href="<?=url::site('departments/stormwater/reports')?>">Minutes and Agendas</a></li>-->
    <li class="clear-item"> </li>
  </ul>
</div>

<div id="static-page">
  <?=$page->body?>
</div>