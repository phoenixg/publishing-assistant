<?php
/**
* Fragment for STM articles, container for articles
*/
?>
<div class="sci-block">
  <div class="overline titler glare">
    Volume <?php echo $issue->get_volume_num(); ?>
  </div>
  <dl class="article-list" id="sci-stm-minifeed">
    <dt>
    <span class="overline">
      <span class="loud">Cover Story</span> | <?php echo $articles[0]->get_overline(); ?>
    </span>
    <span class="item-title">
      <a href="<?php echo $articles[0]->get_url(); ?>">
      <?php echo $articles[0]->get_title() ?>
      </a>
    </span>
    </dt>
    <dd class="thumb">
    <ul class="author-list">
      <?php foreach ($articles[0]->get_authors_list() as $author): echo "<li>{$author}</li>"; endforeach; ?>
    </ul>
    </dd>
    <?php for($i = 1; $i < count($articles); $i++) : ?>
    <dt class="thumb">
    <img class="thumbnail" src="[THUMBNAIL]" height="60" width="60" alt="">
    <span class="overline"><?php echo $articles[$i]->get_overline(); ?></span>
    <span class="item-title"><a href="<?php $articles[$i]->get_url(); ?>"><?php echo $articles[$i]->get_title(); ?></a></span>
    </dt>
    <dd class="thumb">
    <ul class="author-list">
      <?php foreach ($articles[$i]->get_authors_list() as $author): echo "<li>{$author}</li>"; endforeach; ?>
    </ul>
    </dd>
    <?php endfor; ?>
  </dl>
</div>
