<?php

namespace Amasty\AndyModule\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface;
//
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
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ManagerInterface
     */
    private $managerInterface;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession,
        ManagerInterface $managerInterface,
        BlacklistRepository $blacklistRepository
    ) {
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->managerInterface = $managerInterface;
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

                    try {
                        If ($qty != 0) {
                            $qoute->addProduct($product,$qty);
                            $qoute->save();
                            $this->managerInterface->dispatch(
                                'amasty_andymodule_check_sku',
                                ['sku_check' => $postSku]
                            );
                        }
                    } catch (\Exception $e) {
                        $this->messageManager->addNoticeMessage('Promo-товар не добавлен в корзину.');
                    }

                    if ($qty < $postQty) {
                        $this->messageManager->addNoticeMessage("Действует ограничение.
                                        Товар $postSku добавлен в корзину в количестве $qty единиц(ы),
                                        вместо заявленных $postQty единиц(ы).");
                    } else {
                        $this->messageManager->addSuccessMessage("Товар $postSku добавлен в корзину.");
                    }

            } else {
                $this->messageManager->addNoticeMessage("Товар $postSku не 'simple' или его количества недостаточно.");
            }

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage("Товара $postSku нет вообще.");
        }

        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirectResult->setPath('anypage');
    }
}
