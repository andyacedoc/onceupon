<?php

namespace Amasty\AndyModule\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
//
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;


class Index extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
//        $qoute = $this->checkoutSession->getQuote();
//        if(!$qoute->getId()) {
//            $qoute->save();
//        }
//
//        $product = $this->productRepository->get('24-MB04');
//        $qoute->addProduct($product,5);
//        $qoute->save();
//        exit('kwota');

//        $collection = $this->productCollectionFactory->create();
//        $collection->addAttributeToFilter('sku', '24-MB01');
//        $collection->addAttributeToSelect('name');
//        $collection->addAttributeToSelect('description');
//
//        foreach ($collection as $product) {
//            echo $product->getName();
//            echo $product->getDescription();
//        }
//        exit('productlist');

        if ($this->scopeConfig->isSetFlag('andy_config/general/enabled')) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            exit('Модуль выключен.');
        }
    }
}
