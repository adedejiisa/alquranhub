<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$jsonFile = "data.json";
$uploadDir = "uploads/";

// Load existing announcements
$announcements = [];
if (file_exists($jsonFile)) {
    $announcements = json_decode(file_get_contents($jsonFile), true);
}

// Handle new post
if (isset($_POST['add'])) {
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }

    $newAnnouncement = [
        "title" => $_POST['title'],
        "description" => $_POST['description'],
        "image" => $imageName ?? "",
        "date" => date("d-m-Y")
    ];
    $announcements[] = $newAnnouncement;
    file_put_contents($jsonFile, json_encode($announcements, JSON_PRETTY_PRINT));
    header("Location: announcements.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    unset($announcements[$index]);
    $announcements = array_values($announcements); // reindex
    file_put_contents($jsonFile, json_encode($announcements, JSON_PRETTY_PRINT));
    header("Location: announcements.php");
    exit;
}

// Handle edit
if (isset($_POST['edit'])) {
    $index = $_POST['index'];

    $imageName = $announcements[$index]['image']; // keep old image if not replaced
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }

    $announcements[$index] = [
        "title" => $_POST['title'],
        "description" => $_POST['description'],
        "image" => $imageName,
        "date" => date("d-m-Y")
    ];
    file_put_contents($jsonFile, json_encode($announcements, JSON_PRETTY_PRINT));
    header("Location: announcements.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
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

button, a {
  background-color: #F57C00;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
  text-decoration:none;
}

button:hover {
  background-color: #e66a00;
}
    </style>
    <title>Announcements</title>
    
</head>
<body>
<h2>Post Announcement</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <input type="file" name="image" accept="image/*" required><br><br>
    <button type="submit" name="add">Add Announcement</button>
</form>

<hr>
<h2>Existing Announcements</h2>
<?php foreach ($announcements as $index => $a): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <h3><?= htmlspecialchars($a['title']) ?></h3>
        <p><?= htmlspecialchars($a['description']) ?></p>
        <?php if ($a['image']): ?>
            <img src="<?= $uploadDir . htmlspecialchars($a['image']) ?>" width="100"><br>
        <?php endif; ?>
        <small>Date: <?= $a['date'] ?></small><br><br>

        <!-- Edit Form -->
        <form method="POST" enctype="multipart/form-data" style="margin-top:10px;">
            <input type="hidden" name="index" value="<?= $index ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($a['title']) ?>" required><br>
            <textarea name="description" required><?= htmlspecialchars($a['description']) ?></textarea><br>
            <input type="file" name="image" accept="image/*"><br>
            <button type="submit" name="edit">Save Changes</button>
        </form>

        <!-- Delete Button -->
        <a href="announcements.php?delete=<?= $index ?>" 
           onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
    </div>
<?php endforeach; ?>
</body>
</html>