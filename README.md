# laravel-master-crud

Shared master-CRUD scaffolding for Laravel admin panels: a base controller trait
(`HasMasterCrudActions`) that provides `index`/`add`/`edit`/`view`/`delete`/`status`/`save`/`dt_list`
out of the box, a model trait (`HasMasterCrudModel`) for the matching DB access methods, a
DataTables grid view, a set of form components (`<x-select>`, `<x-summernote>`, etc.), and an
access helper.

## Installation

Via Packagist:

```bash
composer require akshay-bhanderi/laravel-master-crud
```

Or as a VCS dependency, without Packagist — add to your project's `composer.json`:

```json
{
    "repositories": [
        { "type": "vcs", "url": "https://github.com/akshay-bhanderi/laravel-master-crud" }
    ],
    "require": {
        "akshay-bhanderi/laravel-master-crud": "^1.0"
    }
}
```

The package's service provider auto-registers via Laravel package discovery.

## Usage

A controller opts into the full CRUD action set with the trait plus three properties:

```php
class BannerController extends MasterController
{
    use HasMasterCrudActions;

    protected $model = Banner::class;
    protected $validation_rules = [
        'banner_title' => 'nullable',
        'banner_image_id' => 'required',
    ];

    public function __construct()
    {
        // route_name, view_path, columns, grid config, View::share(...) as usual
    }

    protected function dt_row($row)
    {
        // only the per-column rendering is controller-specific
        return ['#'.$row->banner_id, $row->banner_title, $this->simple_status($row->status), ...];
    }
}
```

Override any single method (`edit()`, `save()`, `add()`, ...) or the `before_save()` /
`dt_filter()` hooks when a module needs custom behavior (tag resolution, file uploads,
password hashing, etc.) — everything else keeps coming from the trait.

## Views and components

`resources/views/portal/master/**` and `resources/views/components/**` ship inside the package
and resolve automatically under their plain view names (e.g. `portal.master.banner.add`,
`components.select`) — no publishing step required.

To customize any single file in a consuming app, recreate the exact same path under the app's
own `resources/views/...` — Laravel checks the app's `resources/views` before falling back to
the package, so the local file wins automatically. Delete it to fall back to the package version
again. This is wired up in the service provider via `View::addLocation()`, which appends the
package's views directory as a fallback search path.

## Migrations

Not shipped runnable — every consuming project has different columns. Migrations are generated
per project (a `stubs/` directory is reserved for future generator stubs, currently empty).
