<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsV2Media;
use Illuminate\Http\Request;

class MediaV2Controller extends Controller
{
    private array $presets = [
        'thumb' => [320, 200],
        'sm' => [640, 400],
        'md' => [1024, 640],
        'lg' => [1440, 900],
        'orig' => [0, 0],
    ];

    public function show(Request $request, string $id, string $preset, string $ext)
    {
        $media = CmsV2Media::findOrFail($id);
        $etag = 'W/"'.($media->checksum ?: sha1($media->data))."-$preset";

        if ($request->headers->get('If-None-Match') === $etag) {
            return response('', 304)->header('ETag', $etag);
        }

        $target = $this->presets[$preset] ?? [0,0];
        $data = $media->data;
        $mime = $media->mime ?: 'application/octet-stream';

        if ($preset !== 'orig' && $target[0] > 0 && $target[1] > 0 && function_exists('imagecreatetruecolor')) {
            $tmpSrc = sys_get_temp_dir().'/src_'.$media->id;
            file_put_contents($tmpSrc, $data);
            [$w,$h] = $target;
            [$ok, $bin, $outMime] = $this->resizeCover($tmpSrc, $w, $h, $ext);
            @unlink($tmpSrc);
            if ($ok) { $data = $bin; $mime = $outMime; }
        }

        return response($data, 200)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('ETag', $etag);
    }

    private function resizeCover(string $srcPath, int $tw, int $th, string $ext): array
    {
        try {
            $info = @getimagesize($srcPath);
            if (!$info) return [false, null, null];
            [$sw,$sh] = [$info[0], $info[1]];
            $type = $info[2];
            switch ($type) {
                case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($srcPath); $out = 'image/jpeg'; $oext='jpg'; break;
                case IMAGETYPE_PNG: $src = imagecreatefrompng($srcPath); $out = 'image/png'; $oext='png'; break;
                case IMAGETYPE_WEBP: if (!function_exists('imagecreatefromwebp')) return [false,null,null]; $src=imagecreatefromwebp($srcPath); $out='image/webp'; $oext='webp'; break;
                case IMAGETYPE_GIF: $src = imagecreatefromgif($srcPath); $out='image/gif'; $oext='gif'; break;
                default: return [false, null, null];
            }
            if (!$src) return [false,null,null];
            $scale = max($tw / $sw, $th / $sh);
            $nw = (int)ceil($sw * $scale); $nh = (int)ceil($sh * $scale);
            $tmp = imagecreatetruecolor($nw, $nh);
            imagealphablending($tmp, false); imagesavealpha($tmp, true);
            imagecopyresampled($tmp, $src, 0,0,0,0, $nw,$nh, $sw,$sh);
            $dx = (int)max(0, ($nw - $tw)/2); $dy = (int)max(0, ($nh - $th)/2);
            $dst = imagecreatetruecolor($tw, $th);
            imagealphablending($dst, false); imagesavealpha($dst, true);
            imagecopy($dst, $tmp, 0,0, $dx,$dy, min($tw,$nw), min($th,$nh));
            $outPath = tempnam(sys_get_temp_dir(), 'img');
            $useExt = $ext ?: $oext;
            switch (strtolower($useExt)) {
                case 'jpg': case 'jpeg': imagejpeg($dst, $outPath, 85); $out='image/jpeg'; break;
                case 'png': imagepng($dst, $outPath, 6); $out='image/png'; break;
                case 'webp': if (function_exists('imagewebp')) { imagewebp($dst, $outPath, 85); $out='image/webp'; } else { imagejpeg($dst, $outPath, 85); $out='image/jpeg'; } break;
                case 'gif': imagegif($dst, $outPath); $out='image/gif'; break;
                default: imagejpeg($dst, $outPath, 85); $out='image/jpeg'; break;
            }
            $bin = file_get_contents($outPath);
            @unlink($outPath); imagedestroy($src); imagedestroy($tmp); imagedestroy($dst);
            return [true, $bin, $out];
        } catch (\Throwable $e) { return [false, null, null]; }
    }
}

