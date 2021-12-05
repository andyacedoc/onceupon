<?php

namespace Amasty\AndyModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blacklist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            'amasty_andymodule_blacklist',
            'black_id'
        );
    }
}
