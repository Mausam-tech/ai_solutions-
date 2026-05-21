<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
if ($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: portfolio-list.php');exit;}
$id=validId($_POST['id']??0);
if (!$id){setFlash('danger','Invalid ID.');header('Location: portfolio-list.php');exit;}
try{
    $stmt=$pdo->prepare('SELECT cover_image_path FROM portfolio_items WHERE id=?');
    $stmt->execute([$id]);$item=$stmt->fetch();
    if($item){deleteUploadedImage($item['cover_image_path']);$pdo->prepare('DELETE FROM portfolio_items WHERE id=?')->execute([$id]);setFlash('success','Project deleted.');}
    else{setFlash('danger','Project not found.');}
}catch(PDOException $e){setFlash('danger','Failed to delete project.');}
header('Location: portfolio-list.php');exit;
