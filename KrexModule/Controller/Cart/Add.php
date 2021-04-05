<?php

namespace Amasty\KrexModule\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Event\ManagerInterface as EventManager;

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

    /**
     * @var EventManager
     */
    private $eventManager;

    private $sku;

    private $qty;

    public function __construct(
        EventManager $eventManager,
        Context $context,
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession
    )
    {
        $this->eventManager = $eventManager;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->sku = $this->getRequest()->getParam('sku');
        $this->qty = $this->getRequest()->getParam('qty');
        $product = $this->initProduct($this->sku);

        if (!$product) {
            return $this->redirectBack();
        }

        $qtyStock = $product->getData('quantity_and_stock_status/qty');
        $quote = $this->checkoutSession->getQuote();

        if ($product->getTypeId() !== \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            $this->messageManager->addErrorMessage('This is not a simple product!');
        } elseif ($this->getResultQty($quote) > $qtyStock) {
            $this->messageManager->addErrorMessage(sprintf('Only %s units are available.', $qtyStock));
        } else {
            if (!$quote->getId()) {
                $quote->save();
            }
            $quote->addProduct($product, $this->qty);
            $quote->collectTotals()->save();
            $this->messageManager->addSuccessMessage('Product added successfully.');
            $this->eventManager->dispatch(
                'amasty_krexmodule_is_forsku',
                ['sku' => $this->sku]
            );
        }

        return $this->redirectBack();
    }

    protected function getResultQty($quote)
    {
        $cartQty = 0;
        foreach ($quote->getAllItems() as $item) {
            if ($item->getSku() === mb_strtoupper($this->sku)) {
                $cartQty = $item->getQty();
            }
        }
        return $cartQty ? $cartQty + $this->qty : $this->qty;
    }

    protected function initProduct($productSku)
    {
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
        return $this->_redirect($this->_redirect->getRefererUrl());
    }


}
