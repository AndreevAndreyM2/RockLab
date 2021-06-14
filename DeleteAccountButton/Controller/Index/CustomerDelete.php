<?php
namespace Rlab\DeleteAccountButton\Controller\Index;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface as MassageManager;
use Magento\Framework\Registry;

class CustomerDelete implements HttpGetActionInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var MassageManager
     */
    private MassageManager $massageManager;

    /**
     * CustomerDelete constructor.
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param Session $customerSession
     * @param RedirectFactory $redirectFactory
     * @param MassageManager $massageManager
     * @param Registry $registry
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface,
        Session $customerSession,
        RedirectFactory $redirectFactory,
        MassageManager $massageManager,
        Registry $registry
    )
    {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->massageManager = $massageManager;
        $this->registry = $registry;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute()
    {

        $this->registry->register('isSecureArea', true);
        $customerId = $this->customerSession->getCustomer()->getId();
        $customer = $this->customerRepositoryInterface->getById($customerId);
        $this->customerSession->logout();
        $this->customerRepositoryInterface->delete($customer);

        $this->massageManager->addSuccessMessage(('Account was successfully deleted.'));

        $redirect = $this->redirectFactory->create();
        $redirect->setPath('cms/index/index/');

        return $redirect;
    }
}
