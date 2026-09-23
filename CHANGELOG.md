# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/filament-translatable/compare/3.1.0...3.x)

## [1.0.0](https://github.com/jeffersongoncalves/filament-translatable/releases/tag/v1.0.0) - YYYY-MM-DD

### Added

- Initial release for Filament v3
- Plugin class with `defaultLocales()` and `getLocaleLabelUsing()` configuration
- Content driver bridging Filament forms/tables with Spatie translatable models
- LocaleSwitcher actions for pages and tables
- Translatable traits for all page types (Create, Edit, List, Manage, View)
- RelationManager translatable support
- Translation status indicator (`HasTranslationStatus` trait)
- `TranslationStatusColumn` table column with colored badges per locale
- Locale flags support with emoji flag mapping
- `InteractsWithTranslations` unified trait for less boilerplate
- SQLite JSON search support
- Laravel Boost integration (guidelines + skill)

## [3.1.0](https://github.com/jeffersongoncalves/filament-translatable/compare/v1.0.0...3.1.0) - 2026-09-23

### What's new

- **Translations:** 17 new locales (ar, az, de, es, fa, fr, hi, it, ja, nl, pl, pt, ru, tr, uk, uz, zh_CN). (#21)

Thanks to @Elvin-Qulizade (Elvin Qulizada) for the i18n initiative behind these translations — first contributed in jeffersongoncalves/filament-scanner-guard#2 and now rolled out across the Filament plugins. He is credited as co-author.

### What's Changed

* docs: add Buy Me a Coffee sponsor link by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/1
* docs: standardize README section structure by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/4
* chore: add GitHub Sponsors to FUNDING.yml by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/7
* ci: standardize update-changelog workflow (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/13
* ci: standardize dependabot config by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/14
* ci: standardize tests workflow (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/18
* feat(i18n): add translations (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-translatable/pull/21

**Full Changelog**: https://github.com/jeffersongoncalves/filament-translatable/compare/v3.0.1...3.1.0
