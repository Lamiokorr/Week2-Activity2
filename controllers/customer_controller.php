<?php

require_once '../classes/customer_class.php';


function register_user_ctr($customer_name, $email, $password, $country, $city, $phone_number, $role)
{
    $customer = new Customer();
    $customer_id = $customer->createCustomer($customer_name, $email, $password, $country, $city, $phone_number, $role);
    if ($customer_id) {
        return $customer_id;
    }
    return false;
}

function get_user_by_email_ctr($email)
{
    $customer = new Customer();
    return $customer->getCustomerByEmail($email);
}

function login_customer_ctr($email, $password) {
    $customer = new Customer();
    return $customer -> loginCustomer($email, $password);
}

function delete_customer_ctr($customer_id) {
    $customer = new Customer();
    return $customer->deleteCustomer($customer_id);
}

function update_customer_ctr($customer_id, $customer_name, $email, $password, $country, $city, $phone_number) {
    $customer = new Customer($customer_id);
    return $customer->updateCustomer($customer_id, $customer_name, $email, $password, $country, $city, $phone_number);
}

function get_all_customers_ctr() {
    $customer = new Customer();
    return $customer->getAllCustomers();
}
?>