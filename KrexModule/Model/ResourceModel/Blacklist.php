<?php

namespace Amasty\KrexModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blacklist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            'amasty_krexmodule_blacklist',
            'product_id'
        );
    }
}
