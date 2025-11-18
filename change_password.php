<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$userFile = "users.json";
$userData = json_decode(file_get_contents($userFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($current !== $userData['password']) {
        $error = "Current password is incorrect!";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match!";
    } else {
        // Update password
        $userData['password'] = $new;
        file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
        $success = "Password changed successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Change Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    form {
  max-width: 500px;
  margin: 40px auto;
  padding: 30px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

form h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #2E7D32; /* Green */
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #6D4C41; /* Brown */
}

input,
textarea,
select {
  width: 100%;
  padding: 12px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 16px;
  box-sizing: border-box;
}

input:focus,
textarea:focus,
select:focus {
  border-color: #F57C00; /* Orange */
  outline: none;
}

button {
  background-color: #F57C00;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #e66a00;
}
</style>
</head>
<body>
<h2>Change Password</h2>
<form method="POST">
    <input type="password" name="current_password" placeholder="Current Password" required><br><br>
    <input type="password" name="new_password" placeholder="New Password" required><br><br>
    <input type="password" name="confirm_password" placeholder="Confirm New Password" required><br><br>
    <button type="submit">Change Password</button>
</form>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
</body>
</html>