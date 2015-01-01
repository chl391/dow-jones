<?php

/**
 * Autoloaded from HtmlHelper
 * 
 * @author Charles Lin
 */

abstract class AbstractHelper
{
	public function __construct(&$view) {
		$this->View = $view;
	}
	
	/**
	 * Comparable to implode()
	 * 
	 * @param array $array
	 * @param string $glue
	 * @param stringe $encloseValue
	 * @return string $result
	 */
	public function collapseArray($array, $glue = "", $encloseValue = '') 
	{
		$result = "";
		foreach($array as $key => $value) 
		{
			$result .= "$key=$encloseValue$value$encloseValue$glue";
		}
		$result = rtrim($result, $glue);
		return $result;
	}
}