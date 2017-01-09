<?php
class EM_Multidealpro_Block_Adminhtml_Multidealpro_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('multidealproGrid');
      $this->setDefaultSort('deal_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);

	  Mage::helper('multidealpro')->checkAllDeals();
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('multidealpro/multidealpro')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	 $this->addColumn('deal_id', array(
          'header'    => Mage::helper('multidealpro')->__('Deal ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'deal_id',
      ));

      $this->addColumn('product_id', array(
          'header'    => Mage::helper('multidealpro')->__('Product ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'product_id',
      ));

      $this->addColumn('product_name', array(
          'header'    => Mage::helper('multidealpro')->__('Product Name'),
          'align'     =>'left',
          'index'     => 'product_id',
		  'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Name'
      ));
	  
	  if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('multidealpro')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

      $this->addColumn('price', array(
			'header'    => Mage::helper('multidealpro')->__('Deal Price'),
			'width'     => '150px',
			'index'     => 'product_id',
			'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Dealprice'
      ));
	  
	  $this->addColumn('qty', array(
			'header'    => Mage::helper('multidealpro')->__('Deal Qty'),
			'width'     => '150px',
			'index'     => 'product_id',
			'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Dealqty'
      ));
	  
	  $this->addColumn('date_from', array(
			'header'    => Mage::helper('multidealpro')->__('Date/Time From'),
			'width'     => '150px',
			'type'      => 'datetime',
			'index'     => 'product_id',
			'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Dealdateform'
      ));
	  
	  $this->addColumn('date_to', array(
			'header'    => Mage::helper('multidealpro')->__('Date/Time To'),
			'width'     => '150px',
			'type'      => 'datetime',
			'index'     => 'product_id',
			'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Dealdateto'
      ));
	  
	  $this->addColumn('qty_sold', array(
			'header'    => Mage::helper('multidealpro')->__('Qty Sold'),
			'width'     => '150px',
			'index'     => 'qty_sold',
      ));
	  
	  $this->addColumn('status', array(
          'header'    => Mage::helper('multidealpro')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              0 => Mage::helper('multidealpro')->__('Queued'),
              1 => Mage::helper('multidealpro')->__('Running'),
              2 => Mage::helper('multidealpro')->__('End')
          ),
		  'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Status'
      ));
	  
	  $this->addColumn('is_active', array(
          'header'    => Mage::helper('multidealpro')->__('Active'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'is_active',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
		  'renderer' =>  'EM_Multidealpro_Block_Adminhtml_Renderer_Status'
      ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('multidealpro')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('multidealpro')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('multidealpro')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('multidealpro')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('multidealpro_id');
        $this->getMassactionBlock()->setFormFieldName('multidealpro');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('multidealpro')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('multidealpro')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('multidealpro/active')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('is_active', array(
             'label'=> Mage::helper('multidealpro')->__('Change Active'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'is_active',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('multidealpro')->__('Active'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	
	protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
	
	public function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

}