<script type="text/javascript" src="<?=url::file('js/jquery.js')?>"></script>
<script type="text/javascript" src="<?=url::file('js/jquery.ui.js')?>"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#page_sort').sortable({
      handle : '.handle',
      update : function() {
        var order = $('#page_sort').sortable('serialize');
        $.post('/index.php/admin/boards/sort', order);
      }
    });
  });
</script>

<a href="<?=url::site('admin/boards')?>">&laquo; Back</a>
<h1><?=ucwords( str_replace('_', ' ',$section) )?> Page Manager</h1>
<div class="menubar">
  <a class="manage-link" href="<?=url::site('admin/boards/' . $section . '/minutes')?>">Manage Minutes</a>
  <a class="manage-link" href="<?=url::site('admin/boards/' . $section . '/agendas')?>">Manage Agendas</a>
  
  <ul class="report-items">
    <li><a href="<?=url::site('admin/boards/' . $section . '/add/page')?>">New Page</a></li>
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
      <a class="edit-link" href="<?=url::site('admin/boards/' . $section . '/edit/page/' . $page->url)?>">Edit</a>
      <a class="delete-link" href="<?=url::site('admin/boards/' . $section . '/delete/page/' . $page->url)?>">Delete</a>
    </div>
    <div style="clear:both;"></div>
  </li>
  <?php endforeach; ?>
</ul>

<?php endif; ?>