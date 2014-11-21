<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */
set_time_limit(0);

require("config.inc.php");

$url = "http://morecolor.ru/market/yandex.php";

header("Content-Type: text/xml; charset=utf-8");

try{
	$loader = XmlHttpLoader::create();

	$content = $loader->setServiceLocator(ServiceLocator::create())->load($url);

	$content = str_replace("\n","",$content);
	
	/**
	 * @var SimpleXMLElement;
	 */
	$xml = $loader->makeXml($content);
	
	echo '<?xml version="1.0" encoding="UTF-8"?><COMMERCIALINFORMATION ВерсияСхемы="2.021" ДатаФормирования="2013-07-12T20:18:28+00:00">';
	echo DecoderYmlToXml::create()->process($xml->shop);
	echo '</COMMERCIALINFORMATION>';
	
} catch(Exception $e) {
	echo $e->getMessage() . "\r\n" . $e->getTraceAsString();
	exit(1);
}
?>