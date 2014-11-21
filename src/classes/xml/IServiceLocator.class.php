<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

interface IServiceLocator
{
	/**
	 * 
	 * @return IServiceLocator
	 */
	public static function create();
	/**
	 * 
	 * @return CurlHttpClient
	 */
	public function spawnCurlClient();

}

?>