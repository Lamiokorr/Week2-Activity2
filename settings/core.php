<?php
// Settings/core.php
session_start();

// For header redirection
ob_start();

/**
 * Check if a user is logged in
 * 
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['customer_id']); // or use another session key you set at login
}

/**
 * Check if the logged-in user is an admin
 * 
 * @return bool
 */
function isAdmin() {
    if (isLoggedIn() && isset($_SESSION['user_role'])) {
                return $_SESSION['user_role'] == 1;
    }
    return false;
}


//function to get user ID


//function to check for role (admin, customer, etc)



?>