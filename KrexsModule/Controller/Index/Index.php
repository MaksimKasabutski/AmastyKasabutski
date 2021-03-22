<?php

namespace Amasty\KrexsModule\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Index extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    private $checkoutSession;

    private $productRepository;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        Context $context
)
    {
        $this->checkoutSession=$checkoutSession;
        $this->scopeConfig=$scopeConfig;
        $this->productRepository=$productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        /*$quote = $this->checkoutSession->getQuote();

        if(!$quote->getId()) {
            $quote->save();
        }

        $product = $this->productRepository->get('24-MB01');

        $quote->addProduct($product, 2);
        $quote->save();*/

        if($this->scopeConfig->isSetFlag('krexs_config/general/enabled')) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            die('Модуль не включен');
        }
    }
}
