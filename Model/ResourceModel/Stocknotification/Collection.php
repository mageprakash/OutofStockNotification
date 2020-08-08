<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Model\ResourceModel\Stocknotification;

/** 
 * Stocknotification model collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
     * init constructor
     */
  	protected function _construct()
    {
        $this->_init('MagePrakash\OutofStockNotification\Model\Stocknotification', 'MagePrakash\OutofStockNotification\Model\ResourceModel\Stocknotification');
    }
}