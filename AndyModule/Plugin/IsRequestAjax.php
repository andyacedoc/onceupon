<?php

namespace Amasty\AndyModule\Plugin;

use Magento\Framework\App\RequestInterface as Request;

class IsRequestAjax
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function beforeExecute(
        $subject,
        $observer
    ) {
        if ($this->request->isAjax) {
            $observer->setData('sku_check', '');

            return [$observer];
        }
    }
}
