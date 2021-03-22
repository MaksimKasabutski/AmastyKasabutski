<?php

namespace Amasty\KrexsModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Form extends Template
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

    public function getQtyFieldShowStatus(): bool
    {
        return $this->scopeConfig->isSetFlag('krexs_config/general/show_qty');
    }

    public function getQtyValue(): string
    {
       $value = $this->scopeConfig->getValue('krexs_config/general/qty_default_value');
       return $value ? $value : '';
    }
}
