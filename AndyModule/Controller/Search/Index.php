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

    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $queryText = $this->getRequest()->getParam('q');
        $responsePost = [];
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('type_id', 'simple');
        $collection->addAttributeToFilter('sku', ['like' => "%$queryText%"]);
        $collection->addAttributeToSelect('name');
        $collection->setPageSize(15);

        foreach ($collection as $product) {
            $responsePost[] = [
                'sku' => $product->getSku(),
                'name' => $product->getName()
            ];
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responsePost);

        return $resultJson;
    }
}
