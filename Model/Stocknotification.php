<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Model;

use Magento\Framework\Exception\LocalizedException as CoreException;

/**
 * Stocknotification Model class
 */

class Stocknotification extends \Magento\Framework\Model\AbstractModel
{
	/**
	 * init Model class
	 */
  	protected function _construct()
    {
        $this->_init('MagePrakash\OutofStockNotification\Model\ResourceModel\Stocknotification');
    }
}