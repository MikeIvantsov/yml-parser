<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class XmlHttpLoader
{
	/**
	 *
	 * @var IServiceLocator 
	 */
	private $serviceLocator;
	
	/**
	 * @return XmlHttpLoader
	 */
	public static function create()
	{
		return new self();
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

	public function makeXml($xml) {
		Assert::isNotEmpty($xml, "Bad xml string: '{$xml}'");

		try {
			return new SimpleXMLElement($xml);
		} catch (Exception $e) {
			throw new WrongArgumentException(
				"Unable to create xml from string: '{$xml}'"
				. "\n\nerorr:$e->getMessage()"
			);
		}
	}
}

?>