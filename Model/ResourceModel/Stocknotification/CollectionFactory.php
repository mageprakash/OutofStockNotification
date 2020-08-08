<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Model\ResourceModel\Stocknotification;

class CollectionFactory
{
    /**
     * Object Managet
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;
    
    /**
     * Instance name
     *
     */
    protected $_instanceName = null;
    
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\MagePrakash\\OutofStockNotification\\Model\\ResourceModel\\Stocknotification\\Collection')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * @return instance
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}