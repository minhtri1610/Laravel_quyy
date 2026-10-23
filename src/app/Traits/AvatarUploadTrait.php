<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Lưu / xóa ảnh đại diện của Phật tử trên disk public (storage/app/public/avatars).
 * Cột users.avatar lưu đường dẫn tương đối, vd "avatars/abc.jpg".
 */
trait AvatarUploadTrait
{
    public function storeAvatar(UploadedFile $file): string
    {
        return $file->store('avatars', 'public');
    }

    public function deleteAvatar(?string $path): void
    {
        if (!empty($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
