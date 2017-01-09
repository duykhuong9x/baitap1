<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product in category grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class EM_Multidealpro_Block_Adminhtml_Multidealpro_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'product_id') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            }
            elseif(!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('price')
			->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id')
            ->addStoreFilter($this->getRequest()->getParam('store'));
			
		$collection->addFieldToFilter("type_id",array("simple","virtual","downloadable"));

		if((Mage::registry('multidealpro_data')) && Mage::registry('multidealpro_data')->getProductId())
			$collection->addfieldtofilter('entity_id',Mage::registry('multidealpro_data')->getProductId());

		if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }

			$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
			$collection->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
		$this->addColumn('product_id', array(
			'type'      => 'radio',
			'html_name'	=> 'product_id[]',
			'align'     => 'center',
			'values'    => $this->_getSelectedProducts(),
			'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Radio'
		));

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('multidealpro')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
			'index'     => 'entity_id'
        ));

        $this->addColumn('name', array(
			'header_css_class' => 'a-center',
            'header'    => Mage::helper('multidealpro')->__('Name'),
			'align'     => 'left em_name',
            'index'     => 'name'
        ));

        $this->addColumn('type',
            array(
                'header'=> Mage::helper('multidealpro')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
		
		$this->addColumn('visibility',
            array(
                'header'=> Mage::helper('multidealpro')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));
		
		$sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();
        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('multidealpro')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));

        $this->addColumn('price', array(
            'header'    => Mage::helper('multidealpro')->__('Price'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));

		if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('multidealpro')->__('Qty'),
                    'width' => '100px',
                    'type'  => 'number',
                    'index' => 'qty',
            ));
        }

		$this->addColumn('status',
            array(
                'header'=> Mage::helper('multidealpro')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true,'product_id'=>Mage::registry('multidealpro_data')->getProductId()));
    }

	protected function _getSelectedProducts()
    {
		if(Mage::registry('multidealpro_data'))
			$val[]	=	Mage::registry('multidealpro_data')->getProductId();
		else
			$val[]	=	$this->getRequest()->getParam('product_id');
        return $val;
    }

}

