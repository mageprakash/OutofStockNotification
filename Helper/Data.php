<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */

namespace MagePrakash\OutofStockNotification\Helper;

/**
 * Data class for Helper
 */

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;


    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \MagePrakash\OutofStockNotification\Model\Stocknotification
     */
    protected $stockNotification;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \MagePrakash\OutofStockNotification\Model\Stocknotification $stockNotification
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \MagePrakash\OutofStockNotification\Model\Stocknotification $stockNotification,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_escaper = $escaper;
        $this->messageManager = $messageManager;
        $this->stockNotification = $stockNotification;
        $this->dateTime = $dateTime;
    }


    /**
     * @return Boolean
    */
    public function isEnable()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/general/is_active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return Boolean
    */
    public function isShowNotifyOnCategory()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/general/subscription_on_category', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    

    /**
     * @return String
    */
    public function getNotificationMessage()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/general/notification_message', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return Array
    */
    public function getCustomerGroup()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/customer_stock_subscription/allow_customer_group', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return String
    */
    public function getMinQtyMail()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/customer_stock_subscription/min_qty_for_mail', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return String
    */
    public function getAllowSelectSimpleBundle()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/general/select_simpleofbundle', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return String
    */
    public function getAllowSelectSimpleConfig()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/general/select_simpleofconf', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    

    /**
     * @return String
    */
    public function getNotificationSender()
    {
        return $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/notification_email_sender', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerSession->isLoggedIn() ? $this->customerSession->getCustomerId() : 0;
    }

    /**
     * @return boolean
     */
    public function notifySubscriptionStatus($email = '', $productName = '', $type, $url = '', $subid = null)
    {
        if ($email=='' || $productName == '' || $type=='') {
            return;
        }
        $this->inlineTranslation->suspend();
        $senderDetails = $this->getSenderDetails();

        $postObject = new \Magento\Framework\DataObject();
        $post=['productName'=>$productName, 'producturl'=>$url];
        try {
            $postObject->setData($post);
            $error = false;

            $sender = [
                'name' => $this->_escaper->escapeHtml($senderDetails['name']),
                'email' => $this->_escaper->escapeHtml($senderDetails['email']),
            ];

            $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
            $storeManager  = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $storeID       = $storeManager->getStore()->getStoreId(); 
            
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($type) // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => $storeID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($sender)
                ->addTo($email)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            if ($subid) {
                $this->setNotificationStatus($subid);
            }
            $this->inlineTranslation->resume();
            return;
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
            );
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/stock_alert.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info('Something is wrong...'.$e->getMessage());
        }
        return;
    }

    /**
     * @return array
     */
    private function getSenderDetails()
    {
        $sender = $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/notification_email_sender', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $senderDetails = [];
        $senderType = 'ident_general';
        switch ($sender) {
            case 1:
                $senderType = 'ident_sales';
                break;
            case 2:
                $senderType = 'ident_support';
                break;
            case 3:
                $senderType = 'ident_custom1';
                break;
            case 4:
                $senderType = 'ident_custom2';
                break;
            
            default:
                $senderType = 'ident_general';
                break;
        }
        $senderDetails['email']= $this->_scopeConfig->getValue('trans_email/'.$senderType.'/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $senderDetails['name']= $this->_scopeConfig->getValue('trans_email/'.$senderType.'/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $senderDetails;
    }

    /**
     * @return string
     */
    public function getEmailTemplate()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/notify_admin_subscription', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return void
     */
    public function sendNotifications($type = '', $sku = '')
    {
        if ($this->getSubscribers($sku)->count()) {
            $subscribers = $this->getSubscribers($sku);
            foreach ($subscribers as $subscriber) {
                $this->notifySubscriptionStatus($subscriber->getEmail(), $subscriber->getProductName(), $type, $subscriber->getProductUrl(), $subscriber->getId());
            }
        }
    }

    /**
     * @return object
     */
    public function getSubscribers($sku = '')
    {
        if ($sku!='') {
            return $this->stockNotification->getCollection()->addFieldToFilter('status', 'Pending')->addFieldToFilter('notify_status', 1)->addFieldToFilter('product_sku', $sku);
        }
        return $this->stockNotification->getCollection()->addFieldToFilter('status', 'Pending')->addFieldToFilter('notify_status', 1);
    }

    /**
     * @return object
     */
    public function getSubscribersForAdmin()
    {
        return $this->stockNotification;
    }

    /**
     * @return boolean
     */
    private function setNotificationStatus($id = '')
    {
        try {
            $this->stockNotification->setSendDate($this->dateTime->date())->setStatus('Sent')->setId($id)->save();
        } catch (Exception $e) {
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
            );
        }
    }

    /**
     * @return string
     */
    public function getSendTime()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/product_alert/start_time', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getAdminEmail()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/admin_email_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getNotifyCustomer()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/notify_customer_on_subscription', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getStockNotifyCustomer($storeId)
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/email_template_for_subscribed_customer', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    public function getLowQtyStockNotifyCustomer()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/set_notification_email_template', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIsQtyStockAvailablleNotifyCustomer()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/email_template_for_low_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    /**
     * @return boolean
     */
    public function sendReportToAdmin()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/send_email_to_admin_for_subscribed_customer', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return boolean
     */
    public function sendReportToAdminForProduct()
    {
        return  $this->_scopeConfig->getValue('mageprakash_outofstocknotification/admin_stock_subscription/send_email_to_admin_for_product', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
