<?php

/* * *************************************************************************
 *   Copyright (C) 2013 by Ivantsov Mihail                                 *
 *                                                                         *
 * ************************************************************************* */

class XmlToArrayAbstract implements XmlToArray
{
	protected $imageLoaderDir = null;
	/**
	 * @var array[FileOutputStream]
	 */
	protected $files = array();
	/**
	 *
	 * @var str 
	 */
	protected $dir = null;
	
	/**
	 *
	 * @var str 
	 */
	protected $tmpDir = null;
	
	
	protected $tmpFiles = array();
	
	protected $groupProcessors = array();
	
	/**
	 *
	 * @var SimpleXMLElement 
	 */
	protected $groups = null;
	
	protected $loadImages = false;

	/**
	 * @return XmlToArrayAbstract
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 * 
	 * @param type $dir
	 * @return \XmlToArrayMoreColorSiteReader
	 */
	public function setDir($dir)
	{
		$this->dir = $dir;
		
		return $this;
	}
	
	public function setDirImage($dir)
	{
		$this->imageLoaderDir = $dir;
		
		return $this;
	}
	
	/**
	 * 
	 * @param AbstractProcessor $proc
	 * @return \XmlToArrayMoreColorSiteReader
	 */
	public function setGroupProcessor(AbstractProcessor $proc)
	{
		$this->groupProcessors[$proc->getName()] = $proc;
		
		return $this;
	}
	
	/**
	 * 
	 * @param SimpleXmlElement $group
	 * @return AbstractProcessor
	 */
	public function getGroupProcessor(SimpleXmlElement $group)
	{
		$name = (string) $group->CODE;
		if(!isset($this->groupProcessors[$name]))
			$name = 'default';
		
		return $this->groupProcessors[$name];
	}
	
	public function loadImages()
	{
		$this->loadImages = true;
		
		return $this;
	}
	
	public function process(\SimpleXMLElement $xml)
	{
		
	}
	
	/**
	 * 
	 * @param FileOutputStream $file
	 * @param type $code
	 * @return \XmlToArrayMoreColorSiteReader
	 */
	public function setOutputStream(FileOutputStream $file, $code)
	{
		$this->files[(string) $code] = $file;
		
		return $this;
	}
	
	/**
	 * 
	 * @param str $code
	 * @return FileOutputStream
	 * @throws Exception
	 */
	public function getOutputStream($code)
	{
		if(!array_key_exists($code, $this->files))
			throw new Exception("File not exist for code!");
		
		return $this->files[$code];
	}
	
	/**
	 * 
	 * @param type $name
	 * @return type
	 */
	public function getTmpFileName($name)
	{
		return $this->tmpDir . $name . ".csv";
	}
	
	/**
	 * 
	 * @param type $name
	 * @return type
	 */
	public function getFileName($name)
	{
		return $this->dir . $name . ".csv";
	}
	
	/**
	 * 
	 * @param type $name
	 * @return \XmlToArrayAbstract
	 */
	public function setTmpDir($name)
	{
		$this->tmpDir = $name;
		
		return $this;
	}
	
	protected function deleteFileStreams()
	{
		$this->files = array();
		
		return $this;
	}
	
	protected function deleteFileNames()
	{
		$this->tmpFiles = array();
		
		return $this;
	}
	
	protected function writeItemInFile($file,$item)
	{
		if(!$this->loadImages){
			$file->write("\r\n");
			$data = implode(";",$item);
			$data = str_replace("; ", ';', $data);

			$data = iconv("UTF-8","WINDOWS-1251//IGNORE",$data);

			$file->write($data);
		}
	}
	
	protected function getHeaders()
	{
		return DataFields::create()->getFields();
	}
	
	/**
	 * 
	 * @param string $str
	 * @return string
	 */
	protected function clearData($str)
	{
		return XmlTextUtil::create()->clearData($str);
	}
	
	protected function getSeoBlock($str)
	{
		$str = XmlTextUtil::create()->replaceBrBlock($str);
		return $str;
	}
	
	protected function putHeadersInFile($name,$code)
	{
		if(!$this->loadImages){
			$file = $this->getTmpFileName($name);
			
			$this->tmpFiles[] = $file;

			$this->setOutputStream(FileOutputStream::create($file,true), $code);

			$data = implode(";",$this->getHeaders());
			$data = iconv("UTF-8","WINDOWS-1251//IGNORE",$data);

			file_put_contents($file, $data);
		}
		
		return $this;
	}
	
}

?>