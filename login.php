<?php
session_start();

$userFile = "users.json";
$userData = json_decode(file_get_contents($userFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $userData['username'] && $password === $userData['password']) {
        $_SESSION['logged_in'] = true;
        header("Location: announcements.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title>
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

body h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #2E7D32; /* Green */
  font-weight: 800;
  font-size: 2rem;
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
<h2>Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
    <a href="change_password.php">🔗</a>
</form>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>