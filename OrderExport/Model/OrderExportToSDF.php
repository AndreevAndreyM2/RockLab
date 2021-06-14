<?php

namespace Rlab\OrderExport\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Filesystem;
use Rlab\OrderExport\Sender\SendEmail;

class OrderExportToSDF
{

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var DirectoryList
     */
    protected $dir;

    /**
     * @var SendEmail
     */
    public $senderEmail;

    /**
     * OrderExportToSDF constructor.
     * @param Filesystem $filesystem
     * @param DirectoryList $dir
     * @param SendEmail $senderEmail
     */
    public function __construct(
        Filesystem $filesystem,
        DirectoryList $dir,
        SendEmail $senderEmail
    )
    {
        $this->filesystem = $filesystem;
        $this->dir = $dir;
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param $order
     * @throws LocalizedException
     * @throws MailException
     */
    public function orderExportToSDF($order)
    {
        try {
            $directoryWriteFolder = $this->filesystem->getDirectoryWrite($this->dir::VAR_DIR);
        } catch (FileSystemException $e) {
            $this->senderEmail->sendEmail($e->getMessage());
        }

        $pathForWrite = '/order_export/' . $order->getIncrementId() . '.sdf ';

        $data = $order->getCustomerLastname() . ';'
            . $order->getCustomerFirstname() . ';'
            . $order->getShippingAddress()->getPostcode() . ';'
            . $order->getShippingAddress()->getCity() . ';'
            . $order->getShippingAddress()->getData("street") . ';'
            . $order->getShippingAddress()->getEmail() . ';'
            . ';'
            . $order->getCreatedAt() . ';'
            . ';' //Salutation
            . $order->getShippingAddress()->getTelephone() . ';'
            . ';' //Mobile phone
            . ';'
            . ';'
            . ';'
            . ';'
            . ';'
            . ';'
            . $order->getPayment()->getMethodInstance()->getTitle() . ';'
            . ';'
            . $order->getShippingAddress()->getCountryId() . ';'
            . ';'
            . ';'
            . ';'
            . ';'
            . ';' // Payment
            . ';'
            . $order->getIncrementId() . ';'
            . ';'
            . $order->getVatId() . ';'
        ;

        if ($order->getState() == 'processing') {
            try {
                $directoryWriteFolder->writeFile($pathForWrite, $data);
            } catch (Exception $e) {
                $this->senderEmail->sendEmail($e->getMessage());
            }
        }

    }

}
