<?php

namespace App\Filament\Support;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class TranslatableField
{
    public static function make(string $name, string $label = null, string $type = 'text'): Tabs
    {
        $languages = config('benlocal.available_languages', ['en' => 'English']);
        $tabs = [];

        foreach ($languages as $code => $label_lang) {
            $component = match ($type) {
                'textarea' => Textarea::make("$name.$code"),
                'rich' => RichEditor::make("$name.$code"),
                default => TextInput::make("$name.$code"),
            };

            $tabs[] = Tab::make($label_lang)
                ->schema([
                    $component->label($label ? "$label ($label_lang)" : ucfirst($name) . " ($label_lang)")
                ]);
        }

        return Tabs::make($name)
            ->tabs($tabs);
    }
}
