<?php
class Gala_Galatitanshopsettings_Helper_Sample extends Mage_Core_Helper_Abstract
{
	protected $_themeSlug  = 'default/galatitanshop';
	protected $_blockIds = array();
	protected $_blockUpdateIds = array();
	protected $_pageIds = array();
	protected $_widgetInstanceIds = array();
	protected $_menuIds = array();
	protected $_slideshowIds = array();
	protected $_megaMenuModel = 'galatitanshopsettings/megamenupro';
	protected $_themeFrameworkAreaModel = 'themeframework/area';
	
	public function importSampleData($installer){
		$installer->startSetup();
		$pathFile = Mage::getBaseDir('var').DS.'install_galatitanshop.txt';
		if(file_exists($pathFile)){
			echo 'Installing Gala Titanshop Theme, please come back in some minutes ...';
			exit;
		}
		file_put_contents($pathFile,'Installing Gala Titanshop Theme');
		$path = Mage::getBaseDir('design').DS.'frontend'.DS.str_replace('/',DS,$this->_themeSlug).DS.'etc'.DS.'sampledata.xml';
		if (file_exists($path)) {
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($path);
			$this->importCustomSql($installer);
			$this->importThemeFrameworkArea($xmlDoc);
			$this->importStaticContent($xmlDoc);
			$this->importMegaMenu($xmlDoc);
			$this->importSlideshow($xmlDoc);
			$this->importWidgetInstance($xmlDoc);
			$this->updateWidgetTabsId();
			//$this->importStaticContent($xmlDoc);
		}
		unlink($pathFile);
		$installer->endSetup();
		return $this;
	}
	
	/**
		Import static block and static page from sampledata
	*/
	public function importStaticContent($xmlDoc){
		/* Import static block */
		$blockNodes = $xmlDoc->getElementsByTagname('block');
		//echo '<pre>';print_r($blockNodes);exit;
		foreach($blockNodes	as $v){	
			$data = array();
			$data['title']		=	$v->getElementsByTagName("title")->item(0)->nodeValue;
			$data['identifier']	=	$v->getElementsByTagName("identifier")->item(0)->nodeValue;
			$data['stores']		=	unserialize($v->getElementsByTagName("store_id")->item(0)->nodeValue);
			$data['is_active']	=	$v->getElementsByTagName("is_active")->item(0)->nodeValue;
			$data['content']	=	$v->getElementsByTagName("content")->item(0)->nodeValue;
			$data['id'] 		= 	$v->getAttribute('id');
			$this->insertStaticBlock($data);
		}
		
		/* Import cms page */
		$pageNodes = $xmlDoc->getElementsByTagname('page'); 
		foreach($pageNodes	as $v){	
			$data = array();
			$data['title']		=	$v->getElementsByTagName("title")->item(0)->nodeValue;
			$data['identifier']	=	$v->getElementsByTagName("identifier")->item(0)->nodeValue;
			$data['stores']		=	unserialize($v->getElementsByTagName("store_id")->item(0)->nodeValue);
			$data['is_active']	=	$v->getElementsByTagName("is_active")->item(0)->nodeValue;
			$data['content']	=	$v->getElementsByTagName("content")->item(0)->nodeValue;
			$data['root_template']	=	$v->getElementsByTagName("root_template")->item(0)->nodeValue;
			$data['meta_keywords']	=	$v->getElementsByTagName("meta_keywords")->item(0)->nodeValue;
			$data['meta_description']	=	$v->getElementsByTagName("meta_description")->item(0)->nodeValue;
			$data['content_heading']	=	$v->getElementsByTagName("content_heading")->item(0)->nodeValue;
			$data['sort_order']	=	$v->getElementsByTagName("sort_order")->item(0)->nodeValue;
			$data['layout_update_xml']	=	$v->getElementsByTagName("layout_update_xml")->item(0)->nodeValue;
			$data['custom_theme']	=	$v->getElementsByTagName("custom_theme")->item(0)->nodeValue;
			$data['custom_root_template']	=	$v->getElementsByTagName("custom_root_template")->item(0)->nodeValue;
			$data['custom_layout_update_xml']	=	$v->getElementsByTagName("custom_layout_update_xml")->item(0)->nodeValue;
			$data['custom_theme_from']	=	$v->getElementsByTagName("custom_theme_from")->item(0)->nodeValue;
			$data['custom_theme_to']	=	$v->getElementsByTagName("custom_theme_to")->item(0)->nodeValue;
			$this->insertPage($data);	
		}
		return $this;
	}
	
