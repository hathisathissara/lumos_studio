<?php
// Database Connection Details
$servername = "localhost";
$db_username = "root";  // ඔයාගේ DB username එක
$db_password = "";      // ඔයාගේ DB password එක
$dbname = "wedding_portfolio_db";

try {
    // Database එකට සම්බන්ධ වීම
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ඔයාට අවශ්‍ය Username එක සහ Password එක මෙතනින් වෙනස් කරන්න
    $admin_username = "admin";
    $plain_password = "admin123"; // මේක තමයි ලොග් වෙන්න දෙන පාස්වර්ඩ් එක

    // Password එක Hash කිරීම
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Database එකට ඇතුළත් කිරීමේ Query එක (Prepared Statement)
    $sql = "INSERT INTO admin_users (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(':username', $admin_username);
    $stmt->bindParam(':password', $hashed_password);

    // Query එක Run කිරීම
    if ($stmt->execute()) {
        echo "<h3 style='color: green;'>✅ User created successfully!</h3>";
        echo "<p><b>Username:</b> " . htmlspecialchars($admin_username) . "</p>";
        echo "<p><b>Password:</b> " . htmlspecialchars($plain_password) . "</p>";
        echo "<p style='color: red;'><b>වැදගත්:</b> දැන් මේ create_user.php ෆයිල් එක ඔයාගේ ෆෝල්ඩරයෙන් මකා දමන්න (Delete කරන්න). නැතිනම් වෙනත් කෙනෙකුටත් අලුතින් Admin ලා හැදිය හැක!</p>";
    }

} catch(PDOException $e) {
    // Username එක දැනටමත් තියෙනවා නම් Error එකක් පෙන්නනවා
    if ($e->getCode() == 23000) {
        echo "<h3 style='color: orange;'>⚠️ මේ Username එක දැනටමත් Database එකේ තියෙනවා!</h3>";
    } else {
        echo "<h3 style='color: red;'>❌ Error: " . $e->getMessage() . "</h3>";
    }
}

$conn = null;
?>