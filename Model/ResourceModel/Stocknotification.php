<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException as CoreException;
/**
 * Stocknotification Model class
 */
class Stocknotification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	/**
	 * init Model class
	 */
  	protected function _construct()
    {
        $this->_init('subscribe_product_notification', 'entity_id');
    }
}