<?php

namespace Amasty\AndyModule\Model;

use Amasty\AndyModule\Api\Data\AnyInterface;

class AnyProvider implements AnyInterface
{
    public function getName(): string
    {
        return 'This is AnyProvider from First module.';
    }
}
