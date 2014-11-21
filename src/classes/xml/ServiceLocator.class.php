<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class ServiceLocator implements IServiceLocator
{

	/**
	 * @return ServiceLocator
	 */
	public static function create()
	{
		return new self();
	}
	
	/**
	 * 
	 * @return CurlHttpClient
	 */
	public function spawnCurlClient() {
		return CurlHttpClient::create();
	}

}

?>