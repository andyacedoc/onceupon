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

    public function welcomeText()
    {
        return $this->scopeConfig->getValue('andy_config/general/welcome_text') ?: 'NothinG.';
    }

    public function showInputQty()
    {
        $showinput = '';
        if ($this->scopeConfig->isSetFlag('andy_config/general/qty_enabled')) {
            $class_css = $this->hasData("css_class_3") ? $this->getData("css_class_3") : "";
            $valuedefault = $this->scopeConfig->getValue('andy_config/general/qty_value');
            $showinput = 'Количество:<br><input type="number" class='
                    . $class_css . ' id="qtyid" name="qty" value="'
                    . $valuedefault . '"><br>';
        }
        return $showinput;
    }
}
