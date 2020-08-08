<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Model\Customer\Source;

use Magento\Framework\Module\Manager as ModuleManager;

class Sender implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        ModuleManager $moduleManager
    ) {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Return array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        $senderData = array();
        $senderData = [0 => "General Contact", 1 => "Sales Representative", 2 => "Customer Support", 3 => "Custom Email 1", 4 => "Custom Email 2" ];

        return $senderData;
    }
}
