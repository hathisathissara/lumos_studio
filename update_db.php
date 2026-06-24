<?php
require_once 'config/config.php';

try {
    // Check if slug exists
    $stmt = $conn->query("SHOW COLUMNS FROM weddings LIKE 'slug'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("ALTER TABLE weddings ADD COLUMN slug VARCHAR(255) DEFAULT NULL");
        echo "Added slug column.\n";
    }

    // Check if description exists
    $stmt = $conn->query("SHOW COLUMNS FROM weddings LIKE 'description'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("ALTER TABLE weddings ADD COLUMN description TEXT DEFAULT NULL");
        echo "Added description column.\n";
    }

    // Generate slugs for existing weddings
    $stmt = $conn->query("SELECT id, title FROM weddings WHERE slug IS NULL OR slug = ''");
    $weddings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    function createSlug($string) {
        $slug = preg_replace('/[^a-zA-Z0-9-]/', '-', strtolower(trim($string)));
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    $updateStmt = $conn->prepare("UPDATE weddings SET slug = ? WHERE id = ?");
    foreach ($weddings as $w) {
        $slug = createSlug($w['title']);
        
        // Ensure unique slug
        $check = $conn->prepare("SELECT COUNT(*) FROM weddings WHERE slug = ? AND id != ?");
        $check->execute([$slug, $w['id']]);
        if ($check->fetchColumn() > 0) {
            $slug .= '-' . $w['id'];
        }
        
        $updateStmt->execute([$slug, $w['id']]);
        echo "Updated slug for ID " . $w['id'] . " to $slug\n";
    }

    echo "Done.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
