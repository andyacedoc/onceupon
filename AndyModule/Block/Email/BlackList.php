<?php

namespace Amasty\AndyModule\Block\Email;

use Magento\Framework\View\Element\Template;

class BlackList extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getField()
    {
        return $this->getBlackList();
    }
}
