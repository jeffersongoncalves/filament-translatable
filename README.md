<div class="filament-hidden">

![Filament Translatable](https://raw.githubusercontent.com/jeffersongoncalves/filament-translatable/3.x/art/jeffersongoncalves-filament-translatable.png)

</div>

# Filament Translatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-translatable.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-translatable)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-translatable.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-translatable)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-translatable.svg?style=flat-square)](LICENSE.md)

Enhanced Filament plugin for [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) with translation status indicators, locale flags, and improved developer experience.

## Version Compatibility

| Branch | Filament | PHP | Laravel | Tailwind | Livewire | Install |
|--------|----------|-----|---------|----------|----------|---------|
| [1.x](https://github.com/jeffersongoncalves/filament-translatable/tree/1.x) | v3 | ^8.1 | 10+ | 3.x | 3.x | `"^1.0"` |
| [2.x](https://github.com/jeffersongoncalves/filament-translatable/tree/2.x) | v4 | ^8.2 | 11+ | 4.x | 3.x | `"^2.0"` |
| [3.x](https://github.com/jeffersongoncalves/filament-translatable/tree/3.x) | v5 | ^8.2 | 11.28+ | 4.x | 4.x | `"^3.0"` |

> You are viewing the documentation for **branch 3.x** (Filament v5).

## Features

- **Locale Switching** - Switch between locales on Create, Edit, List, View, and Manage pages
- **Translation Status Column** - Visual table column showing translation completeness per locale with colored badges
- **Translation Status Trait** - Introspect translation status (complete/partial/empty) and completeness percentage
- **Locale Flags** - Emoji flag support with configurable display (flag+label, flag only, label only)
- **Unified DX Trait** - `InteractsWithTranslations` for less boilerplate
- **SQLite Search** - JSON search support for SQLite (in addition to MySQL and PostgreSQL)
- **RelationManager Support** - Independent locale management for relation managers
- **30+ Built-in Flags** - Pre-configured emoji flags for the most common locales

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/filament-translatable:"^3.0"
```

Optionally publish the config:

```bash
php artisan vendor:publish --tag="filament-translatable-config"
```

## Setup

### 1. Configure your Model

Your model must use Spatie's `HasTranslations` trait:

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'content', 'slug'];

    public array $translatable = ['title', 'content'];
}
```

Translatable columns must use `json` type in your migration:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->json('title');
    $table->json('content');
    $table->string('slug'); // non-translatable fields use regular column types
    $table->timestamps();
});
```

### 2. Register the Plugin

Add the plugin to your `PanelProvider`:

```php
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentTranslatablePlugin::make()
                ->defaultLocales(['en', 'pt_BR', 'es']),
        ]);
}
```

### 3. Add Translatable to your Resource

```php
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\Translatable;

class PostResource extends Resource
{
    use Translatable;

    // ...
}
```

### 4. Add Translatable to Pages

Each page type needs its own trait and the `LocaleSwitcher` header action:

```php
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\ListRecords;

class CreatePost extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}

class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}

class ListPosts extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}
```

Additional page types are also supported:

```php
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\ViewRecord;
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\ManageRecords;

// ViewRecord
class ViewPost extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}

// ManageRecords (simple resource)
class ManagePosts extends ManageRecords
{
    use ManageRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}
```

## Translation Status Column

Show translation completeness per locale in your table with colored badges:

```php
use JeffersonGoncalves\FilamentTranslatable\Tables\Columns\TranslationStatusColumn;

public static function table(Table $table): Table
{
    return $table->columns([
        TextColumn::make('title'),
        TranslationStatusColumn::make('translations')
            ->showPercentage()    // show completion percentage
            ->onlyShowMissing()   // hide locales that are fully translated
            ->showFlags()         // show emoji flags next to locale labels
            ->locales(['en', 'pt_BR', 'es']), // override plugin locales
    ]);
}
```

Each locale displays a colored badge indicating its translation status:
- **Success** (green) - All translatable attributes are filled
- **Warning** (yellow) - Some translatable attributes are filled
- **Danger** (red) - No translatable attributes are filled

## Translation Status Trait

Use `HasTranslationStatus` on any page to check translation status programmatically:

```php
use JeffersonGoncalves\FilamentTranslatable\Concerns\HasTranslationStatus;

class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    use HasTranslationStatus;
}
```

Available methods:

```php
// Get status per locale: 'complete', 'partial', or 'empty'
$this->getTranslationStatus($record);
// => ['en' => 'complete', 'pt_BR' => 'partial', 'fr' => 'empty']

// Get completeness percentage per locale (0-100)
$this->getTranslationCompleteness($record);
// => ['en' => 100, 'pt_BR' => 50, 'fr' => 0]

