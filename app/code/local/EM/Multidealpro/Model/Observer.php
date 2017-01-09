<?php
class EM_Multidealpro_Model_Observer 
{
	private static $_handleCustomerFirstOrderCounter = 1;

	/* Update custom layout */
    public function changeLayoutEvent($observer)
	{
		$setting	=	Mage::helper("multidealpro");

		$enable 	= $setting->getRecentDeal_EnableRecent();
		$reference 	= $setting->getRecentDeal_BlockReference();

		if($enable == 1){
			if($reference != 'none'){
				$type 		= $setting->getSidebar_BlockData_RecentDeal_Type();
				$name 		= $setting->getSidebar_BlockData_RecentDeal_Name();
				$template 	= $setting->getSidebar_BlockData_RecentDeal_Template();
				$position 	= $setting->getRecentDeal_Position();

				if(!preg_match("/^(before|after)\=\"[a-zA-Z0-9_.-]+\"/",$position))
					$position = "";

				if($reference == 'all'){
					$xml = 		'<reference name="left">';
					$xml .= 		'<block type="'.$type.'" '.$position.' name="'.$name.'" template="'.$template.'"/>';
					$xml .= 	'</reference>';
					$xml .= 	'<reference name="right">';
					$xml .= 		'<block type="'.$type.'" '.$position.' name="'.$name.'" template="'.$template.'"/>';
					$xml .= 	'</reference>';
				}else{
					$xml = 		'<reference name="'.$reference.'">';
					$xml .= 		'<block type="'.$type.'" '.$position.' name="'.$name.'" template="'.$template.'"/>';
					$xml .= 	'</reference>';
				}

				$update = $observer->getEvent()->getLayout()->getUpdate();
				$update->addUpdate($xml);
			}
		}
        return $this;
    }

	public function handleCustomerFirstOrder($observer)
    {
		/*$orders = Mage::getModel('sales/order')
                    ->getCollection()
                    ->addFieldToSelect('increment_id')
                    ->addFieldToFilter('customer_id', array('eq' => $observer->getEvent()->getOrder()->getCustomerId()));*/

		//if ($orders->getSize() == 1) {
            if (self::$_handleCustomerFirstOrderCounter > 1) {
                return $this;
            }

			$cartHelper = Mage::helper('checkout/cart');
			$items = $cartHelper->getCart()->getItems();
			foreach ($items as $item) {
				$where = 'multidealpro.product_id = '.$item->getProductId().' AND multidealpro.is_active=1';
				$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

				if($collection->getSize() > 0 ){
					$deal	=	$collection->getFirstItem();
					$_product = Mage::getModel('catalog/product')->load($item->getProductId());

					$qty 			= Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
					if($qty <= 0)
					{
						$data['status'] = 2;
						$data['active'] = 2;

						$has_end = base64_encode($_product->getSpecialPrice().'tmp'.$_product->getSpecialFromDate().'tmp'.$_product->getSpecialToDate());
						$_product->setSpecialPrice("")
								->setSpecialFromDate("")
								->setSpecialToDate("");
					}
					$data['qty'] = $item->getQty();

					$resource = Mage::getSingleton('core/resource'); // Get the resource model
					$writeConnection = $resource->getConnection('core_write'); // Retrieve the write connection
					$table = $resource->getTableName('multidealpro/deal'); // Retrieve our table name

					$query = "UPDATE {$table} SET qty_sold = qty_sold+".$data['qty'];
					if($data['status'])		$query .= ", status = ".$data['status'];
					if($data['active'])		$query .= ", is_active = ".$data['active'];
					if($qty <= 0)	$query .= ", has_new = '', has_end = '".$has_end."' ";
					$query .= " WHERE deal_id = ".$deal->getDealId();

					$writeConnection->query($query); // Execute the query
					$_product->save();
				}
			}

			self::$_handleCustomerFirstOrderCounter++;
            Mage::dispatchEvent('customer_first_order', array('order' => $observer->getEvent()->getOrder()));
        //}
        return $this;
    }
}