	public function insertStaticBlock($dataBlock) {
		// insert a block to db if not exists
		$block = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('identifier', $dataBlock['identifier'])->getFirstItem();
		$oldId = $dataBlock['id'];
		unset($dataBlock['id']);
		if (!$block->getId())
			$block->setData($dataBlock)->save();
		$this->_blockIds[$oldId] = $block->getId();
		if(preg_match('/{{widget type="tabs\/group"(.*)}}/U',$dataBlock['content'],$matches))
			$this->_blockUpdateIds[$oldId] = $block;
		return $block;
	}
	
	public function insertPage($dataPage) {
		$page = Mage::getModel('cms/page')->getCollection()->addFieldToFilter('identifier', $dataPage['identifier'])->getFirstItem();
		if (!$page->getId())
			$page->setData($dataPage)->save();
		return $page;
	}
	
	public function importMegaMenu($xmlDoc){
		$menus = $xmlDoc->getElementsByTagname('menu');
		foreach($menus as $menu){
			$data = array(
				'name' 		=> $menu->getElementsByTagName("name")->item(0)->nodeValue,
				'type' 		=> $menu->getElementsByTagName("type")->item(0)->nodeValue,
				'content' 	=> $menu->getElementsByTagName("content")->item(0)->nodeValue,
				'css_class' => $menu->getElementsByTagName("css_class")->item(0)->nodeValue,
				'status' 	=> $menu->getElementsByTagName("status")->item(0)->nodeValue
			);
			$model = Mage::getModel($this->_megaMenuModel)->setData($data)->save();
			$this->_menuIds[$menu->getAttribute('id')] = $model->getId();
		}
		return $this;
	}
	
	public function importSlideshow($xmlDoc){
		$slideshows = $xmlDoc->getElementsByTagname('slideshow');
		if(count($slideshows)){
			foreach($slideshows as $slideshow){
				$data = array(
					'name' 				=> $slideshow->getElementsByTagName("name")->item(0)->nodeValue,
					'images' 			=> $slideshow->getElementsByTagName("images")->item(0)->nodeValue,
					'slider_type' 		=> $slideshow->getElementsByTagName("slider_type")->item(0)->nodeValue,
					'slider_params' 	=> $slideshow->getElementsByTagName("slider_params")->item(0)->nodeValue,
					'delay' 			=> $slideshow->getElementsByTagName("delay")->item(0)->nodeValue,
					'touch' 			=> $slideshow->getElementsByTagName("touch")->item(0)->nodeValue,
					'stop_hover' 		=> $slideshow->getElementsByTagName("stop_hover")->item(0)->nodeValue,
					'shuffle_mode' 		=> $slideshow->getElementsByTagName("shuffle_mode")->item(0)->nodeValue,
					'stop_slider' 		=> $slideshow->getElementsByTagName("stop_slider")->item(0)->nodeValue,
					'stop_after_loop' 	=> $slideshow->getElementsByTagName("stop_after_loop")->item(0)->nodeValue,
					'stop_at_slide' 	=> $slideshow->getElementsByTagName("stop_at_slide")->item(0)->nodeValue,
					'position' 			=> $slideshow->getElementsByTagName("position")->item(0)->nodeValue,
					'appearance' 		=> $slideshow->getElementsByTagName("appearance")->item(0)->nodeValue,
					'navigation' 		=> $slideshow->getElementsByTagName("navigation")->item(0)->nodeValue,
					'thumbnail' 		=> $slideshow->getElementsByTagName("thumbnail")->item(0)->nodeValue,
					'visibility' 		=> $slideshow->getElementsByTagName("visibility")->item(0)->nodeValue,
					'trouble' 			=> $slideshow->getElementsByTagName("trouble")->item(0)->nodeValue,
					'status' 			=> $slideshow->getElementsByTagName("status")->item(0)->nodeValue
				);
				//echo '<pre>';print_r($data);exit;
				$model = Mage::getModel('slideshow2/slider')->setData($data)->setCreatedTime(now())->setUpdateTime(now())->save();
				$this->_slideshowIds[$slideshow->getAttribute('id')] = $model->getId();
			}
		}
		return $this;
	}
	
