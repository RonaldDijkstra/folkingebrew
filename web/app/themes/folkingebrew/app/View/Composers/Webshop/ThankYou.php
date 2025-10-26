<?php

namespace App\View\Composers\Webshop;

use Roots\Acorn\View\Composer;

class ThankYou extends Composer
{
    protected static $views = [
        'webshop.thankyou',
    ];

    public function with()
    {
        return [
            'order' => $this->getOrder(),
            'orderNumber' => $this->getOrderNumber(),
            'orderDate' => $this->getOrderDate(),
            'orderTotal' => $this->getOrderTotal(),
            'paymentMethod' => $this->getPaymentMethod(),
            'orderItems' => $this->getOrderItems(),
            'billingAddress' => $this->getBillingAddress(),
            'shippingAddress' => $this->getShippingAddress(),
        ];
    }

    /**
     * Get the order object
     *
     * @return \WC_Order|null
     */
    protected function getOrder()
    {
        return $this->data->get('order');
    }

    /**
     * Get the order number
     *
     * @return string
     */
    protected function getOrderNumber()
    {
        $order = $this->getOrder();
        return $order ? $order->get_order_number() : '';
    }

    /**
     * Get the order date
     *
     * @return string
     */
    protected function getOrderDate()
    {
        $order = $this->getOrder();
        if (!$order) {
            return '';
        }

        return wc_format_datetime($order->get_date_created());
    }

    /**
     * Get the order total
     *
     * @return string
     */
    protected function getOrderTotal()
    {
        $order = $this->getOrder();
        return $order ? $order->get_formatted_order_total() : '';
    }

    /**
     * Get the payment method title
     *
     * @return string
     */
    protected function getPaymentMethod()
    {
        $order = $this->getOrder();
        return $order ? $order->get_payment_method_title() : '';
    }

    /**
     * Get order items formatted for display
     *
     * @return array
     */
    protected function getOrderItems()
    {
        $order = $this->getOrder();
        if (!$order) {
            return [];
        }

        $items = [];
        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            error_log(print_r($product, true));
            $items[] = [
                'name' => $item->get_name(),
                'quantity' => $item->get_quantity(),
                'total' => $order->get_formatted_line_subtotal($item),
                'product' => $product,
            ];
        }

        return $items;
    }

    /**
     * Get formatted billing address
     *
     * @return string
     */
    protected function getBillingAddress()
    {
        $order = $this->getOrder();
        if (!$order) {
            return '';
        }

        return $order->get_formatted_billing_address();
    }

    /**
     * Get formatted shipping address
     *
     * @return string|null
     */
    protected function getShippingAddress()
    {
        $order = $this->getOrder();
        if (!$order || !$order->needs_shipping_address()) {
            return null;
        }

        return $order->get_formatted_shipping_address();
    }
}

