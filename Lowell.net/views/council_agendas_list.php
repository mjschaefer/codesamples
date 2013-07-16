<a href="<?=url::site('admin/councils')?>">&laquo; Back</a>
<h1>Council Agenda</h1>
<div class="menubar">
  <ul>
    <li><a href="<?=url::site('admin/councils/add/agenda')?>">New Agenda</a></li>
  </ul>
</div>

<?php if(count($agendas) == 0): ?>
  <p style="text-align:center">There are no reports to list.</p>
<?php else: ?>
  
<?php foreach($agendas as $agenda): ?>
<div id="<?=$agenda->id?>" class="list-item">
  <h1><?=date("F d, Y", $agenda->date);?></h1>
  <div class="options">
    <a class="edit-link" href="<?=url::site('admin/councils/edit/agenda/' . $agenda->id)?>">Edit</a>
    <a class="delete-link" href="<?=url::site('admin/councils/delete/agenda/' . $agenda->id)?>">Delete</a>
  </div>
</div>
<?php endforeach; ?>

<?php endif; ?>