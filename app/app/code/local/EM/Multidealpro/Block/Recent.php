<?php
class EM_Multidealpro_Block_Recent extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

	public function checkSitebar()
    {
		$check = 0;	// disabled
		if(Mage::helper('multidealpro')->getGeneral_EnableMultideal() == 1){		// enabled
			if (Mage::helper('multidealpro')->getRecentDeal_EnableRecent() == 1) $check = 1;
		}
		return $check;
    }

	public function getProductCollection()
    {
		$where = 'multidealpro.status = 1 AND multidealpro.is_active=1 AND multidealpro.recent = 1';
		$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

		$collection->setPageSize(Mage::helper("multidealpro")->getRecentDeal_Limit());

		return $collection;
    }

}