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

<div id="report-years">
  <div id="minutes">
    <h1>Minutes</h1>
        
    <?php if(count($minute_years) > 0): ?>
    
    <?php foreach($minute_years as $year): ?>
    <h2><a href="<?=url::site('council/reports/' . $year->year . '/minutes')?>"><?=$year->year?></a></h2>
    <?php endforeach; ?>
    
    <?php else: ?>
    <p>No minutes to display</p>
    <?php endif; ?>
  </div>
  
  <div id="agendas">
    <h1>Agendas</h1>
    <?php if(count($agenda_years) > 0): ?>
    
    <?php foreach($agenda_years as $year): ?>
    <h2><a href="<?=url::site('council/reports/' . $year->year . '/agendas')?>"><?=$year->year?></a></h2>
    <?php endforeach; ?>
    
    <?php else: ?>
    <p>No agendas to display</p>
    <?php endif; ?>
  </div>
</div>