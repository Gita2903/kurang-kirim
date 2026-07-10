<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateToR2 extends Command
{
    protected $signature = 'lampiran:migrate-to-r2
                            {--dry-run : Show what would be uploaded without actually uploading}
                            {--batch=50 : Number of files per batch}';

    protected $description = 'Migrate lampiran files from local storage to Cloudflare R2';

    public function handle(): int
    {
        $localDisk = Storage::disk('public');
        $r2Disk = Storage::disk('r2');
        $dryRun = $this->option('dry-run');
        $batchSize = (int) $this->option('batch');

        // Get all files in lampiran/ folder
        $files = $localDisk->files('lampiran');

        if (empty($files)) {
            $this->warn('No files found in storage/app/public/lampiran/');
            return self::SUCCESS;
        }

        $total = count($files);
        $this->info("Found {$total} files to migrate.");

        if ($dryRun) {
            $this->warn('DRY RUN — no files will be uploaded.');
            foreach ($files as $file) {
                $size = round($localDisk->size($file) / 1024, 1);
                $this->line("  → {$file} ({$size} KB)");
            }
            $this->newLine();
            $this->info("Total: {$total} files. Run without --dry-run to upload.");
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% — %message%");
        $bar->setMessage('Starting...');
        $bar->start();

        $uploaded = 0;
        $skipped = 0;
        $failed = 0;
        $errors = [];

        foreach ($files as $file) {
            $bar->setMessage(basename($file));

            try {
                // Skip if already exists on R2
                if ($r2Disk->exists($file)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Read from local, upload to R2
                $stream = $localDisk->readStream($file);

                if ($stream === null) {
                    $failed++;
                    $errors[] = $file . ' (cannot read)';
                    $bar->advance();
                    continue;
                }

                $r2Disk->writeStream($file, $stream, [
                    'visibility' => 'private',
                ]);

                if (is_resource($stream)) {
                    fclose($stream);
                }

                $uploaded++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = $file . ' (' . $e->getMessage() . ')';
            }

            $bar->advance();
        }

        $bar->setMessage('Done!');
        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Uploaded: {$uploaded}");
        if ($skipped > 0) $this->comment("⏭ Skipped (already on R2): {$skipped}");
        if ($failed > 0) {
            $this->error("❌ Failed: {$failed}");
            foreach ($errors as $err) {
                $this->line("   → {$err}");
            }
        }

        $this->newLine();
        $this->info("Migration complete!");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
