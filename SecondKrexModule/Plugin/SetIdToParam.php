<?php

namespace Amasty\SecondKrexModule\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class SetIdToParam
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        RequestInterface $request,
        ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    public function beforeExecute($subject)
    {
        $sku = $this->request->getParam('sku');
        $productId = $this->productRepository->get($sku)->getId();
        $this->request->setParam('product', $productId);
    }
}
