<?php
class EM_Multidealpro_Model_Multidealpro extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('multidealpro/multidealpro');
    }

	public function getDealCollection($where)
    {
		if(!$where) $where = "multidealpro.is_active=1";
		$collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect("*");
		$collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();

		$collection->getSelect()
					->join(array('multidealpro' => $collection->getTable('multidealpro/deal')),
						'e.entity_id = multidealpro.product_id',
						array(
							'deal_id' => 'multidealpro.deal_id','deal_product_id' => 'multidealpro.product_id',
							'deal_recent' => 'multidealpro.recent','deal_status' => 'multidealpro.status',
							'deal_qty_sold' => 'multidealpro.qty_sold','deal_has_new' => 'multidealpro.has_new',
							'deal_has_end' => 'multidealpro.has_end',
							'deal_creation_time' => 'multidealpro.creation_time','deal_update_time' => 'multidealpro.update_time',
							'deal_is_active' => 'multidealpro.is_active','e.*')
					)
					->where($where);

		$collection->addStoreFilter();
		// Status + Visibility
		$collection->addAttributeToFilter('status', array('neq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED));
		$collection->addAttributeToFilter('visibility',array("neq"=>1));
		$collection->getSelect()->order('deal_id DESC');

		return $collection;
	}
	
	public function refeshData()
    {
		$i = 1111;  // Test cronjob working 
		if($i == 1){
			$pathFile = Mage::getBaseDir('var').DS.'test_cron.txt';
			$fp=fopen($pathFile,'a')or exit("file not exist !!");
			$date = getdate();
			fwrite($fp,' \n '.$date[0]);
			fclose($fp);
		}

		Mage::helper('multidealpro')->checkAllDeals();
		return false;
	}
}