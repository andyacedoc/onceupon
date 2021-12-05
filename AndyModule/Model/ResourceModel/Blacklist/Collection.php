<?php

namespace Amasty\AndyModule\Model\ResourceModel\Blacklist;

use Amasty\AndyModule\Model\Blacklist;
use Amasty\AndyModule\Model\ResourceModel\Blacklist as BlacklistResource;
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
