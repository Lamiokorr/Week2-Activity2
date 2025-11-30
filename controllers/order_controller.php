<?php

require_once '../classes/order_class.php';

/**
 * Create a new order
 * @param int $customer_id
 * @param string $invoice_no
 * @param string $order_date
 * @param string $order_status
 * @return int|false
 */
function create_order_ctr($customer_id, $invoice_no, $order_date, $order_status = 'Pending')
{
    $order = new Order();
    return $order->createOrder($customer_id, $invoice_no, $order_date, $order_status);
}

/**
 * Add order details (products)
 * @param int $order_id
 * @param int $product_id
 * @param int $qty
 * @return bool
 */
function add_order_detail_ctr($order_id, $product_id, $qty)
{
    $order = new Order();
    return $order->add_order_detail($order_id, $product_id, $qty);
}

/**
 * Record a payment
 * @param float $amount
 * @param int $customer_id
 * @param int $order_id
 * @param string $currency
 * @param string $payment_date
 * @param string $payment_method
 * @param string|null $transaction_ref
 * @param string|null $authorization_code
 * @param string|null $payment_channel
 * @return int|false
 */
function record_payment_ctr($amount, $customer_id, $order_id, $currency, $payment_date, $payment_method = 'direct', $transaction_ref = null, $authorization_code = null, $payment_channel = null)
{
    $order = new Order();
    return $order->record_payment(
        $amount, 
        $customer_id, 
        $order_id, 
        $currency, 
        $payment_date, 
        $payment_method, 
        $transaction_ref, 
        $authorization_code, 
        $payment_channel
    );
}

/**
 * Get all orders for a user
 * @param int $customer_id
 * @return array|false
 */
function get_user_orders_ctr($customer_id)
{
    $order = new Order();
    return $order->get_user_orders($customer_id);
}

/**
 * Get a specific order’s details
 * @param int $order_id
 * @param int $customer_id
 * @return array|false
 */
function get_order_details_ctr($order_id, $customer_id)
{
    $order = new Order();
    return $order->get_order_details($order_id, $customer_id);
}

/**
 * Get products inside an order
 * @param int $order_id
 * @return array|false
 */
function get_order_products_ctr($order_id)
{
    $order = new Order();
    return $order->get_order_products($order_id);
}

/**
 * Update order status
 * @param int $order_id
 * @param string $order_status
 * @return bool
 */
function update_order_status_ctr($order_id, $order_status)
{
    $order = new Order();
    return $order->update_order_status($order_id, $order_status);
}

?>
