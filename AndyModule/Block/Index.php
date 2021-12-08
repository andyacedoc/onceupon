<?php

namespace Amasty\AndyModule\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Event\ManagerInterface;
//
use Amasty\AndyModule\Api\Data\AnyInterface;

class Index extends Template
{
    const FORM_ACTION = 'anypage/cart';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ManagerInterface
     */
    private $managerInterface;

    /**
     * @var AnyInterface
     */
    private $anyInterface;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        ManagerInterface $managerInterface,
        AnyInterface $anyInterface,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->managerInterface = $managerInterface;
        $this->anyInterface = $anyInterface;
        parent::__construct($context, $data);
    }

    public function renderA(string $f)
    {
//        $this->managerInterface->dispatch(
//            'amasty_andymodule_check_name',
//            ['name_check' => $f]
//        );

//        $f = $this->anyInterface->getName();

        return 'Hi, ' . $f;
    }

    public function getWelcomeText()
    {
        return $this->scopeConfig->getValue('andy_config/general/welcome_text') ?: 'NothinG.';
    }

    public function isShowInputQty()
    {
        $isInput = false;

        if ($this->scopeConfig->isSetFlag('andy_config/general/qty_enabled')) {
            $isInput = true;
        }

        return $isInput;
    }

    public function getShowInputQty()
    {
        return $this->scopeConfig->getValue('andy_config/general/qty_value');
    }

    public function getFormAction()
    {
        return self::FORM_ACTION;
    }
}
