<?php

	/*	 * *************************************************************************
	 *   Copyright (C) 2014 by Mikhail Ivantsov                                *
	 *                                                                         *
	 * ************************************************************************* */

	class ShopItem
	extends XmlItem
	{
		protected $name = null;
		protected $company = null;
		protected $url = null;
		protected $platform = null;
		protected $version = null;
		protected $agency = null;
		protected $email = null;
		protected $currencies = array();
		protected $categories = array();
		protected $localDeliveryCost = null;
		protected $cpa = null;
		protected $offers = array();

		public function __construct()
		{
			$this->setItemName('shop');
		}

		public function setByXml(SimpleXmlElement $item)
		{
			$currentItem = $item->${$this->getNameItem()};

			$this->
				setName($currentItem->name)->
				setCompany($currentItem->company)->
				setUrl($currentItem->url)->
				setPlatform($currentItem->platform)->
				setVersion($currentItem->version)->
				setAgency($currentItem->agency)->
				setEmail($currentItem->email)->
				setCurrencies(
					$currentItem->currencies
				)->
				setCategories(
					$currentItem->categories
				)->
				setLocalDeliveryCost($currentItem->local_delivery_cost)->
				setCpa($currentItem->cpa)->
				setOffers(
					$currentItem->offers
				);
		}

		/*
		 <shop>
    <name>BestShop</name>
    <company>Best online seller Inc.</company>
    <url>http://best.seller.ru/</url>
    <platform>CMS</platform>
    <version>2.3</version>
    <agency>Agency</agency>
    <email>CMS@CMS.ru</email>
    <currencies> ... </currencies>
    <categories> ... </categories>
	 <local_delivery_cost>300</local_delivery_cost>
    <cpa>0</cpa>
     <offers>
        <offer>...</offer>
        ...
    </offers>
</shop>
		 */

		/**
		 *
		 * @return ShopItem
		 */
		public static function create()
		{
			return new self();
		}

		public function getName()
		{
			return $this->name;
		}

		public function setName($name)
		{
			$this->name = $name;
			return $this;
		}

		public function getCompany()
		{
			return $this->company;
		}

		public function setCompany($company)
		{
			$this->company = $company;
			return $this;
		}

		public function getUrl()
		{
			return $this->url;
		}

		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}

		public function getPlatform()
		{
			return $this->platform;
		}

		public function setPlatform($platform)
		{
			$this->platform = $platform;
			return $this;
		}

		public function getVersion()
		{
			return $this->version;
		}

		public function setVersion($version)
		{
			$this->version = $version;
			return $this;
		}

		public function getAgency()
		{
			return $this->agency;
		}

		public function setAgency($agency)
		{
			$this->agency = $agency;
			return $this;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function setEmail($email)
		{
			$this->email = $email;
			return $this;
		}

		public function getCurrencies()
		{
			return $this->currencies;
		}

		public function setCurrencies($currencies)
		{
			$this->currencies = $currencies;
			return $this;
		}

		public function getCategories()
		{
			return $this->categories;
		}

		public function setCategories($categories)
		{
			$this->categories = $categories;
			return $this;
		}

		public function getLocalDeliveryCost()
		{
			return $this->localDeliveryCost;
		}

		public function setLocalDeliveryCost($localDeliveryCost)
		{
			$this->localDeliveryCost = $localDeliveryCost;
			return $this;
		}

		public function getCpa()
		{
			return $this->cpa;
		}

		public function setCpa($cpa)
		{
			$this->cpa = $cpa;
			return $this;
		}

		public function getOffers()
		{
			return $this->offers;
		}

		public function setOffers($offers)
		{
			$this->offers = $offers;
			return $this;
		}

	}

?>