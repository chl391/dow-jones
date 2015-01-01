<?php

__autoload("\Views\Helper\AbstractHelper");

/**
 * Included from view.php
 * HTML helper methods
 * 
 * Note: Work in progress
 * 
 * @author Charles Lin
 */
class HtmlHelper extends AbstractHelper
{
	public function url($options = array())
	{
		if (!is_array($options)) {
			return $options;
		}
		
		// Each View object has Controller variable that gives name of Controller
		$defaults = array(
			'controller' => $this->View->Controller->name,
			'action' => 'index'
		);
		
		// array_merge has the second parameter replace the first
		// if there are overlaps
		$options = array_merge($defaults, $options);
		$qs = $this->collapseArray($options, '&');
		return "index.php?$qs";
	}
	
	public function link($title, $url, $options = array())
	{
		$url = $this->url($url);
		
		// collapseArray() is function in AbstractHelper.php
		$attributes = $this->collapseArray($options, ' ', '"');
		$link = <<<HTML
<a href="$url" $attributes>$title</a>
HTML;
		return $link;	
	}
	
	public function img($alt, $src, $options = array())
	{
		$attributes = $this->collapseArray($options, ' ', '"');
		$link = <<<HTML
<img src ="$src" alt = "$alt" $attributes>
HTML;
		return $link;
	}
	
	// Could do helper method for only one entry
	//                            only text inputs
	// When considering all types of different inputs, gets a little trickier
	// Could do helper method for only individual inputs
	// Probably will have to change this:
	public function input($label,$column,$type,$table)
	{
		$label = strtolower($label);
		$upperLabel = ucfirst($lowerLabel);
		$column = strtolower($column);
		$type = strtolower($type);
		if ($type != "dropdown")
		{
			$input = <<<HTML
<input for="$label">$upperLabel:</label><input type="$type"  id="$column" name="$column">
HTML;
		}
		else
		{
			// How to put php in this tag... will it work?
			$input = <<<HTML
<label  for ="$label">$upperLabel:</label>
<select id="$column" name="$column">
<?php
foreach($this->$table as $key => $value)
{
echo '<option value=' . $key . '>' . $value . '</option>';
}
?>
</select>
HTML;
		}
		return $input;
	}
}