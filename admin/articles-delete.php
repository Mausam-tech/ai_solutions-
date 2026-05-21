<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: articles-list.php'); exit; }
$id = validId($_POST['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid ID.'); header('Location: articles-list.php'); exit; }
try {
    $stmt = $pdo->prepare('SELECT thumbnail_path FROM articles WHERE id=?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if ($item) {
        deleteUploadedImage($item['thumbnail_path']);
        $pdo->prepare('DELETE FROM articles WHERE id=?')->execute([$id]);
        setFlash('success','Article deleted successfully.');
    } else { setFlash('danger','Article not found.'); }
} catch (PDOException $e) { setFlash('danger','Failed to delete article.'); }
header('Location: articles-list.php'); exit;
