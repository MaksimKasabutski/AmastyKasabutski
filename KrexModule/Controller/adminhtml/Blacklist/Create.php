<?php

namespace Amasty\KrexModule\Controller\Adminhtml\Blacklist;

class Create extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
