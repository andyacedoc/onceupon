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
use Amasty\AndyModule\Model\ResourceModel\Blacklist\CollectionFactory as BlacklistCollectionFactory;
use Amasty\AndyModule\Model\BlacklistRepository;
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

    /**
     * @var BlacklistCollectionFactory
     */
    private $blacklistCollectionFactory;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        CheckoutSession $checkoutSession,
        ManagerInterface $managerInterface,
        BlacklistCollectionFactory $blacklistCollectionFactory,
        BlacklistRepository $blacklistRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        $this->managerInterface = $managerInterface;
        $this->blacklistCollectionFactory = $blacklistCollectionFactory;
        $this->blacklistRepository = $blacklistRepository;
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

    private function getQty($postSku, $postQty, $qoute)
    {
        /**
         * @var \Amasty\AndyModule\Model\Blacklist $blackList
         */

        $blackList = $this->blacklistRepository->get($postSku);

        if ($blackList->hasData()) {
            $blackListQty = $blackList->getQty();
            $qouteQty = 0;
            $items = $qoute->getAllItems();

            foreach ($items as $item) {
                if ($item->getSku() ==  $postSku) {
                    $qouteQty = $item->getQty();
                    break;
                }
            }

            $summaryQty = $postQty + $qouteQty;

            if ($summaryQty <= $blackListQty) {
                $qty = $postQty;
            } else {
                $qty = $blackListQty - $qouteQty;
                $qty = (($qty > 0) ? $qty : 0);
            }

            return $qty;

         }

         return $postQty;
    }

    public function execute()
    {
        try {
            $postSku = $this->getRequest()->getParam('sku');
            $postQty = $this->getRequest()->getParam('qty');
            $product = $this->productRepository->get($postSku);

            if (($product->getTypeId() === 'simple')
                && ($product->getQuantityAndStockStatus()['qty'] >= $postQty)) {
                    $qoute = $this->checkoutSession->getQuote();

                    if(!$qoute->getId()) {
                        $qoute->save();
                    }

                    $qty = $this->getQty($postSku, $postQty, $qoute);

                    If ($qty != 0) {
                        $qoute->addProduct($product,$qty);
                        $qoute->save();

                        $this->managerInterface->dispatch(
                    'amasty_andymodule_check_sku',
                            ['sku_check' => $postSku]
                        );
                    }

                    if ($qty < $postQty) {
                        $this->messageManager->addNoticeMessage('Действует ограничение.
                                        Товар добавлен в корзину в количестве ' . $qty . ' единиц(ы),
                                        вместо заявленных ' . $postQty . ' единиц(ы).');
                    } else {
                        $this->messageManager->addSuccessMessage('Товар добавлен в корзину.');
                    }

            } else {
                $this->messageManager->addNoticeMessage('Товар не "simple" или его количества недостаточно.');
            }

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Товара нет вообще (или другая ошибка).');
        }

        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirectResult->setPath('anypage');
    }
}
