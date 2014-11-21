<?php

	/*	 * *************************************************************************
	 *   Copyright (C) 2014 by Mikhail Ivantsov                                *
	 *                                                                         *
	 * ************************************************************************* */

	class XmlItem
	implements IXmlItem
	{
		private $closedItem = 0;

		/**
		 *
		 * @var SimpleXMLElement
		 */
		protected $item = null;

		private $itemName = null;

		/**
		 *
		 * @return XmlTag
		 */
		public static function create()
		{
			return new self();
		}

		public function getItem()
		{
			return $this->item;
		}

		public function setItem(SimpleXMLElement $item)
		{
			$this->item = $item;

			return $this;
		}

		public function getItemName()
		{
			return $this->itemName;
		}

		public function setItemName($itemName)
		{
			if($this->closedItem !== 1) {
				$this->itemName = $itemName;

				$this->closedItem = 1;
			} else {
				throw new Exception('Item was closed already!');
			}

			return $this;
		}

		public function getNameItem()
		{
			return $this->itemName;
		}

	}

?>