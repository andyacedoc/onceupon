<?php

namespace Amasty\AndyModule\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Blacklist
 * @method string getSku()
 * @method integer getQty()
 */

class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(
            ResourceModel\Blacklist::class
        );
    }
}
