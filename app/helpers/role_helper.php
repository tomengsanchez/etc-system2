<?php
/**
 * Check if the user has a specific role.
 *
 * @param string $role The role to check for (e.g., 'admin', 'teacher').
 * @return boolean True if the user has the role, false otherwise.
 */
function hasRole($role) {
    if (isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) == strtolower($role)) {
        return true;
    }
    return false;
}

/**
 * Check if the current user is an admin.
 *
 * @return boolean
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Check if the current user is a teacher.
 *
 * @return boolean
 */
function isTeacher() {
    return hasRole('teacher');
}

/**
 * Check if the current user is a student.
 *
 * @return boolean
 */
function isStudent() {
    return hasRole('student');
}