// Get locales that have at least one translated attribute
$this->getTranslatedLocales($record);
// => ['en', 'pt_BR']
```

## Locale Flags

The plugin ships with 30+ built-in emoji flags. Configure them per locale in the plugin or via config:

```php
FilamentTranslatablePlugin::make()
    ->defaultLocales(['en', 'pt_BR', 'es'])
    ->localeFlags([
        'en' => "\u{1F1FA}\u{1F1F8}",
        'pt_BR' => "\u{1F1E7}\u{1F1F7}",
        'es' => "\u{1F1EA}\u{1F1F8}",
    ])
    ->flagDisplay('flag_and_label'), // 'flag_and_label' | 'flag_only' | 'label_only'
```

Built-in flags include: `en`, `pt_BR`, `pt`, `es`, `fr`, `de`, `it`, `nl`, `ja`, `ko`, `zh`, `ru`, `ar`, `hi`, `tr`, `pl`, `uk`, `sv`, `da`, `no`, `fi`, `cs`, `el`, `ro`, `hu`, `th`, `vi`, `id`, `ms`, `he`.

## Custom Locale Labels

Override how locale names are displayed:

```php
FilamentTranslatablePlugin::make()
    ->defaultLocales(['en', 'pt_BR'])
    ->getLocaleLabelUsing(fn (string $locale) => match ($locale) {
        'en' => 'English',
        'pt_BR' => 'Portugues',
        default => null, // falls back to locale_get_display_name()
    }),
```

## RelationManager

Relation managers have independent locale management using a dedicated `LocaleSwitcher`:

```php
use JeffersonGoncalves\FilamentTranslatable\Resources\RelationManagers\Concerns\Translatable;
use JeffersonGoncalves\FilamentTranslatable\Tables\Actions\LocaleSwitcher;

class CommentsRelationManager extends RelationManager
{
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}
```

> Note: Relation managers use `JeffersonGoncalves\FilamentTranslatable\Tables\Actions\LocaleSwitcher` (from `Tables\Actions`), while pages use `JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher` (from `Actions`).

## InteractsWithTranslations

For less boilerplate, you can use the unified `InteractsWithTranslations` trait instead of page-specific traits:

```php
use JeffersonGoncalves\FilamentTranslatable\Concerns\InteractsWithTranslations;

class EditPost extends EditRecord
{
    use InteractsWithTranslations;

    // Automatically detects the page type and provides
    // getTranslatableLocales() and locale management
}
```

## Configuration

```php
// config/filament-translatable.php

return [
    /*
    |--------------------------------------------------------------------------
    | Locale Flags
    |--------------------------------------------------------------------------
    |
    | Map of locale codes to emoji flags. Used by the LocaleSwitcher and
    | TranslationStatusColumn to display visual locale indicators.
    |
    */
    'locale_flags' => [
        'en' => "\u{1F1FA}\u{1F1F8}", // US
        'pt_BR' => "\u{1F1E7}\u{1F1F7}", // BR
        'es' => "\u{1F1EA}\u{1F1F8}", // ES
        'fr' => "\u{1F1EB}\u{1F1F7}", // FR
        'de' => "\u{1F1E9}\u{1F1EA}", // DE
        // ... 25+ more built-in
    ],

    /*
    |--------------------------------------------------------------------------
    | Flag Display Format
    |--------------------------------------------------------------------------
    |
    | Controls how locale labels are displayed in the LocaleSwitcher.
    | Options: 'flag_and_label', 'flag_only', 'label_only'
    |
    */
    'flag_display' => 'flag_and_label',

    /*
    |--------------------------------------------------------------------------
    | Translation Status Colors
    |--------------------------------------------------------------------------
    |
    | Filament color names used by the TranslationStatusColumn badges.
    |
    */
    'status_colors' => [
        'complete' => 'success',
        'partial' => 'warning',
        'empty' => 'danger',
    ],
];
```

## Migration from `filament/spatie-laravel-translatable-plugin`

This package is an enhanced fork of Filament's official translatable plugin. Migration is straightforward:

**1. Replace the package:**

```bash
composer remove filament/spatie-laravel-translatable-plugin
composer require jeffersongoncalves/filament-translatable:"^3.0"
```

**2. Update PanelProvider imports:**

```php
// Before
use Filament\SpatieLaravelTranslatablePlugin;

// After
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;
```

**3. Update Resource and Page imports:**

Replace `Filament\Resources\` with `JeffersonGoncalves\FilamentTranslatable\Resources\` in all translatable traits.

**4. Update LocaleSwitcher imports:**

```php
// Before
use Filament\Actions\LocaleSwitcher;

// After (for pages)
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;

// After (for relation managers)
use JeffersonGoncalves\FilamentTranslatable\Tables\Actions\LocaleSwitcher;
```

**5. Enjoy the new features** - Translation Status Column, locale flags, and status introspection are ready to use.

## Testing

```bash
composer test
```

## Changelog

Please see [Releases](https://github.com/jeffersongoncalves/filament-translatable/releases) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
