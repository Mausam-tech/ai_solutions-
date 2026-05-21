<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
if ($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: testimonials-list.php');exit;}
$id=validId($_POST['id']??0);
if (!$id){setFlash('danger','Invalid ID.');header('Location: testimonials-list.php');exit;}
try{
    $stmt=$pdo->prepare('SELECT avatar_path FROM testimonials WHERE id=?');$stmt->execute([$id]);$item=$stmt->fetch();
    if($item){deleteUploadedImage($item['avatar_path']);$pdo->prepare('DELETE FROM testimonials WHERE id=?')->execute([$id]);setFlash('success','Testimonial deleted.');}
    else{setFlash('danger','Not found.');}
}catch(PDOException $e){setFlash('danger','Failed to delete.');}
header('Location: testimonials-list.php');exit;
