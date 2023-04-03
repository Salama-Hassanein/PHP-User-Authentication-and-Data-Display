<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_data = array(
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'room_no' => $_POST['room_no'],
        'ext' => $_POST['ext']
    );

    if (!is_dir('uploads')) {
        mkdir('uploads');
    }

    // Check if profile picture was uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $filename = $_FILES['profile_picture']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {
            $destination = 'uploads/' . $filename;
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination);
            $user_data['profile_picture'] = $destination;
        } else {
            echo 'Invalid file format';
            exit();
        }
    }

    // Validate password
    if (strlen($_POST['password']) != 8) {
        echo 'Password must be exactly 8 characters long';
        exit();
    }

    if (!ctype_lower($_POST['password']) && !ctype_digit($_POST['password']) && !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['password'])) {
        echo 'Password can only contain lowercase letters, digits, and underscores';
        exit();
    }

    if (preg_match('/[A-Z]/', $_POST['password'])) {
        echo 'Password cannot contain capital letters';
        exit();
    }

    if ($_POST['password'] !== $_POST['confirm_password']) {
        echo 'Passwords do not match';
        exit();
    }

    // Store user data in file
    $file = 'users.txt';

    // Check if file exists, create it if not
    if (!file_exists($file)) {
        $handle = fopen($file, 'w') or die("Cannot create file: $file");
        fclose($handle);

        // Set file permissions to allow write access
        chmod($file, 0666);
    }

    $current_data = file_get_contents($file);
    $current_data .= json_encode($user_data) . "\n";
    file_put_contents($file, $current_data);

    // Redirect to login page
    header('Location: login.php');
    exit();
} else {
    // Display the registration form
?>
    <form method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <label>Room No:</label>
        <select name="room_no" required>
            <option value="Application1">Application1</option>
            <option value="Application2">Application2</option>
            <option value="cloud">cloud</option>
        </select><br>

        <label>Ext:</label>
        <input type="text" name="ext" required><br>

        <label>Profile Picture:</label>
        <input type="file" name="profile_picture"><br>

        <input type="submit" name="submit" value="Submit">
        <input type="reset" name="reset" value="Reset">
    </form>
<?php
}
?>