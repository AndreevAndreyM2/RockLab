<?php

namespace Rlab\OrderExport\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Sales\Model\OrderRepository;
use Rlab\OrderExport\Model\OrderExportToSDF;

class Export extends Action
{

    /**
     * @var Filesystem
     */
    protected $filesystem;


    /**
     * @var OrderRepository
     */
    protected $orderRepository;


    /**
     * @var RequestInterface
     */
    protected $request;


    /**
     * @var DirectoryList
     */
    protected $dir;


    /**
     * @var OrderExportToSDF
     */
    protected $exportToSDF;

    /**
     * Export constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param OrderRepository $orderRepository
     * @param DirectoryList $dir
     * @param RequestInterface $request
     * @param OrderExportToSDF $exportToSDF
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        OrderRepository $orderRepository,
        DirectoryList $dir,
        RequestInterface $request,
        OrderExportToSDF $exportToSDF
    )
    {
        $this->filesystem = $filesystem;
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        $this->dir = $dir;
        $this->exportToSDF = $exportToSDF;
        parent::__construct($context);
    }


    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws LocalizedException
     * @throws MailException
     */
    public function execute()
    {
        $orderId = $this->request->getParam('order_id');

        try {
            $order = $this->orderRepository->get($orderId);
        } catch (InputException | NoSuchEntityException $e) {
            $this->exportToSDF->senderEmail->sendEmail($e->getMessage());
        }

        if ($order->getState() == 'processing') {
            $this->exportToSDF->orderExportToSDF($order);
            $this->messageManager->addSuccessMessage(('The order was successfully generated.'));
        } else {
            $this->messageManager->addErrorMessage(('Order is not in processing status , please try again'));
        }

        return $this->resultRedirectFactory->create()->setPath(
            'sales/order/view',
            [
                'order_id' => $order->getEntityId()
            ]
        );

    }

}
