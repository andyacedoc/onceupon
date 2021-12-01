<?php

namespace Amasty\AndyModuleSecond\Model;

use Amasty\AndyModule\Api\Data\AnyInterface;

class SecondProvider implements AnyInterface
{
    public function getName(): string
    {
        return 'This is Second Interface from another module';
    }
}
