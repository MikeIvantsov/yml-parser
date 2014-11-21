<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

abstract class AbstractProcessor
{
	/**
	 *
	 * @var ImageLoader 
	 */
	protected $imageLoader = null;
	
	/**
	 * @return AbstractProcessor
	 */
	public static function create()
	{
		return new self();
	}
	
	abstract public function getName();
	abstract public function processName(SimpleXMLElement $element,SimpleXMLElement $groups);
	abstract public function processDetailText(SimpleXMLElement $element,SimpleXMLElement $groups);
	abstract public function processDetail(SimpleXMLElement $element,SimpleXMLElement $groups);
	abstract public function processTitle(SimpleXMLElement $element,SimpleXMLElement $groups);
	abstract public function processMorePhoto(SimpleXMLElement $element,SimpleXMLElement $groups);
	
	public function setDirToLoad($dir)
	{
		$this->imageLoader->setDirToLoad($dir);
		
		return $this;
	}
}

?>