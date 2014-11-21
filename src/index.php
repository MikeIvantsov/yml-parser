<?php

/* * *************************************************************************
 *   Copyright (C) 2012 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

set_time_limit(0);

require("config.inc.php");
header("Content-Type: text/html; charset=utf-8");
$url_original = "http://morecolor.ru/xml/mod.php";
//$url = "http://morecolor.ru/market/yandex.php";
$url_yandex = "http://localhost/project_xml_to_csv/xml_to_csv/src/decoderToOldXml.php";

$urls = array(
	$url_yandex,
	$url_original
);

$dir = dirname(__FILE__) . "\\..\\..\\downloads\\result\\";
$tmpDir = dirname(__FILE__) . "\\..\\..\\downloads\\tmp\\";
$dirToLoadImages = dirname(__FILE__) . "\\..\\..\\downloads\\image\\";

try{
	
	$loader = XmlHttpLoader::create();
	$loader->setServiceLocator(ServiceLocator::create());
	
	$xmlReader = XmlToArrayMoreColorSiteReader::create();
	   $xmlReader->
		   setGroupProcessor(DefaultProcessor::create())->
		   setGroupProcessor(TACSProcessor::create());
   //		setGroupProcessor(StampsProcessor::create());
   //		setGroupProcessor(PhilosofersProcessor::create());

	$xmlReader->
		setDir($dir)->
		setTmpDir($tmpDir);
	
	foreach($urls as $url) {
		$content = $loader->load($url);

		$content = str_replace("\n","",$content);
		
		/**
		* @var SimpleXMLElement;
		*/
	   $xml = $loader->makeXml($content);
	   
	   if(isset($_GET['image']) && $_GET['image']==1) {
		   $xmlReader->
		   setDirImage($dirToLoadImages)->
		   loadImages();
	   }

	   $data = $xmlReader->process($xml);
	   
	   if(!empty($data))
		   echo $data . "\n";
	   //var_dump($data);
	   
	}
	echo "преобразование завершено";

} catch(Exception $e) {
	echo $e->getMessage() . "\r\n" . $e->getTraceAsString();
	exit(1);
}
?>