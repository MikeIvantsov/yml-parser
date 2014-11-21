<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class GroupDecoder
{
	protected static $groupId = 0;
	/**
	 * @return GroupDecoder
	 */
	public static function create()
	{
		return new self();
	}
	
	public function createByElement(SimpleXMLElement $element,$dataGroup)
	{	
		$result = '<GROUP>'
			. $this->createId(++self::$groupId)
			. $this->createName((string)$element->vendor)
			. $this->createActive('true')
			. $this->createCode((string)$element->vendor)
			. $this->createGroups('')
			. '</GROUP>';	
		
		$res = array(
			'code'=> (string)$element->vendor,
			'text'=> $result
		);
		
		return $res;
	}
	
	protected function createId($id)
	{
		return "<ID>$id</ID>";
	}
	
	protected function createName($name)
	{
		return "<NAME>$name</NAME>";
	}
	
	protected function createActive($active)
	{
		return "<ACTIVE>$active</ACTIVE>";
	}
	
	protected function createCode($code)
	{
		return "<CODE>$code</CODE>";
	}
	
	protected function createGroups($groups)
	{
		return "<GROUPS>$groups</GROUPS>";
	}
	
/*
 <GROUPS>
 * <GROUP>
 * <ID>73</ID>
 * <NAME>La Mer Collections</NAME>
 * <ACTIVE>true</ACTIVE>
 * <CODE>la_mer_collections</CODE>
 * <GROUPS>
 *	</GROUPS>
 * </GROUP>
 </GROUPS> 
 */
}

?>