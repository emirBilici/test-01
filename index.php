<?php

// Show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
if (!isset($_SESSION)) {
    session_start();
}

// Defined variables
const PROJECT_NAME = "ekşi sözlük";
const REDIRECT_HOMEPAGE = "/";
//const ADMIN_DATA = [
//    'email' => 'admin@admin.com',
//    'password' => 'Aa@123123'
//];

// Require required files
require __DIR__ . '/database.php';
require __DIR__ . '/model.php';
require __DIR__ . '/controller.php';
require __DIR__ . '/route.php';
require __DIR__ . '/utils.php';

// Homepage Routing
Route::run('/', 'home@noSession');

// Login Routing
Route::run('/login', 'auth@login');
Route::run('/signup', 'auth@signup');

// Profile & Post Routing
Route::run('/profile/{user}', 'profile@index');
Route::run('/reset-password', 'resetpassword@index');
Route::run('/change-password', 'changepassword@index');
Route::run('/change-password', 'changepassword@post', 'post');
Route::run('/change-username', 'changeusername@index');
Route::run('/change-username', 'changeusername@post', 'post');
Route::run('/delete-myaccount', 'deleteaccount@index');
Route::run('/new/post', 'post@newPost');
Route::run('/new/post', 'post@createNewPost', 'post');
Route::run('/entry/{postId}', 'post@getPost');
Route::run('/delete/entry', 'post@deletePost', 'post');
Route::run('/vote', 'vote@post', 'post');
Route::run('/update/voteSession', 'vote@updateSession', 'post');
Route::run('/reportEntry', 'post@reportEntry');
Route::run('/report', 'post@report');

// Search
Route::run('/search/{url}', 'search@index');

/**
 * Authentication
 */
Route::run('/auth/signup', 'auth/signup@index', 'post');
Route::run('/auth/login', 'auth/login@index', 'post');
Route::run('/logout', 'auth@logout');
Route::run('/change_user_settings', 'auth@changeSettings', 'post');

/**
 * Admin
 */
Route::run('/admin', 'admin@login');
Route::run('/admin', 'admin@checkAdmin', 'post');
Route::run('/admin/logout', function() {
    unset($_SESSION['admin_session']);
    site_url();
});
Route::run('/delete/user/{id}', 'admin@deleteUser');
Route::run('/create/tag', 'admin@createTag', 'post');
Route::run('/delete/tag/{id}', 'admin@deleteTag');
Route::run('/delete/report/{id}', 'admin@deleteReport');
Route::run('/change_default_tag', 'admin@changeDefaultTag', 'post');

/**
 * Tag
 */
Route::run('/tag/{user}', 'tag@index');
Route::run('/tag', 'tag@post', 'post');
Route::run('/tags', 'tag@get');

/**
 * Notifications
 */
Route::run('/notify/report', 'notifications@report');
Route::run('/notify/report', 'notifications@reportPost', 'post');
Route::run('/notifications', 'notifications@getNotifications');
Route::run('/delete/notification', 'notifications@delete', 'post');