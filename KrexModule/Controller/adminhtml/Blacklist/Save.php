<?php

namespace Amasty\KrexModule\Controller\Adminhtml\Blacklist;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Amasty\KrexModule\Model\BlacklistFactory;
use Amasty\KrexModule\Model\ResourceModel\Blacklist as BlacklistResource;

class Save extends Action
{
    private $blacklistFactory;

    private $blacklistResource;

    public function __construct(
        Context $context,
        BlacklistResource $blacklistResource,
        BlacklistFactory $blacklistFactory
    )
    {
        $this->blacklistResource = $blacklistResource;
        $this->blacklistFactory = $blacklistFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            $productId = $this->getRequest()->getParam('product_id');

            try {
                $product = $this->blacklistFactory->create();

                if ($productId) {
                    $this->blacklistResource->load($product, $productId);
                }

                $product->addData($data);
                $this->blacklistResource->save($product);
                $this->messageManager->addSuccessMessage(__('Product saved.'));
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage($exception, $exception->getMessage());
            }
        }
        return $this->_redirect('*/*/index');
    }
}
