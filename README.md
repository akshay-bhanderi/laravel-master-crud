# laravel-master-crud

Shared master-CRUD scaffolding extracted from client Laravel admin panels: base controller, model trait, DataTables grid view, form components, and access helper.

## Status

Early extraction in progress. Currently a bare skeleton wired into `Nilkanth-Ayurveda` as a local path dependency for iterative development. Not yet installed by any project as a real dependency.

## Design

See the architecture notes agreed for this package: base runtime classes live in `vendor/` and are extended per project; views/components are overridable via Laravel's standard `vendor:publish`; migrations are never shipped runnable — only Blueprint macros and generator stubs, since every consuming project has different columns.
