<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class ElementDecoderVendorModelOffer
extends ElementDecoder
{
/*
    <url>http://best.seller.ru/product_page.asp?pid=12344</url>
    <price>16800</price>
    <currencyId>USD</currencyId>
    <categoryId>6</categoryId>
    <picture>http://best.seller.ru/img/device12345.jpg</picture>
    <store>false</store>
    <pickup>false</pickup>
    <delivery>true</delivery>
    <local_delivery_cost>300</local_delivery_cost>
    <typePrefix>Принтер</typePrefix>
    <vendor>НP</vendor>
    <vendorCode>CH366C</vendorCode>
    <model>Deskjet D2663</model>
    <description>Серия принтеров для людей, которым нужен надежный, простой в использовании
    цветной принтер для повседневной печати. Формат А4. Технология печати: 4-цветная термальная струйная.
    Разрешение при печати: 4800х1200 т/д.
    </description>
    <sales_notes>Необходима предоплата.</sales_notes>
    <manufacturer_warranty>true</manufacturer_warranty>
    <country_of_origin>Япония</country_of_origin>
    <barcode>1234567890120</barcode>
    <cpa>1</cpa>
    <rec>123123,1214,243</rec>
    <expiry>P5Y</expiry>
    <weight>2.07</weight>
    <dimensions>100/25.45/11.112</dimensions>
    <param name="Максимальный формат">А4</param>
    <param name="Технология печати">термическая струйная</param>
    <param name="Тип печати">Цветная</param>
    <param name="Количество страниц в месяц" unit="стр">1000</param>
    <param name="Потребляемая мощность" unit="Вт">20</param>
    <param name="Вес" unit="кг">2.73</param>
 *  */
	/**
	 * @return ElementDecoderVendorModelOffer
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
}

?>