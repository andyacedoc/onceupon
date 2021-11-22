<?php

namespace Amasty\AndyModule\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;

class Index extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function renderA(string $f)
    {
        return 'Hi, ' . $f;
    }

    public function getWelcomeText()
    {
        return $this->scopeConfig->getValue('andy_config/general/welcome_text') ?: 'NothinG.';
    }

    public function showInputQty()
    {
        $showinput = false;

        if ($this->scopeConfig->isSetFlag('andy_config/general/qty_enabled')) {
            $showinput = $this->scopeConfig->getValue('andy_config/general/qty_value');
        }

        return $showinput;
    }
}
