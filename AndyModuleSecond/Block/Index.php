<?php

namespace Amasty\AndyModuleSecond\Block;

Use Amasty\AndyModule\Block\Index as ExIndex;

class Index extends ExIndex
{
    public function renderA(string $f)
    {
        return 'Override ' . $f;
    }
}
