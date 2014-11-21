<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */
class GroupNotFoundException extends Exception{}

class XmlGroupUtil
{

	/**
	 * @return XmlGroupUtil
	 */
	public static function create()
	{
		return new self();
	}

	public function getGroup(SimpleXmlElement $groups,$groupCode,$main = true) {
		$mainGroup = null;
		
		foreach($groups->GROUP as $group) {
			/* @var $group SimpleXmlElement*/
			
			if(isset($group->GROUPS)) {
				$mainGroup = $this->getGroup($group->GROUPS, $groupCode,false);
			}
			
			if((string)$group->CODE == (string) $groupCode)
				$mainGroup =  $group;
			
			if($main && !empty($mainGroup)) {
				$mainGroup = $group;
				break;
			}
		}
		
		if($main && is_null($mainGroup))
			throw new GroupNotFoundException("Group not found!");
		
		return $mainGroup;
	}
	
	/**
	 * @depricated
	 * use getSubGroup
	 * @param SimpleXmlElement $groups
	 * @param type $groupCode
	 * @param type $main
	 * @return SimpleXmlElement
	 * @throws Exception
	 */
	public function getRealGroup(SimpleXmlElement $groups,$groupCode,$main = true)
	{
		return $this->getSubGroup($groups, $groupCode,$main);
	}
	
	/**
	 * search group by code
	 * @param SimpleXmlElement $groups
	 * @param type $groupCode
	 * @param type $main
	 * @return SimpleXmlElement
	 * @throws Exception
	 */
	public function getSubGroup(SimpleXmlElement $groups,$groupCode,$main = true) {
		$mainGroup = null;
		
		foreach($groups->GROUP as $group) {
			/* @var $group SimpleXmlElement*/
			
			if(isset($group->GROUPS)) {
				$mainGroup = $this->getSubGroup($group->GROUPS, $groupCode,false);
			}
			
			if((string)$group->CODE == (string) $groupCode)
				$mainGroup = $group;
			
			if(!empty($mainGroup))
				break;
		}
		
		if($main && is_null($mainGroup))
			throw new GroupNotFoundException("Group not found!");
		
		return $mainGroup;
	}
}

?>