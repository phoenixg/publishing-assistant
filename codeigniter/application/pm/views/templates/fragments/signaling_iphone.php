<?php
error_reporting(E_ALL);
/*
 * Template for iPhone output
 */
?>

<?xml version="1.0" encoding="UTF-8"?>
<feed>
  <title><![CDATA[Science Online]]></title>
  <vol><?php echo $issue->get_volume_num(); ?></vol>
  <issue><?php echo $issue->get_issue_num(); ?></issue>
  <covercaption><?php echo $issue->get_cover_caption(); ?></covercaption>
  <link rel="alternat" type="text/html" href="http://www.sciencemag.org/"/>
  <link rel="self" type="application/atom+xml" href="http://news.sciencemag.org/rss/atom.xml"/>
  <pdf/>
  <id>SIGNALING</id>
  <updated><?php echo $issue->get_publish_date('d M Y'); ?></updated>
  <subtitle>Up to the minute news and features from Science.</subtitle>
    <?php foreach($articles as $article): ?>  	
  <entry>  
    <section><?php echo $article->get_section(); ?></section>
    <title><?php echo $article->get_title(); ?></title>
    <doi><?php echo $article->get_doi(); ?></doi>
    <fpage><?php echo $article->get_fpage(); ?></fpage>
    <fulltext>http://stm.sciencemag.org/content/<?php echo $issue->get_volume_num(); ?>/<?php echo $issue->get_issue_num(); ?>/<?php echo $article->get_fpage(); ?>.full</fulltext>
    <pdf>http://stm.sciencemag.org/cgi/reprint/<?php echo $issue->get_volume_num(); ?>/<?php echo $issue->get_issue_num(); ?>/<?php echo $article->get_fpage(); ?>.pdf</pdf>
    <summarylink>http://stm.sciencemag.org/content/<?php echo $issue->get_volume_num(); ?>/<?php echo $issue->get_issue_num(); ?>/<?php echo $article->get_fpage(); ?>.abstract</summarylink>
    <published><?php echo $article->get_pub_date('d M Y'); ?></published>
    <summary></summary>
 <authors><?php echo $article->get_authors_tostring();?></authors>
    <category><?php echo $article->get_overline(); ?></category>
    <thumbnail>http://content.aaas.org/images/science/default.png</thumbnail>
    <content><![CDATA[<p><strong>ABSTRACT</strong> - <?php echo $article->get_abstracts(); ?>  
</p>]]></content> 
    <?php endforeach; ?>
  </entry>
</feed>                         

