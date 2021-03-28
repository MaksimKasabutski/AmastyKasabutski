<?php

namespace Amasty\KrexsModule\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;

class Add extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Context $context,
        CheckoutSession $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $qty = $params['qty'];
        $product = $this->initProduct();

        if (!$product) {
            $this->redirectBack();
        }

        if($product->getData('quantity_and_stock_status/qty') < $params['qty']) {
            $this->messageManager->addErrorMessage('Only '. $product->getData('quantity_and_stock_status/qty') . ' units are available.');
        } elseif (($product->getData())['type_id'] !== 'simple') {
            $this->messageManager->addErrorMessage('This is not a simple product!');
        } else {
            $quote = $this->checkoutSession->getQuote();
            if (!$quote->getId()) {
                $quote->save();
            }
            $quote->addProduct($product, $qty);
            $quote->save();
            $this->messageManager->addSuccessMessage('Product added successfully.');
        }
        $this->redirectBack();
    }

    protected function initProduct()
    {
        $productSku = $this->getRequest()->getParam('sku');
        if ($productSku) {
            try {
                return $this->productRepository->get($productSku);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage('There is no product whit this SKU.');
                return false;
            }
        }
        return false;
    }

    protected function redirectBack()
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $this->_redirect($referer);
    }
}
