<?php

namespace Amasty\AndyModuleSecond\Observer;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckSkuObserver implements ObserverInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $sku = strtolower($observer->getData('sku_check'));
        $promo_sku = trim($this->scopeConfig->getValue('andy_second_config/general_second/promo_sku'));
        $for_sku = explode(',',
                str_replace(' ', '',
                strtolower(
                    $this->scopeConfig->getValue('andy_second_config/general_second/for_sku'
                ))));

        if (in_array($sku, $for_sku)) {
                $product = $this->productRepository->get($promo_sku);
                $this->checkoutSession->getQuote()->addProduct($product,1)->save();
        }
    }
}
