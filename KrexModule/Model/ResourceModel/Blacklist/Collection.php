<?php

namespace Amasty\KrexModule\Model\ResourceModel\Blacklist;

use Amasty\KrexModule\Model\Blacklist;
use Amasty\KrexModule\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Blacklist::class,
            BlacklistResource::class
        );
    }
}
