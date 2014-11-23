<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class ElementDecoder
{
/*
<url>http://best.seller.ru/product_page.asp?pid=12348</url>
    <price>600</price>
    <currencyId>USD</currencyId>
    <categoryId>6</categoryId>
    <picture>http://best.seller.ru/img/device12345.jpg</picture>
    <store>false</store>
    <pickup>true</pickup>
    <delivery>false</delivery>
    <local_delivery_cost>300</local_delivery_cost>
    <name>Наручные часы Casio A1234567B</name>
    <vendor>Casio</vendor>
    <vendorCode>A1234567B</vendorCode>
    <description>Изящные наручные часы.</description>
    <sales_notes>Необходима предоплата.</sales_notes>
    <manufacturer_warranty>P2Y</manufacturer_warranty>
    <country_of_origin>Япония</country_of_origin>
    <barcode>0123456789012</barcode>
    <cpa>1</cpa> 
 */
	/**
	 * @return ElementDecoder
	 */
	public static function create()
	{
		return new self();
	}
	
	public function createByElement(SimpleXMLElement $element)
	{
		return "<ELEMENT>"
			. $this->createName((string) $element->model)
			. $this->createGroup( (string) $element->vendor)
			. $this->createArticle('')
			. $this->createPreviewPicture((string) $element->picture)
			. $this->createDetailText( (string) $element->description)
			. $this->createDetailPicture((string) $element->picture)
			. $this->createMorePhoto('')
			. $this->createCode($element->model)
			. $this->createPrice(((int)$element->price)/2)
                . $this->createPriceSeller(((int)$element->price))
			. $this->createCurrency($element->currencyId)
			. $this->createQuantity(1)
			. "</ELEMENT>";
	}
	
	protected function createName($name)
	{
		return "<NAME><![CDATA[$name]]></NAME>";
	}
	
	protected function createGroup($group)
	{
		return "<GROUP><![CDATA[$group]]></GROUP>";
	}
	
	protected function createArticle($article)
	{
		return "<ARTICLE>$article</ARTICLE>";
	}
	
	protected function createPreviewPicture($picture)
	{
		return "<PREVIEW_PICTURE>$picture</PREVIEW_PICTURE>";
	}
	
	protected function createDetailText($detail)
	{
		return "<DETAIL_TEXT><![CDATA[$detail]]></DETAIL_TEXT>";
	}
	
	protected function createDetailPicture($picture)
	{
		return "<DETAIL_PICTURE>$picture</DETAIL_PICTURE>";
	}
	
	protected function createMorePhoto($photo)
	{
		return "<MOREPHOTO>$photo</MOREPHOTO>";
	}
	
	protected function createCode($code)
	{
		return "<CODE><![CDATA[$code]]></CODE>";
	}
	
	protected function createPrice($price)
	{
		return "<PRICE>$price</PRICE>";
	}
	
	protected function createPriceSeller($price)
	{
		return "<PRICESELLER>$price</PRICESELLER>";
	}
	
	protected function createCurrency($currency)
	{
		$currency = str_replace('RUR','RUB',$currency);
		
		return "<CURRENCY>$currency</CURRENCY>";
	}
	
	protected function createQuantity($quantity)
	{
		return "<QUANTITY>$quantity</QUANTITY>";
	}

}

?>