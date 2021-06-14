<?php

namespace Rlab\OrderExport\Sender;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;

class SendEmail
{

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * SendEmail constructor.
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $export
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendEmail($export)
    {
        $customerSupportEmail = $this->scopeConfig->getValue('order_export_email/general/mail');

        $data = ['myvar' => $export];
        $postObject = new DataObject();
        $postObject->setData($data);

        $transport = $this->transportBuilder
            ->setTemplateIdentifier('send_email_email_template')
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => Store::DEFAULT_STORE_ID])
            ->setTemplateVars(['data' => $postObject])
            ->setFrom(['name' => 'Robot', 'email' => 'leonid.leonidovich.96@gmail.com'])
            ->addTo($customerSupportEmail)
            ->getTransport();
        $transport->sendMessage();

    }

}
