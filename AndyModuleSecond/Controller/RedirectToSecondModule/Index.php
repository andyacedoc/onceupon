<?php

namespace Amasty\AndyModuleSecond\Controller\RedirectToSecondModule;

use Amasty\AndyModule\Controller\Index\Index as AndyModuleControllerIndex;
use Magento\Framework\Controller\ResultFactory;

class Index extends AndyModuleControllerIndex
{
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $redirectResult->setPath('anypagesecond');
        }

        return parent::execute();
    }
}
