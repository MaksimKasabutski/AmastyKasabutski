<?php

namespace Amasty\SecondKrexModule\Plugin;

class ChangeFormActionPlugin
{
    public function aroundGetFormAction($subject)
    {
        return 'checkout/cart/add';
    }
}
