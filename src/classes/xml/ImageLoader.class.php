<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class ImageLoader
{
	protected $dirToLoad = null;
	
	/**
	 *
	 * @var IServiceLocator 
	 */
	private $serviceLocator;
	
	/**
	 * @return ImageLoader
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 * 
	 * @param string $dir
	 * @return \ImageLoader
	 */
	public function setDirToLoad($dir)
	{
		$this->dirToLoad = $dir;
		
		return $this;
	}
	
	/**
	 * 
	 * @param type $imagesToLoad
	 */
	public function process($imagesToLoad)
	{
		
		$imageList = array();
		$site = "http://morecolor.ru";
		$siteWithWWW = "http://www.morecolor.ru";
		
		foreach($imagesToLoad as $imageUrl) {
			if(!empty($imageUrl)){
				if(
					stripos($imageUrl,$site) === false
					&& stripos($imageUrl,$siteWithWWW) === false
				)
					$imageUrl = $site . $imageUrl;

				$name = $this->getImageNameToSave($imageUrl);

				$file = $this->dirToLoad . $name;

				$imageList[] = $name;

				if(!file_exists($file)) {
					if(stripos($imageUrl," ") === false){
						try{
							$imageLoaded = $this->load($imageUrl);
							//$imageLoaded = file_get_contents($imageUrl);

							file_put_contents($file, $imageLoaded);
						} catch(Exception $e) {
							echo $e->getMessage() . "<br> url:" . $imageUrl ."<br>";
						}

					}
				}
			}
		}
		
		return $imageList;
	}
	
	public function getImageNameToSave($imageUrl)
	{
		/*
		$path = str_replace("http://morecolor.ru/images/catalog/","",$imageUrl);
		$path = str_replace("http://www.morecolor.ru/images/catalog/","",$path);
		$name = str_replace("/", "_", $path);
			
		$name = str_replace(" ","_",$name);
		*/
		
		$site = "http://morecolor.ru";
		$siteWithWWW = "http://www.morecolor.ru";
		
		if(
					stripos($imageUrl,$site) === false
					&& stripos($imageUrl,$siteWithWWW) === false
				)
					$imageUrl = $site . $imageUrl;
		
		$name = $imageUrl;
			
		return $name;
	}
	
	public function load($url)
	{
		$httpUrl = HttpUrl::create()->parse($url);

		$request = HttpRequest::create()->
			setMethod(HttpMethod::get())->
			setUrl($httpUrl);

		$success = false;
		$triesLeft = 5;
		while (!$success && $triesLeft--) {
			try {
				$responce = $this->
					serviceLocator->
					spawnCurlClient()->
					setTimeout(10)->
					send($request);
				$success = true;
			} catch (NetworkException $e) {
				// Ошибка загрузки страницы. Игнорируем
			}
		}

		if (!$success) {
			throw $e;
		}
		
		Assert::isEqual(
			$responce->getStatus()->getId(),
			HttpStatus::CODE_200,
			"Bad response status: {$responce->getStatus()->getId()}"
		);

		Assert::isGreater(
			mb_strlen($responce->getBody()),
			0,
			'Remote service return empty string'
		);

		return $responce->getBody();
	}
	
	/**
	 * @param IServiceLocator $serviceLocator
	 * @return XmlHttpLoader
	 */
	public function setServiceLocator(IServiceLocator $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;

		return $this;
	}
	
	
}

?>