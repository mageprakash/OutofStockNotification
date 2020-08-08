<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */
namespace MagePrakash\OutofStockNotification\Cron;

use Magento\Framework\App\Action\Context;

class Notification extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $_stockItemRepository;

    /**
     * @var \MagePrakash\OutofStockNotification\Helper\Data
     */
    protected $_mageprakashHelper;

     /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezoneInterface;


    /** 
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \MagePrakash\OutofStockNotification\Helper\Data $mageprakashHelper
     * @param \\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
     */
    public function __construct(
        Context $context, 
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \MagePrakash\OutofStockNotification\Helper\Data $mageprakashHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
    )
    {
        $this->productRepository = $productRepository;
        $this->_stockItemRepository = $stockItemRepository;     
        $this->_mageprakashHelper = $mageprakashHelper;
        $this->_timezoneInterface = $timezoneInterface;
        parent::__construct($context);
    }
    
    /**
     * Cron for stock notification
     */
    public function execute()
    {
        if(!$this->_mageprakashHelper->isEnable()){
            return;
        }
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/stock_alert.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $currentTime= $this->_timezoneInterface->date()->format('H:i:s');
        $sendTime = $this->_mageprakashHelper->getSendTime();
        $sendtime = str_replace(',', ':', $sendTime);
        $currentDateTime = date('h:i:s', strtotime($currentTime));
        $diffrenceTime = (int)strtotime($sendtime) - (int)strtotime($currentDateTime);

             
        try {
            if($sendtime!='00:00:00')
            {
              


               if(abs($diffrenceTime) < 100 && abs($diffrenceTime) >=0){
                    $logger->info('cron ran...');
                    if($this->_mageprakashHelper->sendReportToAdmin()){
                        $this->_mageprakashHelper->notifySubscriptionStatus($this->_mageprakashHelper->getAdminEmail(), 'admin', $this->_mageprakashHelper->getEmailTemplate());
                    }
                    $subscribers = $this->_mageprakashHelper->getSubscribers();
                    foreach ($subscribers as $subscriber) {

                        try {
                            $product = $this->getProduct($subscriber->getProductSku());
                            $stock = $this->getStockItem($product->getId());

                            if($stock->getIsInStock()){
                                $this->_mageprakashHelper->sendNotifications($this->_mageprakashHelper->getStockNotifyCustomer(), $subscriber->getSku());
                            }
                        } catch (Exception $e) {
                            $logger->info('Something is wrong...'.$e->getMessage());            
                        }
                    }
                        
                    if($this->_mageprakashHelper->sendReportToAdminForProduct()){
                        $this->_mageprakashHelper->notifySubscriptionStatus($this->_mageprakashHelper->getAdminEmail(), 'admin', $this->_mageprakashHelper->getLowQtyStockNotifyCustomer());

                    }

                 }
            }
            else
            {
                if($this->_mageprakashHelper->sendReportToAdmin()){
                        $this->_mageprakashHelper->notifySubscriptionStatus($this->_mageprakashHelper->getAdminEmail(), 'admin', $this->_mageprakashHelper->getEmailTemplate());
                    }
                    $subscribers = $this->_mageprakashHelper->getSubscribers();
                    foreach ($subscribers as $subscriber) {

                        try {
                            $product = $this->getProduct($subscriber->getProductSku());
                            $stock = $this->getStockItem($product->getId());

                            if($stock->getIsInStock()){
                                $this->_mageprakashHelper->sendNotifications($this->_mageprakashHelper->getStockNotifyCustomer(), $subscriber->getSku());
                            }
                        } catch (Exception $e) {
                            $logger->info('Something is wrong...'.$e->getMessage());            
                        }
                    }
                   
                    if($this->_mageprakashHelper->sendReportToAdminForProduct()){
                        $this->_mageprakashHelper->notifySubscriptionStatus($this->_mageprakashHelper->getAdminEmail(), 'admin', $this->_mageprakashHelper->getLowQtyStockNotifyCustomer());
                       $logger->info($this->_mageprakashHelper->getLowQtyStockNotifyCustomer());
                    }
            }
        } catch (Exception $e) {
            $logger->info('Something is wrong...'.$e->getMessage());
        }

        return $this;
    }


    /** 
     * @param $sku
     * @return object
     */
    public function getProduct($sku)
    {
        return $this->productRepository->get($sku);
    }

    /** 
     * @param $prodict Id
     * @return object
     */
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
}