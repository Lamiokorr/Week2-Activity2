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
    return isset($_SESSION['id']); // or use another session key you set at login
}

/**
 * Check if the logged-in user is an admin
 * 
 * @return bool
 */
function isAdmin() {
    if (isLoggedIn() && isset($_SESSION['user_role'])) {
        // assuming role "2" means admin (you can adjust this)
        return $_SESSION['user_role'] == 2;
    }
    return false;
}


//function to get user ID


//function to check for role (admin, customer, etc)



?>