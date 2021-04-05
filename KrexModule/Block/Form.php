<?php

namespace Amasty\KrexModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Form extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    const FORM_ACTION = 'krex/cart/add';

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function isShowQtyField(): bool
    {
        return $this->scopeConfig->isSetFlag('krex_config/general/show_qty');
    }

    public function getQtyValue(): string
    {
        return $this->scopeConfig->getValue('krex_config/general/qty_default_value') ?: '';
    }

    public function getFormAction()
    {
        return self::FORM_ACTION;
    }
}
