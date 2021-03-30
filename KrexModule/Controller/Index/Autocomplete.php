<?php


namespace Amasty\KrexModule\Controller\Index;


use Magento\Catalog\Ui\DataProvider\Product\ProductCollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Autocomplete extends Action
{
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var JsonFactory
     */
    private $jsonResultFactory;

    public function __construct(
        JsonFactory $jsonResultFactory,
        ProductCollectionFactory $productCollectionFactory,
        Context $context
    )
    {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        $result->setData($this->getSearchCollection());
        return $result;
    }

    public function getSearchCollection(): array
    {
        $sku = $this->getRequest()->getParam('sku');
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('sku', array('like' => '%' . $sku . '%'));
        $collection->addAttributeToSelect('*');

        $searchCollection = [];
        foreach ($collection->getItems() as $product) {
            $searchCollection[$product->getName()] = $product->getSku();
        }
        return $searchCollection;
    }
}
