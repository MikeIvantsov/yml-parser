<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class PhilosofersProcessor  extends AbstractProcessor
{

	/**
	 * @return PhilosofersProcessor
	 */
	public static function create()
	{
		return new self();
	}

	public function processDetailText(\SimpleXMLElement $element,
		\SimpleXMLElement $groups)
	{
		
	}

	public function processName(\SimpleXMLElement $element,
		\SimpleXMLElement $groups)
	{
		return StampsNameFieldProcessor::create()->process($element, $groups);
	}

	public function getName()
	{
		return 'philosophers';
	}


}

?>