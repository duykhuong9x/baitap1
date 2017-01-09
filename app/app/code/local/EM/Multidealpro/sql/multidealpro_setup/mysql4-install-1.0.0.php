<?php

$installer = $this;

$installer->startSetup();

/**
 * Create table 'multidealpro/deal'
 */
if(!$installer->tableExists($installer->getTable('multidealpro/deal'))){
	$table = $installer->getConnection()
		->newTable($installer->getTable('multidealpro/deal'))
		->addColumn('deal_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'identity'  => true,
			'nullable'  => false,
			'primary'   => true,
			), 'Deal ID')
		->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			), 'Product ID')
		->addColumn('after_end', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			'default'   => '0',
			), 'Disable product after deal ends')
		->addColumn('recent', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			), 'Recent')
		->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => true,
			), 'Status')
		->addColumn('qty_sold', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
			'nullable'  => true,
			'default'   => '0.0000',
			), 'QTY Sold')
		->addColumn('has_new', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
			), 'History new')
		->addColumn('has_end', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
			), 'History end')
		->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
			), 'Block Creation Time')
		->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
			), 'Block Modification Time')
		->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			'default'   => '1',
			), 'Is Deal Active')
		->setComment('EM Multidealpro Table');
	$installer->getConnection()->createTable($table);
}

/**
 * Create table 'multidealpro/deal_store'
 */
if(!$installer->tableExists($installer->getTable('multidealpro/deal_store'))){
	$table = $installer->getConnection()
		->newTable($installer->getTable('multidealpro/deal_store'))
		->addColumn('deal_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			'primary'   => true,
			), 'Deal ID')
		->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'unsigned'  => true,
			'nullable'  => false,
			'primary'   => true,
			), 'Store ID')
		->addIndex($installer->getIdxName('multidealpro/deal_store', array('store_id')),
			array('store_id'))
		->addForeignKey($installer->getFkName('multidealpro/deal_store', 'deal_id', 'multidealpro/deal', 'deal_id'),
			'deal_id', $installer->getTable('multidealpro/deal'), 'deal_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->addForeignKey($installer->getFkName('multidealpro/deal_store', 'store_id', 'core/store', 'store_id'),
			'store_id', $installer->getTable('core/store'), 'store_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('CMS Block To Store Linkage Table');
	$installer->getConnection()->createTable($table);
}

$installer->endSetup(); 