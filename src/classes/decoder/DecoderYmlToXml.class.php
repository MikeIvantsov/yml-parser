<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class DecoderYmlToXml
{
	protected $groups = array();
	/**
	 * @return DecoderYmlToXml
	 */
	public static function create()
	{
		return new self();
	}

	public function process(SimpleXMLElement $xml)
	{
		$result = '';
		
		$result .= "<GROUPS>";
		
		foreach($xml->offers as $offer)
		{
			foreach($offer->offer as $offerElement)
			{
				if($this->checkGroupExist($offerElement))
					continue;
				
				$dataGroup = $this->createGroup($offerElement,$this->groups);
				$this->groups[$dataGroup['code']] = $dataGroup;
				
				$result .= $dataGroup['text'];
			}
		}
		
		$result .= "</GROUPS>";
		
		$result .= "<ELEMENTS>";
		
		foreach($xml->offers as $offer)
		{
			foreach($offer->offer as $offerElement)
			{
				$result .= $this->createElement($offerElement);
			}
		}
		
		$result .= "</ELEMENTS>";
		
		return $result;
	}
	
	protected function createElement(SimpleXMLElement $element)
	{
	    $type = '';
	    foreach($element->attributes() as $k => $v) {
		if($k == 'type')
		    $type = $v;
	    }

	    switch($type) {
		case 'vendor.model':
		    return ElementDecoder::create()->createByElement($element);
		    break;
		case 'book':
		    throw new Exception('unsupported logic!');
		    break;
		default:
		    return ElementDecoder::create()->createByElement($element);    
		    break;
	    }
	}
	
	protected function createGroup(SimpleXMLElement $element,array $dataGroup)
	{
		return GroupDecoder::create()->createByElement($element,$dataGroup);
	}
	
	protected function checkGroupExist(SimpleXMLElement $element)
	{
		foreach($this->groups as $group) {
			if($group['code'] == (string) $element->vendor)
				return true;
		}
		
		return false;
	}
	
}

?>