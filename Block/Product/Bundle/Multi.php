<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */
namespace MagePrakash\OutofStockNotification\Block\Product\Bundle;

class Multi extends \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle\Option
{
    /**
     * @var template
     */
    protected $_template = 'MagePrakash_OutofStockNotification::Product/bundle/option/multi.phtml';

    /**
     * @inheritdoc
     * @since 100.2.0
     */
    protected function assignSelection(\Magento\Bundle\Model\Option $option, $selectionId)
    {
        if (is_array($selectionId)) {
            foreach ($selectionId as $id) {
                if ($id && $option->getSelectionById($id)) {
                    $this->_selectedOptions[] = $id;
                }
            }
        } else {
            parent::assignSelection($option, $selectionId);
        }
    }
}
