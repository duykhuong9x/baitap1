<?php
 
class Gala_Galatitanshopsettings_Model_Config_Product_View
{
	public function toOptionArray()
    {
		return array(
			array('label' => "- Default -", 'value' => ""),
			array('label' => "Cloudzoom", 'value' => "cloudzoom"),
			array('label' => "Lightbox", 'value' => "lightbox")
		);
    }
 
}
