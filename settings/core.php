<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For header redirection
ob_start();

/**
 * Check if a user is logged in
 * 
 * @return bool
 */
function isLoggedIn() {
  // Check if user_id exists in session and is not empty
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

/**
 * Check if the logged-in user is an admin
 * 
 * @return bool
 */
function isAdmin() {
    if (isLoggedIn()) {
        return $_SESSION['role'] == 1;
    }
    return false;
}


//function to get user ID
/**
 * Get current user's ID
 * @return int|null - Returns user ID or null if not logged in
 */
function get_user_id() {
    if (isLoggedIn()) {
        return $_SESSION['customer_id'] ?? null;
    }
    return null;
}

//function to check for role (admin, customer, etc)
/**
 * Get current user's role
 * @return int|null - Returns user role or null if not logged in
 */
function get_user_role() {
    if (isLoggedIn()) {
        return $_SESSION['user_role'] ?? null;
    }
    return null;
}

// function to get user name
/**
 * Get current user's name
 * @return string|null - Returns user name or null if not logged in
 */ 
function get_user_name() {
    if (isLoggedIn()) {
        return $_SESSION['customer_name'] ?? null;
    }
    return null;
}

//function to get user email
/**
 * Get current user's email
 * @return string|null - Returns user email or null if not logged in
 */ 
function get_user_email() {
    if (isLoggedIn()) {
        return $_SESSION['customer_email'] ?? null;
    }
    return null;
}

/**
 * Require user to be logged in - redirect if not
 * @param string $redirect_url - URL to redirect to if not logged in (default: login page)
 */
function require_login($redirect_url = '../login/login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect_url");
        exit();
    }
}

/**
 * Require admin privileges - redirect if not admin
 * @param string $redirect_url - URL to redirect to if not admin (default: index page)
 */
function require_admin($redirect_url = '../index.php') {
    if (!isAdmin()) {
        // Log unauthorized access attempt
        error_log("Unauthorized admin access attempt by user ID: " . (get_user_id() ?? 'guest'));
        header("Location: $redirect_url?error=access_denied");
        exit();
    }
}

/**
 * Get user role name as string
 * @return string - Returns 'Admin', 'Customer', or 'Guest'
 */
function get_user_role_name() {
    if (!isLoggedIn()) {
        return 'Guest';
    }
    
    return isAdmin() ? 'Admin' : 'Customer';
}

/**
 * Log user activity (optional function for tracking)
 * @param string $activity - Description of the activity
 */
function log_user_activity($activity) {
    $user_info = is_logged_in() 
        ? "User ID: " . get_user_id() . " (" . get_user_name() . ")"
        : "Guest user";
    
    error_log("User Activity - $user_info - $activity");
}
?>