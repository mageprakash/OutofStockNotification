<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Plugin;

class BeforeAllowProducts
{
    /**
     * @var \MagePrakash\OutofStockNotification\Block\Product\Notify
     */        

    protected $_notifyBlock;

    /**
     * @param \MagePrakash\OutofStockNotification\Block\Product\Notify $notifyBlock
     */    
    public function __construct(
        \MagePrakash\OutofStockNotification\Block\Product\Notify $notifyBlock
    )
    {
        $this->_notifyBlock = $notifyBlock;
    }

    /**
     * getAllowProducts
     *
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     *
     * @return array
     */    
    public function beforeGetAllowProducts($subject)
    {
        if ($this->_notifyBlock->getAllowSelectSimpleConfig() == 1) {
            if (!$subject->hasData('allow_products')) {
                $products = [];
                $allProducts = $subject->getProduct()->getTypeInstance()->getUsedProducts($subject->getProduct(), null);
                foreach ($allProducts as $product) {
                        $products[] = $product;
                }
                $subject->setData('allow_products', $products);
            }            
        }
        return [];
    }

}