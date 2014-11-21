<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class XmlToArrayMoreColorSiteReader
	extends XmlToArrayAbstract
{	
	/**
	 * @return XmlToArrayMoreColorSiteReader
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
		/**
		 * @var SimpleXMLElement
		 */
		$groups = null;
		/**
		 * @var SimpleXMLElement
		 */
		$elements = null;
		
		$results = array();
		
		if(isset($xml->GROUPS)) {
			$this->groups = $xml->GROUPS;
			$this->processGroup($xml->GROUPS);//make files for groups
		}
		
		if(isset($xml->ELEMENTS)) {
			$elements = $xml->ELEMENTS;
		}
		
		foreach($elements->ELEMENT as $element) {
			$group = null;
			
			try{
				$group = $this->getGroup($this->groups,$element->GROUP);
			} catch(Exception $e) {continue;}

			if(isset($group) && !empty($group)) {
				$this->processElement($element, $group,$this->groups);
			}
		}
		
		if(!$this->loadImages){
			foreach($this->files as $file) {
				/* @var $file  FileOutputStream*/
				
				$file->close();
				$this->deleteFileStreams();
			}
			
			$error = array();
			foreach($this->tmpFiles as $tmpFile) {
				$filePath = explode('\\',$tmpFile);
				$name = array_pop($filePath);
				$name = preg_replace("/\.csv$/", '', $name);
				
				if(!rename($tmpFile, $this->getFileName($name))) {
					$error[] = "File [$name] is not moved from tmp to result dir!";
				}
				
				$this->deleteFileNames();
			}
			if(!empty($error))
				return implode(',',$error);
			else
				return '';
		}
	}
	
	public function processGroup(SimpleXmlElement $groups)
	{
		foreach($groups->GROUP as $group) {
			/* @var $group SimpleXmlElement*/
			$this->putHeadersInFile($group->NAME, $group->CODE);
		}
		
		return $this;
	}
	
	public function getGroup(SimpleXmlElement $groups,$groupCode,$main = true)
	{
		return XmlGroupUtil::create()->getGroup($groups, $groupCode, $main);
	}
	
	/**
	 * search group by code
	 * @param SimpleXmlElement $groups
	 * @param type $groupCode
	 * @param type $main
	 * @return SimpleXmlElement
	 * @throws Exception
	 */
	public function getRealGroup(SimpleXmlElement $groups,$groupCode,$main = true)
	{
		return XmlGroupUtil::create()->getRealGroup($groups, $groupCode, $main);
	}
	
	public function processElement(SimpleXmlElement $element,SimpleXmlElement $group,SimpleXmlElement $groups)
	{
		if(!$this->loadImages) {
			/* @var $file FileOutputStream */
			$file = $this->getOutputStream((string)$group->CODE);
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
		
		$results['article'] = 
			$this->clearData($element->ARTICLE);
		
		$results['priceseller'] = 
			$this->clearData(
				$this->processPrice($element, $element->PRICESELLER)
			);
		
		$results['oldprice'] = "";
			
		$results['price'] = 
			$this->clearData(
				$this->processPrice($element, $element->PRICE)
			);
		
		$results['quantity'] = $this->clearData($element->QUANTITY);
		$results['unit'] = "";
		$results['morephoto'] = $this->clearData($this->processMorePhoto($element,$group));
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
	
	public function processDetail(SimpleXmlElement $element,SimpleXmlElement $group)
	{	
		return $this->getGroupProcessor($group)->
			processDetail($element, $this->groups);
	}
	
	public function processMorePhoto(SimpleXmlElement $element,SimpleXmlElement $group)
	{
		return $this->getGroupProcessor($group)->
			setDirToLoad($this->imageLoaderDir)->
			loadImage($this->loadImages)->
			processMorePhoto($element, $this->groups);	
	}
	
	public function processPrice(SimpleXMLElement $element,$str)
	{
		$res = "";
		$currency = $element->CURRENCY;

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