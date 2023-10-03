use the following steps for trimming video in laravel 

1) **composer require pbmedia/laravel-ffmpeg**

2) **php artisan vendor:publish --provider="ProtoneMedia\LaravelFFMpeg\Support\ServiceProvider"**

3) create controller using controller command

4) after perfoming these steps install ffmpeg-6.0-essentials_build pakesge for window through a link  **https://www.gyan.dev/ffmpeg/builds/**

5) setup in env file 

FFMPEG_BINARIES=C:\ffmpeg\bin\ffmpeg.exe
FFPROBE_BINARIES=C:\ffmpeg\bin\ffprobe.exe

6) replace the code with laravel-ffmpeg.php 

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', 'C:\ffmpeg\bin\ffmpeg.exe'),
        'threads'  => 12,
    ],
    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', 'C:\ffmpeg\bin\ffprobe.exe'),
    ],

'timeout' => 3600,
 'enable_logging' => true
];

7) use code in controller

   <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use illuminate\Support\Facades\Storage;
use App\Models\Video;

class VideoController extends Controller
{

    public function uploadForm()
    {
        return view('video.upload_form');
    }

    public function trimVideo(Request $request)
    {
        $video = $request->file('video');
        $outputPath = 'trimmed_videos/';

        // Generate a unique filename for the trimmed video
        $trimmedFilename = 'trimmed_' . time() . '.' . $video->getClientOriginalExtension();

        // Move the uploaded video to a temporary location
        $video->storeAs('tmp', $trimmedFilename);

        // Perform the actual trimming using FFMpeg
        $media = FFMpeg::fromDisk('local')
            ->open('tmp/' . $trimmedFilename);

        $durationInSeconds = $media->getDurationInSeconds();
        $start = $request->start_time;
        $end = min($request->end_time, $durationInSeconds); // Trim the first 30 seconds or the entire duration if it's less than 30 seconds

        $media->addFilter(['-ss', $start, '-t', $end])
            ->export()
            ->toDisk('local')
            ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
            ->save($outputPath . $trimmedFilename);

        // Clean up the temporary file
        Storage::delete('tmp/' . $trimmedFilename);

        // Save the video path and trimmed video path to the database
        $videoModel = new Video();
        $videoModel->video_path = $video->store('videos', 'public');
        $videoModel->trimed_path = $outputPath . $trimmedFilename;
        $videoModel->save();

        // Redirect or do something else after the trimming and saving is done
        return redirect()->back()->with('success', 'Video trimmed and saved successfully.');
    }
}

