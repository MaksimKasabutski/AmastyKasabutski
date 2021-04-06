<?php

namespace Amasty\KrexModule\Controller\Cart;

use Amasty\KrexModule\Model\Blacklist;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Amasty\KrexModule\Model\BlacklistFactory;
use Amasty\KrexModule\Model\ResourceModel\Blacklist as BlacklistResource;

class Add extends Action
{
    /**
     * @var BlacklistFactory
     */
    protected $blacklistFactory;

    /**
     * @var BlacklistResource
     */
    protected $blacklistResource;

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

    public function __construct(
        BlacklistResource $blacklistResource,
        BlacklistFactory $blacklistFactory,
        EventManager $eventManager,
        Context $context,
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession
    )
    {
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
        $this->eventManager = $eventManager;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->sku = $this->getRequest()->getParam('sku');
        $qty = $this->getRequest()->getParam('qty');
        $product = $this->initProduct($this->sku);

        if (!$product) {
            return $this->redirectBack();
        }

        $qtyStock = $product->getData('quantity_and_stock_status/qty');
        $quote = $this->checkoutSession->getQuote();

        /** @var Blacklist $blacklist */
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $this->sku,
            'sku'
        );
        $blacklistQty = $blacklist->getQty();

        if ($product->getTypeId() !== \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            $this->messageManager->addErrorMessage('This is not a simple product!');
            return $this->redirectBack();
        }

        //Если SKU из блэклиста и общее количество товара больше доступного, добавляем возможное количество
        if ($blacklistQty && $this->getResultQty($quote, $qty) > $blacklistQty) {
            $availableQty = $blacklistQty - $this->getResultQty($quote, 0);
            if ($availableQty > 0) {
                $quote->addProduct($product, $availableQty);
                $quote->collectTotals()->save();
                $this->messageManager->addErrorMessage(sprintf('Аdded all available units (%s).', $availableQty));
                $this->eventManager->dispatch(
                    'amasty_krexmodule_is_forsku',
                    ['sku' => $this->sku]
                );
            } else {
                $this->messageManager->addErrorMessage('There is no more available units.');
            }
        } else {
            //Если SKU из блэклиста и общее количество товара меньше доступного, происходит обычное добавление,
            //но меняем qtyStock на qty из блэклиста (на случай, если в блэклисте qty больше)
            if ($blacklistQty) {
                $qtyStock = $blacklistQty;
            }
            if ($this->getResultQty($quote, $qty) > $qtyStock) {
                $this->messageManager->addErrorMessage(sprintf('Only %s units are available.', $qtyStock));
            } else {
                $quote->addProduct($product, $qty);
                $quote->collectTotals()->save();
                $this->messageManager->addSuccessMessage('Product added successfully.');
                $this->eventManager->dispatch(
                    'amasty_krexmodule_is_forsku',
                    ['sku' => $this->sku]
                );
            }
        }

        return $this->redirectBack();
    }

    protected function getResultQty($quote, $qty)
    {
        $cartQty = 0;
        foreach ($quote->getAllItems() as $item) {
            if ($item->getSku() === mb_strtoupper($this->sku)) {
                $cartQty = $item->getQty();
            }
        }
        return $cartQty ? $cartQty + $qty : $qty;
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
