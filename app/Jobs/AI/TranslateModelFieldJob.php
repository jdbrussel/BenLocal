<?php

namespace App\Jobs\AI;

use App\Services\AI\AITranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranslateModelFieldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Model $model,
        protected string $field,
        protected array $targetLanguages,
        protected bool $force = false
    ) {}

    public function handle(AITranslationService $translationService): void
    {
        $translationService->translateModelField($this->model, $this->field, $this->targetLanguages, $this->force);
    }
}
