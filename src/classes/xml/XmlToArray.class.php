<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

interface XmlToArray
{

	/**
	 * @return XmlToArray
	 */
	public static function create();
	
	/**
	 * @return array
	 */
	public function process(SimpleXMLElement $xml);
}

?>