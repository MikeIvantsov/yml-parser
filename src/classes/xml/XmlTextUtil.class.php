<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class XmlTextUtil
{

	/**
	 * @return XmlTextUtil
	 */
	public static function create()
	{
		return new self();
	}

	public function clearEnter($str,$replace = '')
	{
		return preg_replace("/\r*\n/ims", $replace, $str);
	}
	
	 /* 
	 * @param string $str
	 * @return string
	 */
	public function replaceBrBlock($str,$replace = '')
	{
		return preg_replace("/\<br[^\/\>]*[\/]*>+?/ims", $replace, $str);
	}
	
	/**
	 * 
	 * @param mixed $str
	 * @return string
	 */
	public function clearData($str)
	{			
		$str = str_replace(
			';',
			'.',
			str_replace(
				'"',
				'""',
				trim((string) $str)
			)
		);
		
		return "\"" . $str . "\"";
	}
	
	/**
	 * 
	 * @param array $data
	 * @return string
	 */
	public function concatBySemicolon($data = array())
	{
		if(!is_null($data))
			return implode(";",$data);
		else
			return '';
	}
}

?>