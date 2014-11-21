<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class TACSProcessor extends DefaultProcessor
{	
	/**
	 * @return TACSProcessor
	 */
	public static function create()
	{
		return new self();
	}

	public function getName()
	{
		return 'tacs';
	}
	
	public function processDetail(SimpleXMLElement $element,
		SimpleXMLElement $groups)
	{
		$detailsAndText = $this->prepareDetailsText($element);
		
		$details = $this->getDetails($detailsAndText);
		
		$details = $this->getDetailsAsArray($details);
		
		if((string)$element->NAME == "Lens-M-C") {
			foreach($details as $k=>$detail) {
				if($k>7) { 
					$data = explode(":",$detail);
					$dataExist = explode(":",$details[$k-5]);
					
					if(
						isset($data[1]) && isset($dataExist[1])
						&& trim($dataExist[0]) == trim($data[0])
						&& trim($dataExist[1]) != trim($data[1])
					) {
						$details[$k-5] =  $dataExist[0] . ":" . trim($dataExist[1]) . " / ". trim($data[1]);
					}
				}
			}
			
			$details = array_slice($details,0,7,true);
		}
		
		return $details;
	}
	
	protected function getDetailText($details)
	{
		$detailText = parent::getDetails($details);
		
		if(!is_null($detailText)){
			if(preg_match("/^.*<br[^\wа-яА-Я0-9]*?([\wа-яА-Я0-9]+.*)$/ism",$detailText,$matches)) {
				$text = trim($matches[1]);
			} else {
				$text = $detailText;
			}
		} else 
			$text = "";
		
		return $text;
	}
	
	protected function getDetails($details)
	{
		return parent::getDetailText($details);
	}
}

?>