<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */
namespace MagePrakash\OutofStockNotification\Block\Product\Bundle;

class Checkbox extends \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle\Option
{
    /**
     * @var template
     */
    protected $_template = 'MagePrakash_OutofStockNotification::Product/bundle/option/checkbox.phtml';
}
