<?php

/**
 * This class can generate, display or write an XML Google Site Map file.</br>
 * This class is easy to use because she is able to determine the format of the input data automatically!
 * After that, this class convert them to generate properly the XML.
 * 
 * @author	Alban DOUSSAU
 * @version	2.0
 * @website	http://www.dodecagone.com
 */

class GoogleSiteMap {


	var $flux;			//XML String
	var $nl;			//New Line
	var $listfreq;		//Frequency List

	 /** 
     * Constructor. Initialize this class and start the XML Google Site Map String.
     * @return	Void
     */
	function GoogleSiteMap(){
		$this->Ini();
		$this->StartMap();
	}

/*-------------------------------------------------PUBLIC FUNCTIONS-------------------------------*/

	 /** 
     * This function add url elements to the XML Google Site Map String.
	 * This function is able to determine the format of the input data automatically.
	 * So you can have mixed data !
     *
     * @param	Mixed	$o	The object to convert to url.
	 * This can be a string, an array, an associative array, a folder, a mysql resource...
	 * You can extend it !
	 * 
     * @param	Array 	$option	This parameter is optional and can be used to add extra options (Browse recursively fr exemple) 
	 *
     * @return	Void
     */

	function Add($o,$option=array()){
		$type=gettype($o);
		switch ($type){
			case "array":
				 if ($this->IsMultiDimensional($o)) {
				 	for ($i=0;$i<count($o);$i++){
							$this->Add($o[$i],$option);
					}
				 }else{
				 	
				 	$this->AddURL($o,$option);
				 }
				break;
			default:
				$this->AddURL($o,$option);
				break;
		}
	}
	
	 /** 
     * This function close the XML Google Site Map String.
	 * 
     * @return	String	The XML String
     */
	function Close(){
		$this->flux.="</urlset>";
		$this->ToString();
	}
	
	 /** 
     * This function output the XML Google Site Map String to the browser with XML Content-Type
	 * 
     * @return	Void
     */
	function View(){
		header("Pragma: no-cache");
		header("Content-Type: text/xml;charset=UTF-8");
		echo $this->flux;
	}
	
	 /** 
     * This function return the XML Google Site Map String (Usefull for debug too)
	 * 
     * @param	String 	The path to write the file.
	 *
     * @return	Void 
     */
	function ToString(){
		return $this->flux;
	}
	
	 /** 
     * This function write the XML Google Site Map String in a specified file
	 * 
     * @return	Void
     */
	function Write($file){
		$this->MakerFile($file, $this->flux);
	}
	
/*-------------------------------------------------PRIVATE FUNCTIONS-------------------------------*/
	
	
	 /** 
     * This function initialise this class
	 * 
     * @return	Void
     */
	function Ini(){
		$this->flux="";
		$this->nl="\n";
		$this->listfreq=array("always","hourly","daily","weekly","monthly","yearly","never");
		
	}
	 /** 
     * This function start the XML Google Site Map String.
	 * 
     * @return	Void
     */
	function StartMap(){
		$this->flux.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>".$this->nl;
		$this->flux.="<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd\">".$this->nl;
	}
		
	 /** 
     * This function can determine the input data and convert them.
     *
     * @param	Mixed	$o	The object to convert to url.
	 * This can be a string, an array, an associative array, a folder, a mysql resource...
	 * You can extend it !
	 * 
     * @param	Array 	$option	This parameter is optional and can be used to add extra options (Browse recursively for exemple) 
	 *
     * @return	Void
     */
	function AddURL($o,$option=array()){
		
		$type=gettype($o);
		switch ($type){
			
			case "string":
				 is_dir($o) ? $this->AddFolderURL($o,$option) :$this->AddStringURL($o);
				break;
				
			case "array":
				$this->AddArrayURL($o);
				break;
			case "resource":
				switch (get_resource_type($o)){
					case "mysql result":
						  $this->AddMysqlURL($o);
						  break;
				}
				break;
				
			default:
				break;
		}
		
	}
	 /** 
     * String parser function
     *
     * @param	String	$s	The String to parse
	 * 
     * @return	Void
     */
	function AddStringURL($s){
		$this->flux.="<url>".$this->nl;
		$this->flux.="<loc>".$this->CleanURL($s)."</loc>".$this->nl;
		$this->flux.="</url>".$this->nl;
	}
	 /** 
     * Array parser function. This function can distinct array of associative array.
     *
     * @param	Array	$a	The Array to parse
	 * 
     * @return	Void
     */
	function AddArrayURL($a){
		
		if ($this->IsAssocArray($a)){
			$this->flux.="<url>".$this->nl;
			while (list($k, $v) = each($a)) {
						switch($k) {
							case "loc":
								$v=$this->CleanURL($v);
								break;
							case "lastmod":
								$v=($v=="")?date("c"):$v;
								break;
							case "changefreq":
								$v=(in_array($v,$this->listfreq))?$v:"monthly";
								break;
							case "priority":
								$v=($v==""||$v>1||$v<0)?"0.5":number_format($v,1);
								break;
						}
								
						$this->flux.="<".$k.">".$v."</".$k.">".$this->nl;
			}
			$this->flux.="</url>".$this->nl;
		}else{
			$this->flux.="<url>".$this->nl;
			if ($a[0]!=""){
				$this->flux.=($a[0]!="")?"<loc>".$this->CleanURL($a[0])."</loc>".$this->nl:"";
			}
			array_shift($a);
			$this->flux.="</url>".$this->nl;
		    $this->AddURL($a);
		
		}
		
		
		
		
	
	}
	
