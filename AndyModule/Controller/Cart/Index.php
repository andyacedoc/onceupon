<?php

namespace Amasty\AndyModule\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface;
//
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;

class Index extends Action
            implements CsrfAwareActionInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ManagerInterface
     */
    private $managerInterface;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        CheckoutSession $checkoutSession,
        ManagerInterface $managerInterface
    ) {
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        $this->managerInterface = $managerInterface;
        parent::__construct($context);
    }

    public function createCsrfValidationException(RequestInterface $request): ? InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ? bool
    {
        return true;
    }

    public function execute()
    {
        $messagecart = '';
        $post = $this->getRequest()->getPostValue();
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('sku', $post['sku']);

        if (count($collection->getData()) != 0) {
            $product = $this->productRepository->get($post['sku']);
            if (($product->getTypeId() === 'simple')
                && ($product->getQty() >= $post['qty'])) {
                    $qoute = $this->checkoutSession->getQuote();

                    if(!$qoute->getId()) {
                        $qoute->save();
                    }
                    $qoute->addProduct($product,$post['qty']);
                    $qoute->save();

                    $this->managerInterface->dispatch(
                'amasty_andymodule_check_sku',
                        ['sku_check' => $post['sku']]
                    );

                    $messagecart = 'Товар добавлен в корзину.';
            } else {
                $messagecart = 'Товар не "simple" или его количества недостаточно.';
            }
        } else {
            $messagecart = 'Товара нет вообще.';
        }

        echo $messagecart;

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
