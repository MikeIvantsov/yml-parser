<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class XmlToArrayMoreColorSiteYandexTypeReader
	extends XmlToArrayAbstract
{	
	/**
	 * @return XmlToArrayMoreColorSiteYandexTypeReader
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 * @return array
	 */
	public function process(SimpleXMLElement $xml)
	{
		foreach($xml->offers as $offer) {
			$this->processElement($offer);
		}
		
		if(!$this->loadImages){
			foreach($this->files as $file) {
				/* @var $file  FileOutputStream*/
				$file->close();
			}
		}
	}
	
	public function createFileIsNotExist($vendorName)
	{
		$codeName = $this->getcodeNameByVendorName($vendorName);
		
		if(!file_exists($this->getFileName($vendorName))){
			$this->putHeadersInFile($vendorName, $codeName);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $code
	 * @return FileOutputStream
	 */
	public function getOutputStream($code)
	{
		$code = $this->getcodeNameByVendorName($code);
		
		return parent::getOutputStream($code);
	}
	
	public function getGroupProcessor(\SimpleXmlElement $group)
	{
		$group = $this->getcodeNameByVendorName((string) $group);
		
		parent::getGroupProcessor($group);
	}
	
	public function getcodeNameByVendorName($vendorName)
	{
		return strtolower(preg_replace("/[^\w]/is", '_', $vendorName));
	}
	
	public function processElement(SimpleXmlElement $element)
	{
		$this->createFileIsNotExist((string)$element->vendor);
		
		if(!$this->loadImages) {
			/* @var $file FileOutputStream */
			$file = $this->getOutputStream((string)$element->vendor);
		}
		$results = array();
		
		$name = $this->processName(
			$element,
			$group
		);
		
		$details = $this->processDetail($element, $group);
		
		$results['name'] = $this->clearData($name);
		$results['text'] = "";
		if(isset($details[0]))
			$results['detail_text'] = $this->clearData($this->processDetailText($element,$group));
		else
			$results['detail_text'] = "";
		
		$results['article'] = '';
		
		$results['priceseller'] = '';
		
		$results['oldprice'] = "";
			
		$results['price'] = 
			$this->clearData(
				$this->processPrice($element, $element->price)
			);
		
		$results['quantity'] = '';
		$results['unit'] = "";
		$results['morephoto'] = $this->clearData($this->processMorePhoto($element));
		$results['short_seo'] = "";
		$results['seo'] = "";
		$results['title'] = $this->processTitle($element, $group);
		$results['meta_keywords'] = "";
		$results['meta_descr'] = "";
		$results['url'] = "";
		$results['mods_view'] = "";
		
		foreach($details as $k=>$detail) {
			if($k<8){
				$data = array();
				if(strpos($detail, ":"))
					$data = explode(":",$detail);
				else
					$data[0] = $detail;
				/*else if(strpos($detail, "-"))
					$data = explode("-",$detail);
				*/
				if(!isset($data[0]))
					$data[0] = '';
				if(!isset($data[1]))
					$data[1] = '';	
				
				$results['name_param'.($k+1)] = $this->clearData($data[0]);
				$results['val_param'.($k+1)] = $this->clearData($data[1]);
			} else {
				if(!empty($detail)){
					if($k == 8)
						$results['name_param'.(8)] .= "/доп свойства:";
					$results['val_param'.(8)] .= "/".$this->clearData($detail);
				}
			}
		}
		
		$results['name_mod_param1'] = "";
		$results['val_mod_param1'] = "";
		
		
		/*-------------*/
		$this->writeItemInFile($file,$results);
		
		return $this;
	}
	
	public function processDetail(SimpleXmlElement $element)
	{	
		return $this->getGroupProcessor($element->vendor)->
			processDetail($element, $this->groups);
	}
	
	public function processMorePhoto(SimpleXmlElement $element)
	{
		return $this->getGroupProcessor($group)->
			setDirToLoad($this->imageLoaderDir)->
			loadImage($this->loadImages)->
			processMorePhoto($element, $this->groups);	
	}
	
	public function processPrice(SimpleXMLElement $element,$str)
	{
		$res = "";
		$currency = (string) $element->currencyId;
		
		$currency = strtolower($currency);

		if(!empty($str))
			$res = $str . " " . ((!empty($currency))?$currency:"");
		
		return $res;
	}
	
	public function processUrl($str)
	{
		return preg_replace("/\s/",'-',$str);
	}
	
	public function processDetailText(SimpleXmlElement $element,SimpleXmlElement $group)
	{	
		return $this->getGroupProcessor($group)->
			processDetailText($element, $this->groups);
	}
	
	public function processName(SimpleXMLElement $element,SimpleXMLElement $group)
	{
		return $this->getGroupProcessor($group)->
			processName($element, $this->groups);
	}
	
	public function processTitle(SimpleXMLElement $element,SimpleXMLElement $group)
	{
		return $this->getGroupProcessor($group)->
			processTitle($element, $this->groups);
	}
	
}

?>