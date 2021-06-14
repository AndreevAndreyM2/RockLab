<?php
namespace Rlab\DeleteAccountButton\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Rlab\DeleteAccountButton\Model\Sender;
use Magento\Framework\Message\ManagerInterface as MassageManager;
use Magento\Framework\Controller\Result\RedirectFactory;

class Delete implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var Sender
     */
    protected $sender;

    /**
     * @var MassageManager
     */
    protected MassageManager $massageManager;

    /**
     * @var RedirectFactory
     */
    protected RedirectFactory $redirectFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Sender $sender
     * @param MassageManager $massageManager
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Sender $sender,
        MassageManager $massageManager,
        RedirectFactory $redirectFactory
    )
    {
        $this->pageFactory = $pageFactory;
        $this->sender = $sender;
        $this->massageManager = $massageManager;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @return ResultInterface
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $this->sender->sendEmail();

        $this->massageManager->addSuccessMessage(('The letter was sent successfully.'));
        return $this->redirectFactory->create()->setPath(
            'customer/account/edit/');
    }
}
