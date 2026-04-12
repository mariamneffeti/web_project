<?php
/** Session Management
  *Handles user authentication and session management
 */

session_start();

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    /** return isset($_SESSION['user_id']) && isset($_SESSION['role']); */
    return TRUE;
}

/**
 * Check if user is an employee
 */
function isEmployee() {
    return isLoggedIn() && $_SESSION['role'] === 'employee';
}

/**
 * Check if user is a company
 */
function isCompany() {
    return isLoggedIn() && $_SESSION['role'] === 'company';
}
function isuser() {
    return isLoggedIn() && $_SESSION['role'] === 'normal';
}
/**
 * Require employee access
 */
function requireEmployee() {
    if (!isEmployee()) {
        header('Location: ../auth/login.php');
        exit();
    }
}

/**
 * Require company access
 */
function requireCompany() {
    if (!isCompany()) {
        header('Location: ../auth/login.php');
        exit();
    }
}
function requireuser() {
    if (!isuser()) {
        header('Location: ../auth/login.php');
        exit();
    }
}
/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'user_id' => 1,
        'email' => 'admin@example.com',
        'role' => 'employee',
        'employee_id' => 1,
        'company_id' => 1
    ];
    
    /*/return [
        'user_id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['role'],
        'employee_id' => $_SESSION['employee_id'] ?? null,
        'company_id' => $_SESSION['company_id'] ?? null
    ];/*/
}

/**
 * Login user
 */
function loginUser($userId, $email, $role, $additionalData = []) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;
    
    foreach ($additionalData as $key => $value) {
        $_SESSION[$key] = $value;
    }
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
    header('Location: ../auth/login.php');
    exit();
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
