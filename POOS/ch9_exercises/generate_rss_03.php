<?php
require_once '../Pos/RssFeed.php';
require_once '../Pos/MysqlImprovedConnection.php';
require_once '../Pos/MysqlImprovedResult.php';




try {
  $xml = new Pos_RssFeed('localhost', 'psadmin', 'kyoto', 'phpsolutions');
  $xml->setFeedTitle('OOP News');
  $xml->setFeedLink('http://www.example.com/oop_news.xml');
  $xml->setFeedDescription('Get the lowdown on OOP and PHP.');
  $xml->setLastBuildDate(true);
  $xml->setFilePath('oop_news.xml');
  $xml->setItemTitle('title');
  $xml->setItemDescription('article');
  $xml->setItemPubDate('updated');
  $xml->setTable('blog');
  $result = $xml->generateXML();
  if ($result) {
    // echo 'XML file created';
  }
  else {
    echo 'Error';
  }
}
catch (Exception $e) {
  echo $e->getMessage();
}

?> 