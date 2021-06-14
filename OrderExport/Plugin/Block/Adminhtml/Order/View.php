<?php

namespace Rlab\OrderExport\Plugin\Block\Adminhtml\Order;

class View
{
    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View $view
     */
    public function beforeSetLayout(\Magento\Sales\Block\Adminhtml\Order\View $view)
    {
        $message = 'Are you sure you want to do this?';
        $url = $view->getUrl('rlab/order/export') . $view->getOrderId();

        $view->addButton(
            'order_myaction',
            [
                'label' => __('Generate SDF'),
                'class' => 'myclass',
                'onclick' => "confirmSetLocation('{$message}', '{$url}')"
            ]
        );

    }
}
