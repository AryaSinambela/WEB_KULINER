<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FotoDestinasi extends Model
{
    protected $table = 'foto_destinasi';
    public $timestamps = false;
    protected $guarded = [];

    public function getUrlFotoAttribute(): string
    {
        return self::resolveImageUrl($this->path_foto, 'images/default/placeholder-toko.svg');
    }

    public static function resolveImageUrl(?string $path, string $fallback = 'images/default/placeholder-toko.svg'): string
    {
        if (!$path) {
            return asset($fallback);
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // 1. Jika mengarah ke folder public/images/...
        if (str_starts_with($path, 'images/')) {
            $base = preg_replace('/\.(jpg|jpeg|png|webp|svg)$/i', '', $path);

            // Jika tersedia file .svg (misal file SVG vektor), utamakan agar browser render dengan benar tanpa error MIME type
            if (file_exists(public_path("{$base}.svg"))) {
                return asset("{$base}.svg");
            }

            if (file_exists(public_path($path))) {
                // Deteksi jika file .jpg/.png sebenarnya berformat teks SVG
                $header = @file_get_contents(public_path($path), false, null, 0, 150);
                if ($header && (str_contains($header, '<svg') || str_contains($header, '<?xml'))) {
                    @copy(public_path($path), public_path("{$base}.svg"));
                    return asset("{$base}.svg");
                }
                return asset($path);
            }

            // Cek jika user menaruh file dengan ekstensi berbeda (.jpg, .jpeg, .png, .webp, .svg)
            foreach (['svg', 'webp', 'jpg', 'jpeg', 'png'] as $ext) {
                if (file_exists(public_path("{$base}.{$ext}"))) {
                    return asset("{$base}.{$ext}");
                }
            }
            return asset($fallback);
        }

        // 2. Jika hasil upload form (storage/app/public/...)
        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset($fallback);
    }
}