---
prev: 1_introduction
next: 2_quick-start
---

# Installation

To get started, use Composer to add the package to your project's dependencies:
```bash
composer require awssat/laravel-sync-migration
```

#### Before Laravel 5.5
In Laravel 5.4. you'll manually need to register the `Awssat\SyncMigration\SyncMigrationServiceProvider::class` service provider in `config/app.php`.
