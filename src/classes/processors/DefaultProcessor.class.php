<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class DefaultProcessor extends AbstractProcessor
{
	protected $template_title_buy = "Купить %text% в интернет-магазине.";
	
	protected $loadImages = false;
	
	/**
	 * @return DefaultProcessor
	 */
	public static function create()
	{
		return new self();
	}

	public function __construct()
	{
		$this->imageLoader = ImageLoader::create();
		$this->imageLoader->setServiceLocator(ServiceLocator::create());
	}
	
	public function getName()
	{
		return 'default';
	}
	
	public function loadImage($loading)
	{
		$this->loadImages = $loading;
		
		return $this;
	}
	
	public function processDetailText(SimpleXMLElement $element,
		SimpleXMLElement $groups)
	{
		
		$detail_text = $this->prepareDetailsText($element);
		
		$text = "";
		
		$text = $this->getDetailText($detail_text);
		
		$text = preg_replace("/<br[^\wа-яА-Я0-9]*/ism"," ",$text);
		
		return $text;
		
		/*
		$str = $element->PREVIEW_PICTURE;
		
		return '<p><a href="' . $str . '"></a></p><p></p>';
		 * 
		 */
	}

	public function processName(SimpleXMLElement $element,
		SimpleXMLElement $groups)
	{
		$detail_text = (string) $element->DETAIL_TEXT;
		
		$detail_text = str_replace("<br/>","<br>",$detail_text);
		$detail_text = str_replace("<br/","<br>",$detail_text);
		$detail_text = str_replace("<br >","<br>",$detail_text);
		$detail_text = str_replace("<br ","<br>",$detail_text);
		
		$detail_textAr = explode("<br>",$detail_text);
		
		if(count($detail_textAr)<3) {
			$type = explode(" ",$detail_text);
			$name = $element->NAME;
			$name = trim(str_replace($type[0], "" ,$element->NAME));
			
			$group = XmlGroupUtil::create()->getGroup($groups, $element->GROUP, true);
			
			$nameAr[] = $type[0];
			$nameAr[] = $group->NAME;
			$nameAr[] = $name;
			
			$text = implode(" ",$nameAr);
			
			return $text;
		}
		
		if(!empty($detail_textAr)) {
			$nameDescription = $detail_textAr[0];
			$name = (string) $element->NAME;
			
			if(!stripos($nameDescription,"Часы")) {
				$text = StampsNameFieldProcessor::create()->process($element, $groups);
			} else 
				$text = $nameDescription . " " . $name;
		} else {
			$text = StampsNameFieldProcessor::create()->process($element, $groups);
		}
		
		$text = preg_replace("/<p[^\wа-яА-Я0-9]+/i","",$text);
		
		return $text;
	}

	public function processTitle(SimpleXMLElement $element,
		SimpleXMLElement $groups)
	{
		$name = $this->processName($element, $groups);
		
		$name_to_template = $name;
		//var_dump(preg_match("/^[а-я]{1}/i",$name));
		if(preg_match("/^[а-яА-Я]{1}/i",$name)) {
			$name_to_template = mb_strtolower(mb_substr($name, 0,1,"UTF-8"),"UTF-8") . mb_substr($name, 1,strlen($name)-1,"UTF-8");
		}
		return $name . "." . str_replace("%text%",$name_to_template,$this->template_title_buy);
	}

	public function getMorePhotoAsBlock(SimpleXmlElement $photos)
	{
		$res = array();
		foreach($photos->PHOTO as $photo) {
			$tmp = (string) $photo;
			$res[] = trim($photo,"\n");
		}
		
		return implode("\n",$res);
	}

	public function processMorePhoto(SimpleXMLElement $element,
		SimpleXMLElement $groups)
	{
		$photos = $this->getMorePhotoAsBlock($element->MOREPHOTO);
		$res = "";
		
		if(!empty($element->DETAIL_PICTURE))
			$res = $element->DETAIL_PICTURE
			.  ((!empty($photos))?"\n" . $photos : "" );
		
		$imageToLoad = explode("\n",$res);
		
		$res = $this->imageLoad($imageToLoad);
		
		$res = implode("\n",$res);
		
		return $res;	
	}
	
	protected function imageLoad($imagesToLoad)
	{
		if(!$this->loadImages){
			$names = array();
			foreach($imagesToLoad as $image) {
				$names[] = $this->imageLoader->getImageNameToSave($image);
			}
			
			return $names;
		} else
			return $this->imageLoader->process($imagesToLoad);
	}

	public function processDetail(SimpleXMLElement $element,
		SimpleXMLElement $groups)
	{
		$detailsAndText = $this->prepareDetailsText($element);
		
		$details = $this->getDetails($detailsAndText);
		
		$data = $this->getDetailsAsArray($details);
		
		unset($data[0]);
		
		return $data;
	}
	
	/**
	 * 
	 * @param SimpleXMLElement $element
	 * @return string
	 */
	protected function prepareDetailsText(SimpleXMLElement $element)
	{
		$detailsAndText = (string) $element->DETAIL_TEXT;
		
		$detailsAndText = trim($detailsAndText,'"');
		$detailsAndText = XmlTextUtil::create()->clearEnter($detailsAndText);
		$detailsAndText = XmlTextUtil::create()->replaceBrBlock($detailsAndText, '<br>');
		
		return $detailsAndText;
	}
	
	protected function getDetailText($details)
	{
		if(preg_match("/^.*<br[^\wа-яА-Я0-9]*?<br[^\wа-яА-Я0-9]*?<br[^\wа-яА-Я0-9]*?>?([\wа-яА-Я0-9]+.*)$/ism",$details,$matches)) {
			return trim($matches[1]);
		}
		
		return $details;
	}
	
	protected function getDetails($details)
	{		
		if(preg_match("/^(.*)<br[^\wа-яА-Я0-9]*?<br[^\wа-яА-Я0-9]*?<br[^\wа-яА-Я0-9]*?>?([\wа-яА-Я0-9]+.*)$/ism",$details,$matches)) {
			return trim($matches[1]);
		}
		
		return $details;
	}
	
	protected function getDetailsAsArray($details,$delim = "<br>")
	{
		return explode($delim,$details);
	}
}

?>