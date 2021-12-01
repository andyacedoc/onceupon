<?php

namespace Amasty\AndyModuleSecond\Controller\RedirectToSecondModule;

use Amasty\AndyModule\Controller\Index\Index as AndyModuleControllerIndex;
use Magento\Framework\Controller\ResultFactory;

class Index extends AndyModuleControllerIndex
{
    /**
     * @var AndyModuleControllerIndex $andyModuleControllerIndex
     */
    private $andyModuleControllerIndex;

    public function execute()
    {
        //Как здесь проверить регистрацию пользователя?
        //Вроде бы ронимаю, что надо достать отсюда $this->andyModuleControllerIndex->customerSession->isLoggedIn()
        //но не получается

        if (5<3) {
            $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $redirectResult->setPath('anypagesecond');
        }

        return parent::execute();
    }
}
