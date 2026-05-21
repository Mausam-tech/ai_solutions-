<?php
// ============================================================
// UPLOAD HELPER
// Requires UPLOAD_PATH to be defined (via db.php).
// ============================================================

/**
 * Handle an image file upload from a form file input.
 *
 * @param string      $fieldName  The $_FILES key (form input name).
 * @param string      $subFolder  Sub-directory inside uploads/ (e.g. 'gallery').
 * @param string|null $oldPath    Existing stored path to delete on success (optional).
 *
 * @return string|false  Relative path stored in DB (e.g. 'gallery/img_xxx.jpg'), or false on failure/no upload.
 */
function handleImageUpload(string $fieldName, string $subFolder, ?string $oldPath = null): string|false
{
    // No file submitted or user left field empty
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return false;
    }

    $file = $_FILES[$fieldName];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Size limit: 5 MB
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }

    // Validate MIME type using finfo (not just extension — more secure)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    if (!array_key_exists($mimeType, $allowedMimes)) {
        return false;
    }

    $ext      = $allowedMimes[$mimeType];
    $filename = 'img_' . uniqid('', true) . '.' . $ext;
    $destDir  = UPLOAD_PATH . $subFolder . DIRECTORY_SEPARATOR;

    // Create directory if it doesn't exist
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $destPath = $destDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return false;
    }

    // Delete old file from disk (only after successful new upload)
    if (!empty($oldPath)) {
        $oldFullPath = UPLOAD_PATH . $oldPath;
        if (file_exists($oldFullPath)) {
            @unlink($oldFullPath);
        }
    }

    // Return relative path: subfolder/filename
    return $subFolder . '/' . $filename;
}

/**
 * Delete an uploaded image from disk.
 * @param string $path  Stored path (e.g. 'gallery/img_xxx.jpg')
 */
function deleteUploadedImage(string $path): void
{
    if (!empty($path)) {
        $fullPath = UPLOAD_PATH . $path;
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}

/**
 * Return an error message string for upload validation failures
 * (used in admin forms to show the user what went wrong).
 */
function uploadErrorMessage(string $fieldName): string
{
    if (!isset($_FILES[$fieldName])) {
        return '';
    }
    $err  = $_FILES[$fieldName]['error'];
    $size = $_FILES[$fieldName]['size'] ?? 0;

    if ($err === UPLOAD_ERR_NO_FILE)  return '';
    if ($err !== UPLOAD_ERR_OK)       return 'File upload failed (error code ' . $err . ').';
    if ($size > 5 * 1024 * 1024)      return 'File exceeds the 5 MB size limit.';

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($_FILES[$fieldName]['tmp_name']);
    $ok    = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $ok)) return 'Only JPG, PNG, GIF, and WebP images are allowed.';

    return '';
}
