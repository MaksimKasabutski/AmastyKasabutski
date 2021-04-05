<?php

namespace Amasty\KrexModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Index extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getConfigHello(): string
    {
        return $this->scopeConfig->getValue('krex_config/general/greeting_text');
    }

    public function addString()
    {
        return ' It works!';
    }
}
