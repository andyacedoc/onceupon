<?php

namespace Amasty\AndyModuleSecond\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class AddToCartPlugin
{
    const FORM_ACTION_ = 'checkout/cart/add';

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function aroundGetFormAction(
        $subject,
        callable $proceed
    ) {
        return self::FORM_ACTION_;
    }

    public function beforeExecute(
         $subject
    ) {
        try {
            $productId = $this->productRepository->get($_POST['sku'])->getId();
            $subject->getRequest()->setParams(['product' => $productId]);
        } catch (NoSuchEntityException $e) {
        }
    }


/*    public function beforeRenderA(
        $subject,
        $f
    ) {
        $f = 'BeforePlugin ' . $f;

        return [$f];
    }*/

/*    public function aroundRenderA(
        $subject,
        callable $proceed,
        $f
    ) {
        $f .= ' AroundPlugin';

        return $proceed($f);
    }*/

/*    public function afterRenderA(
        $subject,
        $result
    ) {

        return $result . ' AfterPlugin';
    }*/

}
