<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'im1' => 'required|image',
            'im2' => 'required|image',
            'preset' => 'required|string',
            'hair' => 'required|in:1,2',
        ]);

        $presetKey = $request->input('preset');
        $preset = $this->presets[$presetKey] ?? $this->presets['default'];

        $hair = $request->input('hair');

        $im1 = $request->file('im1');
        $im2 = $request->file('im2');

        Http::attach('im1', file_get_contents($im1), $im1->getClientOriginalName())
        ->attach('im2', file_get_contents($im2), $im2->getClientOriginalName())
        ->post('http://62.201.232.166:5000/mix-image', [
            'hair' => (string) $hair,
            ...$preset
        ]);

        $im1Name = pathinfo($im1->getClientOriginalName(), PATHINFO_FILENAME);
        $im2Name = pathinfo($im2->getClientOriginalName(), PATHINFO_FILENAME);

        return redirect()->route('status.page', [
            'im1'  => $im1Name,
            'im2'  => $im2Name
        ]);
    }

    public function statusPage($im1, $im2, Request $request)
    {
        $hair = $request->input('hair', '1');

        return view('status', [
            'status' => 'Loading...',
            'im1'    => $im1,
            'im2'    => $im2
        ]);
    }

    public function checkStatus($im1, $im2, Request $request)
    {
        $url = "http://62.201.232.166:5000/check-status/{$im1}/{$im2}";
        $response = Http::get($url);

        return response($response->body(), 200)
            ->header('Content-Type', 'text/plain');
    }

    public function download($im1, $im2, Request $request)
    {
        $hair = $request->input('hair', '1');

        $inputBase = 'http://62.201.232.166:5000/image/';
        $outputBase = 'http://62.201.232.166:5000/image/';

        $im1Name = $im1 . '.png';
        $im2Name = $im2 . '.png';

        return view('done', [
            'image1' => $inputBase . $im1Name,
            'image2' => $inputBase . $im2Name,
            'result' => $outputBase . $im1 . '_' . $im2 . '.png'
        ]);
    }

    private $presets = [
        'low' => [
            'W_steps' => 200,
            'FS_steps' => 200,
            'align_steps1' => 100,
            'align_steps2' => 100,
            'blend_steps' => 100,
            'mask1_steps' => 80,
            'mask2_steps' => 80,
        ],
        'high' => [
            'W_steps' => 2200,
            'FS_steps' => 600,
            'align_steps1' => 200,
            'align_steps2' => 200,
            'blend_steps' => 500,
            'mask1_steps' => 80,
            'mask2_steps' => 80,
        ],
        'medium' => [
            'W_steps' => 1200,
            'FS_steps' => 400,
            'align_steps1' => 150,
            'align_steps2' => 150,
            'blend_steps' => 300,
            'mask1_steps' => 80,
            'mask2_steps' => 80,
        ],
        'default' => [
            'W_steps' => 200,
            'FS_steps' => 200,
            'align_steps1' => 100,
            'align_steps2' => 100,
            'blend_steps' => 100,
            'mask1_steps' => 80,
            'mask2_steps' => 80,
        ],
        'zero' => [
            'W_steps' => 2,
            'FS_steps' => 2,
            'align_steps1' => 1,
            'align_steps2' => 1,
            'blend_steps' => 1,
            'mask1_steps' => 1,
            'mask2_steps' => 1,
        ],
    ];
}
