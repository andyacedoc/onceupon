<?php

namespace Amasty\AndyModuleSecond\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckNameObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $name = $observer->getData('name_check');

        if (strtolower($name) === 'fedya') {
            exit($name . ', this is he.');
        }
    }
}
