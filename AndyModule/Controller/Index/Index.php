<?php

namespace Amasty\AndyModule\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    public function execute()
    {
        //exit('whatwhat');
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
