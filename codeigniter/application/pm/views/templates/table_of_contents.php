<?php 
?>
<h1><?php echo $issue->get_name(); ?></h1> 

<h2>Vol: <?php echo $issue->get_volume_num(); ?> Issue: <?php echo $issue->get_issue_num();?></h2>
<h3><?php echo $issue->get_data_source(); ?></h3>
<ul>
<?php 
$current = "";
foreach ($articles as $article): 
  if ($article->get_article_type() != $current) {
    $current = $article->get_article_type();
    echo "<h3>" . $current . "</h3>";
  } 
echo "<li><a href='" . $article->get_url() . "'>" 
  . $article->get_title() . "</a> page: " 
  . $article->get_fpage() . " doi: " 
  . $article->get_doi() . "{"
  . join(', ', $article->get_fields()) . "}";
echo "<p>ABSTRACT: <em> {$article->get_abstracts()} </em></p>";
endforeach; 
?>
</ul>