	public function importWidgetInstance($xmlDoc){
		$widgets = $xmlDoc->getElementsByTagname('widget');
		$test = array();
		foreach($widgets	as $widget){	
			$data = array(
				'title'				=>	$widget->getElementsByTagName("title")->item(0)->nodeValue,
				'store_ids'			=>	unserialize($widget->getElementsByTagName("store_ids")->item(0)->nodeValue),
				'widget_parameters'	=>	$widget->getElementsByTagName("widget_parameters")->item(0)->nodeValue,
				'sort_order'		=>	$widget->getElementsByTagName("sort_order")->item(0)->nodeValue,
				'page_groups'		=>	unserialize($widget->getElementsByTagName("page_groups")->item(0)->nodeValue)
			);
			
			$instanceType = $widget->getElementsByTagName("instance_type")->item(0)->nodeValue;
			$packageTheme = $widget->getElementsByTagName("package_theme")->item(0)->nodeValue;
			
			if($instanceType == 'cmswidget/widget_block'){
				$params = unserialize($data['widget_parameters']);
				$params['block_id'] = $this->_blockIds[$params['block_id']];
				$data['widget_parameters'] = serialize($params);
			} else if($instanceType == 'sliderwidget/slide'){
				$params = unserialize($data['widget_parameters']);
				$params['instance'] = $this->_widgetInstanceIds[$params['instance']];
				$data['widget_parameters'] = serialize($params);
			} else if($instanceType == 'tabs/group'){
				$params = unserialize($data['widget_parameters']);
				for($i = 1;$i <= 10;$i++){
					if(!$params['instance_'.$i])
						break;
					$params['instance_'.$i] = $this->_widgetInstanceIds[$params['instance_'.$i]];	
				}
				$data['widget_parameters'] = serialize($params);
			} else if($instanceType == 'megamenupro/megamenupro'){
				$params = unserialize($data['widget_parameters']);
				$params['menu'] = $this->_menuIds[$params['menu']];
				$data['widget_parameters'] = serialize($params);
			} else if($instanceType == 'slideshow2/slideshow2'){
				$params = unserialize($data['widget_parameters']);
				$params['slideshow'] = $this->_slideshowIds[$params['slideshow']];
				$data['widget_parameters'] = serialize($params);
			}
			
			$model = Mage::getModel('widget/widget_instance');
			$model->setData($data)->setType($instanceType)->setPackageTheme($packageTheme)->save();
			if($instanceType == 'tabs/group'){
				$newId = $model->getId();
				$params = unserialize($data['widget_parameters']);
				$params['instance'] = $newId;
				$data['widget_parameters'] = serialize($params);
				$model->setData($data)->setType($instanceType)->setPackageTheme($packageTheme)->setId($newId)->save();
			}
			$this->_widgetInstanceIds[$widget->getAttribute('id')] = $model->getId();
			$data['id'] = $model->getId();
			$test[$model->getId()] = unserialize($data['widget_parameters']);
		}
		return $this;
	}
	
