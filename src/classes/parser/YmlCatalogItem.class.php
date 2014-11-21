<?php

	/*	 * *************************************************************************
	 *   Copyright (C) 2014 by Mikhail Ivantsov                                *
	 *                                                                         *
	 * ************************************************************************* */

	class YmlCatalogItem
	extends XmlItem
	{
		/**
		 *
		 * @var ShopItem $shop
		 */
		protected $shop = null;
		/**
		 *
		 * @return YmlCatalogItem
		 */
		public static function create()
		{
			return new self();
		}

		public function __construct()
		{
			$this->setItemName('yml_catalog');
		}

		public function setByXml(SimpleXmlElement $item)
		{
			$this->shop = ShopItem::create()->setByXml($item);
		}

		public function getShop()
		{
			return $this->shop;
		}

		public function setShop(ShopItem $shop)
		{
			$this->shop = $shop;
			return $this;
		}

	}

?>