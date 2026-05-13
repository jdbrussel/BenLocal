<?php

namespace App\Console\Commands;

use App\Jobs\AI\TranslateModelFieldJob;
use App\Models\Spot;
use Illuminate\Console\Command;

class AITranslateMissingCommand extends Command
{
    protected $signature = 'benlocal:translate-missing {--force : Overwrite existing translations} {--model=all : Model to translate (Spot, Review, Recommendation or all)}';
    protected $description = 'Translate missing JSON fields for models';

    public function handle()
    {
        $force = $this->option('force');
        $modelOption = $this->option('model');

        $modelsToProcess = [];
        if ($modelOption === 'all') {
            $modelsToProcess = ['Spot', 'Review', 'Recommendation'];
        } else {
            $modelsToProcess = [$modelOption];
        }

        $targetLanguages = ['nl', 'en', 'es', 'de', 'fr'];

        foreach ($modelsToProcess as $modelName) {
            $modelClass = "App\\Models\\$modelName";

            if (!class_exists($modelClass)) {
                $this->error("Model $modelClass not found.");
                continue;
            }

            $this->info("Starting translation for $modelName...");

            $models = $modelClass::all();
            $count = 0;

            foreach ($models as $model) {
                foreach ($model->translatable as $field) {
                    TranslateModelFieldJob::dispatch($model, $field, $targetLanguages, $force);
                    $count++;
                }
            }

            $this->info("Dispatched $count translation jobs for $modelName.");
        }
    }
}
