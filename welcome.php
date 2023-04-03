<?php
// Start session before any output is sent to the browser
ob_start();
session_start();

// Check if user is logged in
if (isset($_SESSION['email'])) {

    // Read user data from file
    $file = 'users.txt';
    $user_data = array();
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $data_arr = explode("\n", $data);
        foreach ($data_arr as $user_str) {
            if (!empty($user_str)) {
                $user = json_decode($user_str, true);
                if ($user['email'] == $_SESSION['email']) {
                    $user_data = $user;
                    break;
                }
            }
        }
    }

    // Display user data
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<title>Welcome</title>';
    echo '<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">';
    echo '</head>';
    echo '<body>';
    echo '<div class="container">';
    echo '<h1 class="text-center my-5">Welcome ' . $user_data['name'] . '</h1>';
    echo '<p><strong>Name:</strong> ' . $user_data['name'] . '</p>';
    echo '<p><strong>Email:</strong> ' . $user_data['email'] . '</p>';
    if (isset($user_data['room_no'])) {
        echo '<p><strong>Room No:</strong> ' . $user_data['room_no'] . '</p>';
    }
    if (isset($user_data['ext'])) {
        echo '<p><strong>Ext:</strong> ' . $user_data['ext'] . '</p>';
    }
    if (isset($user_data['profile_picture'])) {
        echo '<p><strong>Profile Picture:</strong> <br><img src="' . $user_data['profile_picture'] . '" style="max-width: 300px;"></p>';
    }

    // Logout button
    echo '<a href="login.php" class="btn btn-primary">Logout</a>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
} else {
    // Redirect to login page
    header('Location: login.php');
    exit();
}

ob_end_flush();
