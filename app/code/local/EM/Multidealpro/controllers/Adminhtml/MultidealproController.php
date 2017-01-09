<?php
class EM_Multidealpro_Adminhtml_MultidealproController extends Mage_Adminhtml_Controller_Action
{
	/**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro');
                break;
        }
    }

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('multidealpro/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('multidealpro/multidealpro')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			$_product = Mage::getModel('catalog/product')->load($model->getProductId());
			$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($model->getProductId());

			if($model->getStatus() == 0){
				$str = base64_decode($model->getHasNew());
				$plit = explode("tmp",$str);
				$price 	= $plit[0];
				$from 	= $plit[1];
				$to 	= $plit[2];
			}elseif($model->getStatus() == 1){
				$price 	= $_product->getSpecialPrice();
				$from 	= $_product->getSpecialFromDate();
				$to 	= $_product->getSpecialToDate();
			}elseif($model->getStatus() == 2){
				$str = base64_decode($model->getHasEnd());
				$plit = explode("tmp",$str);
				$price 	= $plit[0];
				$from 	= $plit[1];
				$to		= $plit[2];
			}

			$model->setData('product_name',$_product->getName());
			$model->setData('price',$price);
			$model->setData('date_from',$from);
			$model->setData('date_to',$to);
			$model->setData('qty',$stock->getQty());

			Mage::register('multidealpro_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('multidealpro/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit'))
				->_addLeft($this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost('data')) {

			$productId	= $data['product_id'];

			$product = Mage::getModel('catalog/product')->load($productId);
			$product->setSpecialPrice($data['price'])
				->setSpecialFromDate($data['date_from'])
				->setSpecialFromDateIsFormated(true)
				->setSpecialToDate($data['date_to'])
				->setSpecialToDateIsFormated(true);

			// load stock item for specific product
			$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
			$stock->setQty($data['qty']);

			unset($data['price'],$data['date_from'],$data['date_to'],$data['qty']);
			$data['status'] = "";
			$data['has_new'] = "";
			$data['has_end'] = "";
			$model = Mage::getModel('multidealpro/multidealpro');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));

			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}

				$model->save();
				$product->save();
				$stock->save();

				Mage::helper('multidealpro')->checkDeal($model);

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('multidealpro')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}

	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('multidealpro/multidealpro');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $multidealproIds = $this->getRequest()->getParam('multidealpro');
        if(!is_array($multidealproIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($multidealproIds as $multidealproId) {
                    $multidealpro = Mage::getModel('multidealpro/multidealpro')->load($multidealproId);
                    $multidealpro->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($multidealproIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $multidealproIds = $this->getRequest()->getParam('multidealpro');
        if(!is_array($multidealproIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($multidealproIds as $multidealproId) {
                    $multidealpro = Mage::getModel('multidealpro/multidealpro')
                        ->load($multidealproId)
                        ->setIsActive($this->getRequest()->getParam('is_active'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($multidealproIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'multidealpro.csv';
        $content    = $this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
	
	/**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit_tab_product', 'category.product.grid')
                ->toHtml()
        );
    }

    public function exportXmlAction()
    {
        $fileName   = 'multidealpro.xml';
        $content    = $this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}