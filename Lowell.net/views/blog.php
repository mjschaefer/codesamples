<div id="news-box">
  
  <?php if(count($posts) == 0): ?>
    <p style="text-align:center">There are no posts to display.</p>
  <?php else: ?>
  <?php foreach($posts as $post): ?>
  <div class="news-post">
    <h1><?=$post->title?></h1>
    <h2>Posted on <?=date("F d, Y", $post->time);?></h2>
    <p>
      <?=$post->body?>
    </p>
  </div>
  <?php endforeach; ?>
  
  <p class="pagination-links"><?=$pagination?></p>
  <?php endif; ?>

</div>