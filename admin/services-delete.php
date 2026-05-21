<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
if ($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: services-list.php');exit;}
$id=validId($_POST['id']??0);
if (!$id){setFlash('danger','Invalid ID.');header('Location: services-list.php');exit;}
try {
    $stmt=$pdo->prepare('SELECT image_path FROM services WHERE id=?');
    $stmt->execute([$id]); $item=$stmt->fetch();
    if ($item){ deleteUploadedImage($item['image_path']); $pdo->prepare('DELETE FROM services WHERE id=?')->execute([$id]); setFlash('success','Service deleted.'); }
    else { setFlash('danger','Service not found.'); }
} catch (PDOException $e){ setFlash('danger','Failed to delete service.'); }
header('Location: services-list.php'); exit;
