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

<div id="report-list">
    <h1><?=$title?></h1>
        
    <?php if(count($reports) > 0): ?>
    
    <?php foreach($reports as $report): ?>
    <h2><a href="<?=url::site('boards/edc/' . $report->type . '/' . $report->month . '/' . $report->day . '/' . $report->year)?>"><?=date("F d, Y", $report->date);?></a></h2>
    <?php endforeach; ?>
    
    <?php else: ?>
    <p>No reports to display</p>
    <?php endif; ?>
</div>