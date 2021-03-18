<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Amasty\KrexsModule\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Catalog index page controller.
 */
class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        die('It\'s works');
    }
}
