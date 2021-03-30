<?php

namespace Amasty\KrexModule\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Config
     */
    private $pageConfig;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context,
        Config $pageConfig,
        PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->pageConfig = $pageConfig;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->scopeConfig->isSetFlag('krex_config/general/enabled')) {
            return $this->resultPageFactory->create();
        } else {
            $this->messageManager->addErrorMessage('Module \'KrexModule\' is off.');
            return $this->_redirect('defaultNoRoute');
        }
    }
}