	 /** 
     * Mysql parser function.
     *
     * @param	Resource	$o	The MySQL resource to parse
	 * 
     * @return	Void
     */
	function AddMysqlURL($o){

		while ($res=mysql_fetch_object($o)){
		    $a=$this->ObjectToArray($res);
			$this->Add($a);
		}
	}
	 /** 
     * Folder parser function.
     *
     * @param	String	$path	The path to analyse end parse
	 * @param	Array	$option	Option for the analyse. 
	 * url: the url to add before the name files. Exemple: http://www.test.com/
	 * hidden: list of files extension to hide (exemple : .htaccess,.txt)
	 * resursive: bolean, specify if the parser must browser the forlder recursively or not
	 *
	 * 
     * @return	Void
     */
	function AddFolderURL($path,$option=array()){
			
		$dir=opendir($path);
		$restrict=explode(",",$option["hidden"]);
		while (($file = readdir($dir)) !== false) 
		{
               if ($file == "." || $file == "..") continue;
               $file = $path . "/" . $file;
			   if(!is_dir($file)){
			   		if (!in_array(strrchr($file, '.'),$restrict)){
					   $this->flux.="<url>".$this->nl;
					   $this->flux.="<loc>".$this->CleanURL(str_replace("../","",$option["url"].$file))."</loc>".$this->nl;
					   $this->flux.="</url>".$this->nl;
					}
			   }else{
			   	   if ($option["recursive"]){
			  		  $this->AddFolderURL($file,$option);
					}
			   }
        }
		
	}
	
/*-------------------------------------------------USEFUL FUNCTIONS-------------------------------*/

	 /** 
     * Check if an array is multi-dimensional or not
     *
     * @param	Array	$a	The array to analyse
	 * 
     * @return	Bolean 
     */
	
	function IsMultiDimensional($a) {
			$ism=0;
			for ($i=0;$i<count($a);$i++){
			   if (is_array($a[$i])) {
					$ism=1;
			   } 
			}
			return $ism;	
	}
	   
	 /** 
     * Check if an array is an associative array or not
     *
     * @param	Array	$a	The array to analyse
	 * 
     * @return	Bolean 
     */
		
	function IsAssocArray($a){
			return $a[0]!=""? false:true;
	}
	 /** 
     * Convert an Object to Array
     *
     * @param	Object	$o	The Object to convert
	 * 
     * @return	Array
     */
	function ObjectToArray($o){
		
		$a=array();
		while (list($k, $v) = each($o)) {
			$a[$k]=$v;
		}
		return $a;
	}
	
	 /** 
     * Convert a string whith htmlentities and utf8 encode.
     *
     * @param	String	$s	The string to clean 
	 * 
     * @return	String
     */
	function CleanURL($s){
			$s=htmlentities($s,ENT_QUOTES,"UTF-8");
			$s=utf8_encode($s);
			return $s;
	}
	

	 /** 
     * Write a file to the specified file.
     *
     * @param	String	$file	The path to write the file
	 * @param	String	$string	The content to write
	 * 
     * @return	Void
     */
	
	function MakerFile($file, $string) {
	   $f=fopen($file, 'w+');
	   ftruncate($f, 0);
	   fwrite($f, $string);
	   fclose($f);
	}
	
}
	
?>