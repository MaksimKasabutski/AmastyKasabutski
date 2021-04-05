<?php

namespace Amasty\SecondKrexModule\Plugin;

use Amasty\KrexModule\Block\Index as Block;

class ChangeConfigHelloMessage
{
    protected $block;

    public function __construct(Block $block)
    {
        $this->block = $block;
    }

    public function afterGetConfigHello($subject, $result)
    {
        return $result . $this->block->addString();
    }
}
