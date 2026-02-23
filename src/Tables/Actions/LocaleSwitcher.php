<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tables\Actions;

use Filament\Actions\SelectAction;
use JeffersonGoncalves\FilamentTranslatable\Actions\Concerns\HasTranslatableLocaleOptions;

class LocaleSwitcher extends SelectAction
{
    use HasTranslatableLocaleOptions;

    public static function getDefaultName(): ?string
    {
        return 'activeLocale';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-translatable::messages.locale_switcher.label'));

        $this->setTranslatableLocaleOptions();
    }
}
