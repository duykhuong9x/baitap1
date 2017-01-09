<?php
class EM_Multidealpro_Model_Config_Layout
{
	public function toOptionArray()
    {
		return array(
			array('label' => "1 column", 'value' => "one_column"),
			array('label' => "2 columns with left bar", 'value' => "two_columns_left"),
			array('label' => "2 columns with right bar", 'value' => "two_columns_right"),
			array('label' => "3 columns", 'value' => "three_columns")
		);
    }
 
}
