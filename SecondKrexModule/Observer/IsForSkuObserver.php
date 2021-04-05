<?php

namespace Amasty\SecondKrexModule\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class IsForSkuObserver implements ObserverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    private $messageManager;

    public function __construct(
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig,
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession
    )
    {
        $this->messageManager = $messageManager;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $sku = $observer->getData('sku');

        foreach ($this->getForSku() as $item) {
            if ($sku === $item) {
                $promoSku = $this->scopeConfig->getValue('second_krex_config/general/promo_sku');
                $product = $this->initProduct($promoSku);
                if ($product) {
                    $quote = $this->checkoutSession->getQuote();
                    $quote->addProduct($product, 1);
                    $quote->collectTotals()->save();
                    $this->messageManager->addSuccessMessage('Promo product added successfully.');
                }
            }
        }
    }

    private function getForSku(): array
    {
        return explode(',', $this->scopeConfig->getValue('second_krex_config/general/for_sku'));
    }

    private function initProduct($productSku)
    {
        if ($productSku) {
            try {
                return $this->productRepository->get($productSku);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }
}
