<?php

namespace Amasty\AndyModule\Model;

use Amasty\AndyModule\Model\BlacklistFactory;
use Amasty\AndyModule\Model\ResourceModel\Blacklist as BlacklistResource;

class BlacklistRepository
{
    /**
     * @var BlacklistFactory
     */
    protected $blacklistFactory;

    /**
     * @var BlacklistResource
     */
    protected $blacklistResource;

    public function __construct(
        BlacklistFactory $blacklistFactory,
        BlacklistResource $blacklistResource
    ) {
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
    }

    public function get($sku)
    {
        $item = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $item,
            $sku,
            'sku'
        );

        return $item;
    }
}
