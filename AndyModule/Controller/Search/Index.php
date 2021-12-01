<?php

namespace Amasty\AndyModule\Controller\Search;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class Index extends Action
{
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var \Magento\Framework\Controller\Result\Json $resultJson
     */

    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $querytext = $this->getRequest()->getParam('q');
        $response_post = [];
        $n = 0;
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('type_id', 'simple');
        $collection->addAttributeToFilter('sku', ['regexp' => ($querytext)]);
        $collection->addAttributeToSelect('name');
        $collection->setPageSize(15);

        foreach ($collection as $product) {
            $response_post[$n]['sku'] = $product->getSku();
            $response_post[$n]['name'] = $product->getName();
            $n++;
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($response_post);

        return $resultJson;
    }
}
