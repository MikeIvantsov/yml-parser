<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class StampsProcessor extends AbstractProcessor
{

	/**
	 * @return StampsProcessor
	 */
	public static function create()
	{
		return new self();
	}
	
	public function processName(SimpleXMLElement $element,SimpleXMLElement $groups)
	{
		return StampsNameFieldProcessor::create()->process($element, $groups);
	}

	public function processDetailText(\SimpleXMLElement $element,
		\SimpleXMLElement $groups)
	{
		
	}

	public function getName()
	{
		return 'stamps';
	}
	

}

?>