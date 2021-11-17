<?php

namespace Amasty\AndyModule\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template
{
    public function renderA(string $f)
        {
            return 'Hi, ' . $f;
        }
}
