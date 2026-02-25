<?php
require_once __DIR__ . '/../google-config.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/utils.php';

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['error'])) {
            throw new Exception("Error fetching access token: " . $token['error_description']);
        }
        
        $client->setAccessToken($token['access_token']);

        // Get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $google_id = $google_account_info->id;
        $image = $google_account_info->picture;

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // User exists, login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
        } else {
            // New user, register
            $id = generate_id();
            $role = 'USER'; // Default role for Google Signup

            $stmt = $pdo->prepare("INSERT INTO users (id, name, email, emailVerified, image, role) VALUES (?, ?, ?, NOW(), ?, ?)");
            $stmt->execute([$id, $name, $email, $image, $role]);

            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_name'] = $name;
        }

        // Redirect based on role
        if ($_SESSION['user_role'] === 'SELLER') {
            redirect(BASE_URL . 'seller/dashboard.php');
        } else {
            redirect(BASE_URL . 'index.php');
        }

    } catch (Exception $e) {
        die("Google Login Failed: " . $e->getMessage());
    }
} else {
    redirect(BASE_URL . 'auth/login.php');
}
