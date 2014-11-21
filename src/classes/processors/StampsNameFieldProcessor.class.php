<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class StampsNameFieldProcessor extends NameFieldProcessor
{
	private $isNotClock = array(
		"drugie_aksessuari",
		"remeshki_stamps"
	);
	/**
	 * @return StampsNameFieldProcessor
	 */
	public static function create()
	{
		return new self();
	}

	public function process(SimpleXMLElement $element,SimpleXMLElement $groups)
	{
		$result = "";
		
		if(
			$this->isNotClock(
				XmlGroupUtil::create()->getRealGroup($groups, $element->GROUP)->CODE
			)
		) {
			$result = $this->processOther($element, $groups);
		} else {
			$result = $this->processClock($element, $groups);
		}
			
		return $result;
	}
	
	public function processClock(SimpleXMLElement $element,SimpleXMLElement $groups)
	{
		$pref = "Часы ";
		
		return  $pref
			. XmlGroupUtil::create()->getGroup($groups, $element->GROUP)->NAME
			. " "
			. $element->NAME;
	}
	
	public function processOther(SimpleXMLElement $element,SimpleXMLElement $groups)
	{
		$pref = "";
		
		return  $pref
			. XmlGroupUtil::create()->getGroup($groups, $element->GROUP)->NAME
			. " "
			. $element->NAME;
	}
	
	private function isNotClock($str)
	{
		$isNotClock = array_map("strtolower", $this->isNotClock);
		
		$str = strtolower($str);
		
		return in_array($str,$isNotClock);
	}
}

?>