<?php
require_once '../Pos/RssFeed.php';
require_once '../Pos/MysqlImprovedConnection.php';
require_once '../Pos/MysqlImprovedResult.php';




try {
  $xml = new Pos_RssFeed('localhost', 'psadmin', 'kyoto', 'phpsolutions');
//  $xml->setQuery('SELECT * FROM blog');
  $xml->setFilePath('rss1.xml');
  $xml->setFeedTitle('OOPS News');
  $xml->setFeedLink('http://www.example.com/oops_news.xml');
  $xml->setFeedDescription('All the latest news about oopsa-daisy PHP programming.');
  $xml->setLastBuildDate(true);
  $xml->setItemTitle('title');
  $xml->setItemDescription('article', 3);
  $xml->setItemPubDate('updated');
  $xml->setTable('blog', 2);   
  $xml->setItemLink('article_id');
  //$xml->setItemLinkURL('http://www.example.com/detail.php'); 

  $result = $xml->generateXML();
  if ($result) {
      /*header('Content-Type: text/xml');*/
    echo $result;
  }
  else {
    echo 'Error';
  }
}
catch (Exception $e) {
  echo $e->getMessage();
}

?> 