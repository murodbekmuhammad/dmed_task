<?php

namespace App\Jobs;

use App\Models\Image;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProcessImageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected string $tempPath,
        protected string $originalName
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fullTempPath = Storage::path($this->tempPath);

        if (!file_exists($fullTempPath)) {
            return;
        }

        $hash = hash_file('sha256', $fullTempPath);
        $targetPath = "images/{$hash}.webp";

        DB::transaction(function () use ($fullTempPath, $hash, $targetPath) {
            $image = Image::where('hash', $hash)->first();

            if (!$image) {
                $manager = new ImageManager(new Driver());
                $processedImage = $manager->read($fullTempPath);

                Storage::put(
                    $targetPath,
                    $processedImage->toWebp(90)
                );

                $image = Image::create([
                    'original_name' => $this->originalName,
                    'path' => $targetPath,
                    'hash' => $hash,
                    'size' => Storage::size($targetPath),
                    'status' => 'completed',
                ]);
            }

            $this->user->images()->syncWithoutDetaching($image->id);
        });

        // Clean up temporary original uploaded file
        Storage::delete($this->tempPath);
    }

    /**
     * Handle job failure cleanup.
     */
    public function failed(\Throwable $exception): void
    {
        if (Storage::exists($this->tempPath)) {
            Storage::delete($this->tempPath);
        }
    }
}
