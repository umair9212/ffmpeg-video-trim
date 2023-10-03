<?php

namespace App\Http\Controllers;

use App\Models\video;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class VideoCOntroller extends Controller
{
    public function video(Request $request) //this trim video and store file in storage folder
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

        $media->addFilter(['-ss', $start, '-to', $end])
            ->export()
            ->toDisk('local')
            ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
            ->save($outputPath . $trimmedFilename);

        // Clean up the temporary file
        Storage::delete('tmp/' . $trimmedFilename);


        return   $outputPath . $trimmedFilename; // trim video
    }


    //for api and store in public folder in laravel then use this function
    public function trimVideo(Request $request)
    {
        $video = $request->file('video');
        $outputPath = 'public/trimmed_videos/'; //file path
        $trimmedFilename = 'video_' . time() . '.' . $video->getClientOriginalExtension(); //generate unique name
        $video->storeAs('tmp', $trimmedFilename); //store temporary location

        // Perform the actual trimming using FFMpeg
        $media = FFMpeg::fromDisk('local')
            ->open('tmp/' . $trimmedFilename);

        $durationInSeconds = $media->getDurationInSeconds();
        $start = $request->start_time;
        $end = min($request->end_time, $durationInSeconds);

        $media->addFilter(['-ss', $start, '-to', $end])
            ->export()
            ->toDisk('local')
            ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
            ->save($outputPath . $trimmedFilename);
        File::move(public_path('storage/trimmed_videos/' . $trimmedFilename), public_path('uploads/videos/' . $trimmedFilename));

        Storage::delete('tmp/' . $trimmedFilename); //delete temp file
        return response()->json([
            'sussess' => true,
            'message' => "",
            'data' => [
                'trim_video' => asset('uploads/videos/' . $trimmedFilename),
            ],

        ]);
    }
}
