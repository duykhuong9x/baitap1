<?php
class EM_Multidealpro_Block_Adminhtml_Renderer_Dealdateto extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render(Varien_Object $row)
    {
		if($row->getStatus() == 0){
			$str = base64_decode($row->getHasNew());
			$plit = explode("tmp",$str);
			$result = $plit[2];
		}elseif($row->getStatus() == 1){
			$value 		= $row->getData($this->getColumn()->getIndex());
			$product 	= Mage::getModel('catalog/product')->load($value);
			$result		= $product->getSpecialToDate();
		}elseif($row->getStatus() == 2){
			$str = base64_decode($row->getHasEnd());
			$plit = explode("tmp",$str);
			$result = $plit[2];
		}
		return $result;
    }
}
?>