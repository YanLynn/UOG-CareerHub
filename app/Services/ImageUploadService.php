<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageUploadService
{

    public static function uploadBase64Image($base64Image)
    {
        try {
            if (!str_starts_with($base64Image, 'data:image')) {
                throw new \Exception('Invalid image format.');
            }
            $filePath = self::convertBase64ToFile($base64Image);
            // Upload to Cloudinary
            $uploadedImage = Cloudinary::upload($filePath)->getSecurePath();
            return $uploadedImage;
        } catch (\Exception $e) {
            throw new \Exception("Cloudinary upload failed: " . $e->getMessage());
        }
    }

    private static function convertBase64ToFile($base64String)
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $tempFilePath = tempnam(sys_get_temp_dir(), 'upload_');
        file_put_contents($tempFilePath, $fileData);
        return $tempFilePath;
    }
}
