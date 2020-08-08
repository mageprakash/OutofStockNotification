<?php
/**
 * @category MagePrakash Out Of Stock Notification
 * @package MagePrakash_OutOfStockNotification
 * @copyright Copyright (c) 2018 MagePrakash
 * @author MagePrakash Team <support@mageprakash.com>
 */
namespace MagePrakash\OutofStockNotification\Controller\Adminhtml\CustomerList;
 
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var resultPageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagePrakash_OutofStockNotification::notificationlist');
        $resultPage->addBreadcrumb(__('OutofStock Subscription List'), __('OutofStock Subscription List'));
        $resultPage->addBreadcrumb(__('Manage OutofStock Notification List'), __('Manage OutofStock Notification List'));
        $resultPage->getConfig()->getTitle()->prepend(__('OutofStock Subscription List'));

        return $resultPage;
    }

    /**
     * _isAllowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MagePrakash_OutofStockNotification::notificationlist');
    }
}
