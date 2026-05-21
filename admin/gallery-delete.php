<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: gallery-list.php'); exit; }

$id = validId($_POST['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid ID.'); header('Location: gallery-list.php'); exit; }

try {
    $stmt = $pdo->prepare('SELECT image_path FROM gallery_images WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if ($item) {
        deleteUploadedImage($item['image_path']);
        $pdo->prepare('DELETE FROM gallery_images WHERE id = ?')->execute([$id]);
        setFlash('success','Gallery image deleted successfully.');
    } else {
        setFlash('danger','Image not found.');
    }
} catch (PDOException $e) {
    setFlash('danger','Failed to delete image.');
}

header('Location: gallery-list.php');
exit;