	public function importThemeFrameworkArea($xmlDoc){
		$areas = $xmlDoc->getElementsByTagname('area');
		foreach($areas as $area){
			$data = array(
				'package_theme'	=> $area->getElementsByTagName("package_theme")->item(0)->nodeValue,
				'layout' 		=> $area->getElementsByTagName("layout")->item(0)->nodeValue,
				'is_active' 	=> $area->getElementsByTagName("is_active")->item(0)->nodeValue,
				'content_decode'=> unserialize($area->getElementsByTagName("content")->item(0)->nodeValue)
			);
			$model = Mage::getModel($this->_themeFrameworkAreaModel)->setData($data)->setStores(array(0))->save();
		}
		return $this;
	}
	
	public function replacer($m){
		$seps = explode(' ',$m[1]);
		$paramsString = '{{widget type="tabs/group"';
		foreach($seps as $sep){
			if(preg_match('/instance_(.*)"(.*)"/U',$sep)){
				$sep = preg_replace_callback('/(.*)"(.*)"/U',array($this,'replacement'),$sep);
			}
			$paramsString .= $sep.' ';
		}
		return trim($paramsString,' ').'}}';
	}

	public function replacement($matches){
		return $matches[1].'"'.$this->_widgetInstanceIds[$matches[2]].'"';
	}
	
	public function updateWidgetTabsId(){
		foreach($this->_blockUpdateIds as $block){
			$content = preg_replace_callback('/{{widget type="tabs\/group"(.*)}}/U', array($this,'replacer'), $block->getContent());
			$block->setData('content',$content)->save();;
		}
		return $this;
	}
	
	public function importCustomSql($installer){
		# ADD MEGAMENU PRO
		$installer->run("
			CREATE TABLE IF NOT EXISTS {$installer->getTable('megamenupro')} (
			  `megamenupro_id` int(11) unsigned NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL default '',
			  `type` smallint(6) NOT NULL default '0',
			  `content` text NOT NULL default '', 
			  `css_class` varchar(255) NULL,
			  `status` smallint(6) NOT NULL default '0',
			  `created_time` datetime NULL,
			  `update_time` datetime NULL,
			  PRIMARY KEY (`megamenupro_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
		
		# ADD EM Slideshow2
		if(!$installer->tableExists($installer->getTable('slideshow2/slider'))){
			$table = $installer->getConnection()
				->newTable($installer->getTable('slideshow2/slider'))
				->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
					'identity'  => true,
					'nullable'  => false,
					'primary'   => true,
					), 'Slideshow ID')
				->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
					), 'Slideshow name')
				->addColumn('images', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'images')
				->addColumn('slider_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
					), 'Slideshow type')
				->addColumn('slider_params', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'Slideshow params')
				->addColumn('delay', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
					), 'Slideshow delay')
				->addColumn('touch', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
					), 'Slideshow touch')
				->addColumn('stop_hover', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
					), 'Slideshow stop hover')
				->addColumn('shuffle_mode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
					), 'Slideshow shuffle mode')
				->addColumn('stop_slider', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
					), 'Slideshow stop slider')
				->addColumn('stop_after_loop', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
					), 'Slideshow stop after loop')
				->addColumn('stop_at_slide', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
					), 'Slideshow stop at slide')
				->addColumn('position', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'position')
				->addColumn('appearance', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'appearance')
				->addColumn('navigation', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'navigation')
				->addColumn('thumbnail', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'thumbnail')
				->addColumn('visibility', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'visibility')
				->addColumn('trouble', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
					), 'trouble')
				->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
					), 'Slideshow Creation Time')
				->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
					), 'Slideshow Modification Time')
				->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
					'nullable'  => false,
					'default'   => '0',
					), 'Is Slideshow Active')
				->setComment('EM Slideshow2 Slider Table');
			$installer->getConnection()->createTable($table);
		}
		return $this;
	}
}