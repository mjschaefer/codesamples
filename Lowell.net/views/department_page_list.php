<script type="text/javascript" src="<?=url::file('js/jquery.js')?>"></script>
<script type="text/javascript" src="<?=url::file('js/jquery.ui.js')?>"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#page_sort').sortable({
      handle : '.handle',
      update : function() {
        var order = $('#page_sort').sortable('serialize');
        $.post('/index.php/admin/departments/sort', order);
      }
    });
  });
</script>

<a href="<?=url::site('admin/departments')?>">&laquo; Back</a>
<h1><?=ucwords( str_replace('_', ' ',$section) )?> Page Manager</h1>
<div class="menubar">
  <?php if($section == "building"): ?>
  <a class="manage-link" style="margin-bottom:20px;" href="<?=url::site('admin/departments/' . $section . '/minutes')?>">Manage Minutes</a>
  <a class="manage-link" style="margin-bottom:20px;" href="<?=url::site('admin/departments/' . $section . '/agendas')?>">Manage Agendas</a>
  <?php endif; ?>
  
  <ul style="margin-top:20px;">
    <li><a href="<?=url::site('admin/departments/' . $section . '/add/page')?>">New Page</a></li>
  </ul>
</div>

<?php if(count($pages) == 0): ?>
  <p style="text-align:center">There are no pages to list.</p>
<?php else: ?>

<ul id="page_sort">  
  <?php foreach($pages as $page): ?>
  <li id="sort_<?=$page->id?>" class="list-item sortable">
    <img src="<?=url::file('imx/icons/move_arrow.png')?>" class="handle" alt=""/>
    <h1><?=$page->title?></h1>
    <div class="options">
      <a class="edit-link" href="<?=url::site('admin/departments/' . $section . '/edit/page/' . $page->url)?>">Edit</a>
      <a class="delete-link" href="<?=url::site('admin/departments/' . $section . '/delete/page/' . $page->url)?>">Delete</a>
    </div>
    <div style="clear:both;"></div>
  </li>
  <?php endforeach; ?>
</ul>

<?php endif; ?>