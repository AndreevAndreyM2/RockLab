<?php

namespace Rlab\OrderExport\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Filesystem;
use Rlab\OrderExport\Model\OrderExportToSDF;


class GenerateSDF implements ObserverInterface
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var DirectoryList
     */
    protected $dir;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var OrderExportToSDF
     */
    protected $exportToSDF;

    /**
     * GenerateSDF constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param DirectoryList $dir
     * @param Filesystem $filesystem
     * @param OrderExportToSDF $exportToSDF
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        DirectoryList $dir,
        Filesystem $filesystem,
        OrderExportToSDF $exportToSDF
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->dir = $dir;
        $this->filesystem = $filesystem;
        $this->exportToSDF = $exportToSDF;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @throws MailException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $this->exportToSDF->orderExportToSDF($order);
    }

}



