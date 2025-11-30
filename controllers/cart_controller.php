<?php

require_once '../classes/cart_class.php';

function add_to_cart_ctr($p_id, $ip_add, $c_id = null, $qty = 1)
{
    $cart = new Cart($c_id);
    return $cart->add_to_cart($p_id, $ip_add, $c_id, $qty);
}

function update_cart_item_ctr($p_id, $qty, $ip_add, $c_id = null)
{
    $cart = new Cart($c_id);
    return $cart->update_quantity($p_id, $qty, $ip_add, $c_id);
}

function remove_from_cart_ctr($p_id, $ip_add, $c_id = null) 
{
    $cart = new Cart($c_id);
    return $cart->remove_from_cart($p_id, $ip_add, $c_id);
}

function empty_cart_ctr($ip_add, $c_id = null) 
{
    $cart = new Cart($c_id);
    return $cart->empty_cart($ip_add, $c_id);
}


function get_user_cart_ctr($ip_add = null, $c_id = null)
{
    $cart = new Cart();
    return $cart->get_user_cart($ip_add, $c_id);
}

function count_cart_items_ctr($ip_add = null, $c_id = null)
{
    $cart = new Cart();
    return $cart->count_cart_items($ip_add, $c_id);
}
