<?php

namespace AkshayBhanderi\LaravelMasterCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeMasterCrudCommand extends Command
{
    protected $signature = 'master-crud:make {name? : The module route name, e.g. "banner" or "blog_category"}';

    protected $description = 'Interactively scaffold a new master-CRUD module: migration, model, controller, optional add/edit view, and wiring into routes/config/menu.';

    private const TYPE_MAP = [
        'string' => ['migration' => "\$table->string('%s')%s;", 'validation' => 'string', 'component' => 'x-text'],
        'text' => ['migration' => "\$table->text('%s')%s;", 'validation' => 'string', 'component' => 'x-textarea'],
        'integer' => ['migration' => "\$table->integer('%s')%s;", 'validation' => 'integer', 'component' => 'x-number'],
        'decimal' => ['migration' => "\$table->decimal('%s', 10, 2)%s;", 'validation' => 'numeric', 'component' => 'x-number'],
        'date' => ['migration' => "\$table->date('%s')%s;", 'validation' => 'date', 'component' => 'x-date'],
        'datetime' => ['migration' => "\$table->timestamp('%s')%s;", 'validation' => null, 'component' => 'x-date-time'],
        'image' => ['migration' => "\$table->text('%s')%s;", 'validation' => null, 'component' => 'x-drag-drop-upload'],
    ];

    private string $routeName;
    private string $modelClass;
    private string $table;
    private string $pk;
    private string $viewTitle;
    private array $columns = []; // [ ['name'=>..,'type'=>..,'nullable'=>bool,'inForm'=>bool,'searchable'=>bool,'component'=>..] ]
    private array $gridColumns = []; // ordered ['label'=>.., 'field'=>.. (null for no/status/action)]
    private bool $generateView = true;
    private string $icon = 'bi-collection';

    public function handle(): int
    {
        $this->info('Master CRUD module generator');
        $this->newLine();

        $this->collectModuleBasics();
        $this->collectColumns();
        $this->collectFormAndGridOptions();
        $this->collectMenuIcon();

        if (! $this->confirmSummary()) {
            $this->warn('Aborted — nothing was written.');
            return self::SUCCESS;
        }

        $anchors = $this->validateAnchors();
        if ($anchors !== true) {
            $this->error('Aborted — cannot safely edit routes/config/menu:');
            foreach ($anchors as $problem) {
                $this->line('  - '.$problem);
            }
            return self::FAILURE;
        }

        $created = [];
        $modified = [];

        $migrationFile = $this->writeMigration();
        $created[] = $migrationFile;

        $created[] = $this->writeModel();
        $created[] = $this->writeController();

        if ($this->generateView) {
            $created[] = $this->writeView();
        }

        if ($this->mutateRoutes()) {
            $modified[] = 'routes/web.php';
        }
        if ($this->mutateConfig()) {
            $modified[] = 'config/master-crud.php';
        }
        if ($this->mutateMenu()) {
            $modified[] = 'resources/views/portal/template/menu.blade.php';
        }

        if ($this->confirm('Run the migration now?', true)) {
            $this->call('migrate', ['--path' => 'database/migrations/'.basename($migrationFile), '--force' => true]);
        }

        $this->printSummary($created, $modified);

        return self::SUCCESS;
    }

    private function collectModuleBasics(): void
    {
        $input = $this->argument('name') ?: $this->ask('Module route name (snake_case, e.g. "banner", "blog_category")');
        $this->routeName = Str::of($input)->trim()->lower()->replace([' ', '-'], '_')->__toString();

        $defaultModel = Str::studly($this->routeName);
        $this->modelClass = $this->ask('Model/Controller class name', $defaultModel);

        $defaultTable = Str::plural($this->routeName);
        $this->table = $this->ask('Database table name', $defaultTable);

        $defaultPk = Str::singular($this->routeName).'_id';
        $this->pk = $this->ask('Primary key column', $defaultPk);

        $defaultTitle = Str::title(str_replace('_', ' ', $this->routeName));
        $this->viewTitle = $this->ask('Display name (used in page titles/menu)', $defaultTitle);
    }

    private function collectColumns(): void
    {
        $this->newLine();
        $this->info('Now define the columns (besides the primary key, status, is_delete, timestamps — those are added automatically).');

        $types = array_keys(self::TYPE_MAP);
        $addAnother = true;

        while ($addAnother) {
            $name = $this->ask('Column name (blank to stop adding columns)');
            if (empty($name)) {
                if (empty($this->columns)) {
                    $this->warn('At least one column is recommended, but continuing with none.');
                }
                break;
            }
            $type = $this->choice('Column type for "'.$name.'"', $types, 'string');

            $this->columns[] = [
                'name' => $name,
                'type' => $type,
                'nullable' => true,
                'inForm' => true,
                'searchable' => in_array($type, ['string', 'text']),
                'component' => self::TYPE_MAP[$type]['component'],
            ];

            $addAnother = $this->confirm('Add another column?', true);
        }

        if (empty($this->columns)) {
            return;
        }

        $names = array_column($this->columns, 'name');

        $required = $this->choice('Which columns are REQUIRED (not nullable)? Select none for "all optional".', $names, null, null, true);
        foreach ($this->columns as &$col) {
            $col['nullable'] = ! in_array($col['name'], $required);
        }
        unset($col);

        $excludeFromForm = $this->choice('Exclude any columns from the add/edit form? Select none to include all.', $names, null, null, true);
        foreach ($this->columns as &$col) {
            $col['inForm'] = ! in_array($col['name'], $excludeFromForm);
        }
        unset($col);

        $searchableDefaults = array_column(array_filter($this->columns, fn ($c) => $c['searchable']), 'name');
        $searchable = $this->choice(
            'Which columns should be searchable in the grid search box?',
            $names,
            null,
            null,
            true
        );
        // choice() with multiple + a null default requires an explicit selection; fall back to type-based defaults if the user just presses enter with nothing marked.
        $searchable = empty($searchable) ? $searchableDefaults : $searchable;
        foreach ($this->columns as &$col) {
            $col['searchable'] = in_array($col['name'], $searchable);
        }
        unset($col);

        $this->newLine();
        $this->info('Guessed form components (based on column type):');
        foreach ($this->columns as $col) {
            if ($col['inForm']) {
                $this->line('  '.$col['name'].' -> '.$col['component']);
            }
        }
        if ($this->confirm('Override any component choice?', false)) {
            while (true) {
                $target = $this->choice('Which column? (blank/ctrl-c style — pick "done" to stop)', array_merge(array_column(array_filter($this->columns, fn ($c) => $c['inForm']), 'name'), ['done']), 'done');
                if ($target === 'done') {
                    break;
                }
                $components = ['x-text', 'x-textarea', 'x-number', 'x-date', 'x-date-time', 'x-drag-drop-upload'];
                $newComponent = $this->choice('Component for "'.$target.'"', $components);
                foreach ($this->columns as &$col) {
                    if ($col['name'] === $target) {
                        $col['component'] = $newComponent;
                    }
                }
                unset($col);
            }
        }
    }

    private function collectFormAndGridOptions(): void
    {
        $this->newLine();

        // Default grid: no (pk) -> first string/text column as "Name" -> status -> action
        $firstText = collect($this->columns)->first(fn ($c) => in_array($c['type'], ['string', 'text']));

        $defaultGrid = [['label' => 'no', 'field' => null]];
        if ($firstText) {
            $defaultGrid[] = ['label' => 'Name', 'field' => $firstText['name']];
        }
        $defaultGrid[] = ['label' => 'status', 'field' => null];
        $defaultGrid[] = ['label' => 'action', 'field' => null];

        $this->gridColumns = $defaultGrid;

        if (! empty($this->columns) && $this->confirm('Customize which columns appear in the grid listing? (default: '.($firstText['name'] ?? 'none').' + status)', false)) {
            $names = array_column($this->columns, 'name');
            $chosen = $this->choice('Additional columns to show in the grid (in the order you want them)', $names, null, null, true);

            $middle = array_map(fn ($n) => ['label' => Str::title(str_replace('_', ' ', $n)), 'field' => $n], $chosen);
            $this->gridColumns = array_merge(
                [['label' => 'no', 'field' => null]],
                $middle,
                [['label' => 'status', 'field' => null], ['label' => 'action', 'field' => null]]
            );
        }

        $this->generateView = $this->confirm('Generate the add/edit view (add.blade.php) now?', true);
    }

    private function collectMenuIcon(): void
    {
        $this->icon = $this->ask('Bootstrap icon for the menu (e.g. "bi-images", "bi-box-seam")', 'bi-collection');
    }

    private function confirmSummary(): bool
    {
        $this->newLine();
        $this->info('=== Summary ===');
        $this->line('Route name:      '.$this->routeName);
        $this->line('Model/Controller: '.$this->modelClass.' / '.$this->modelClass.'Controller');
        $this->line('Table:           '.$this->table);
        $this->line('Primary key:     '.$this->pk);
        $this->line('Display name:    '.$this->viewTitle);
        $this->newLine();

        if (! empty($this->columns)) {
            $this->table(['name', 'type', 'nullable', 'form', 'search'], array_map(fn ($c) => [
                $c['name'],
                $c['type'],
                $c['nullable'] ? 'nullable' : 'required',
                $c['inForm'] ? 'in form ('.$c['component'].')' : 'not in form',
                $c['searchable'] ? 'searchable' : '',
            ], $this->columns));
        }

        $this->line('Grid columns:    '.implode(' | ', array_map(fn ($g) => $g['label'], $this->gridColumns)));
        $this->line('Generate view:   '.($this->generateView ? 'yes' : 'no'));
        $this->line('Menu icon:       '.$this->icon);
        $this->newLine();

        $this->info('Files to create:');
        $this->line('  database/migrations/{timestamp}_create_'.$this->table.'_table.php');
        $this->line('  app/Models/portal/master/'.$this->modelClass.'.php');
        $this->line('  app/Http/Controllers/portal/master/'.$this->modelClass.'Controller.php');
        if ($this->generateView) {
            $this->line('  resources/views/portal/master/'.$this->routeName.'/add.blade.php');
        }
        $this->info('Files to modify:');
        $this->line('  routes/web.php — add: $masters[] = [\''.$this->routeName.'\', $this->master_path.\''.$this->modelClass.'Controller\'];');
        $this->line('  config/master-crud.php — add module entry \''.$this->routeName.'\'');
        $this->line('  resources/views/portal/template/menu.blade.php — add menu item');
        $this->newLine();

        return $this->confirm('Proceed?', true);
    }

    /** @return true|array<int, string> */
    private function validateAnchors()
    {
        $problems = [];

        $problems = array_merge($problems, $this->checkAnchor(base_path('routes/web.php'), 'foreach ($masters as $master){'));
        $problems = array_merge($problems, $this->checkAnchor(config_path('master-crud.php'), "'modules' => ["));
        $problems = array_merge($problems, $this->checkAnchor(resource_path('views/portal/template/menu.blade.php'), "@if( \Access::is_allowed('setting','list') || \Access::is_allowed('user','list') )"));

        return empty($problems) ? true : $problems;
    }

    private function checkAnchor(string $file, string $anchor): array
    {
        if (! file_exists($file)) {
            return ["File not found: {$file}"];
        }
        $lines = file($file);
        $count = 0;
        foreach ($lines as $line) {
            if (trim($line) === $anchor) {
                $count++;
            }
        }
        if ($count === 0) {
            return ["Anchor not found in {$file}: \"{$anchor}\""];
        }
        if ($count > 1) {
            return ["Anchor appears {$count} times in {$file} (expected exactly once): \"{$anchor}\""];
        }

        return [];
    }

    private function stub(string $name): string
    {
        return file_get_contents(__DIR__.'/../../../stubs/master-crud/'.$name);
    }

    private function writeMigration(): string
    {
        $columnLines = [];
        foreach ($this->columns as $col) {
            $spec = self::TYPE_MAP[$col['type']]['migration'];
            $suffix = $col['nullable'] ? '->nullable()' : '';
            $columnLines[] = '            '.sprintf($spec, $col['name'], $suffix);
        }

        $content = str_replace(
            ['{{table}}', '{{pk}}', '{{columns}}'],
            [$this->table, $this->pk, implode("\n", $columnLines)],
            $this->stub('migration.stub')
        );

        $filename = date('Y_m_d_His').'_create_'.$this->table.'_table.php';
        $path = database_path('migrations/'.$filename);
        file_put_contents($path, $content);

        return $path;
    }

    private function writeModel(): string
    {
        $content = str_replace(
            ['{{modelClass}}', '{{table}}', '{{pk}}'],
            [$this->modelClass, $this->table, $this->pk],
            $this->stub('model.stub')
        );

        $dir = app_path('Models/portal/master');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir.'/'.$this->modelClass.'.php';
        file_put_contents($path, $content);

        return $path;
    }

    private function writeController(): string
    {
        $validationLines = [];
        foreach ($this->columns as $col) {
            $rule = $col['nullable'] ? 'nullable' : 'required';
            $extra = self::TYPE_MAP[$col['type']]['validation'];
            if ($extra) {
                $rule .= '|'.$extra;
            }
            $validationLines[] = "            \"{$col['name']}\" => '{$rule}',";
        }

        $gridColumnLines = [];
        $columnsArrayParts = [];
        foreach ($this->gridColumns as $g) {
            $width = '10%';
            $extra = $g['label'] === 'no' ? "'sortable','','text-center'" : ($g['field'] !== null && in_array($g['field'], array_column(array_filter($this->columns, fn ($c) => $c['searchable']), 'name')) ? "'sortable','',''" : "'','',''");

            $gridColumnLines[] = "            ['{$g['label']}','{$width}',{$extra}],";

            if ($g['label'] === 'action') {
                continue; // action never gets a $columns entry
            }
            if ($g['label'] === 'no') {
                $columnsArrayParts[] = "'{$this->pk}'";
            } elseif ($g['label'] === 'status') {
                $columnsArrayParts[] = "'status'";
            } else {
                $columnsArrayParts[] = "'{$g['field']}'";
            }
        }

        $searchColumns = array_column(array_filter($this->columns, fn ($c) => $c['searchable']), 'name');
        $searchColumnsStr = implode(',', array_map(fn ($n) => "'{$n}'", $searchColumns));

        $dtRowLines = ["            '#'.\$row->{$this->pk},"];
        foreach ($this->gridColumns as $g) {
            if (in_array($g['label'], ['no', 'action'])) {
                continue;
            }
            if ($g['label'] === 'status') {
                $dtRowLines[] = "            \$this->simple_status(\$row->status),";
            } else {
                $dtRowLines[] = "            \$row->{$g['field']},";
            }
        }
        $dtRowLines[] = "            \$this->action_btn([\n                \$this->delete_btn(\$row->{$this->pk}),\n                \$this->status_btn(\$row->{$this->pk},\$row->status),\n            ],url(\$this->route_name.'-edit/'.\$row->{$this->pk}),'Edit'),";

        $content = str_replace(
            ['{{modelClass}}', '{{table}}', '{{routeName}}', '{{viewTitle}}', '{{validationRules}}', '{{gridColumns}}', '{{columnsArray}}', '{{searchColumns}}', '{{dtRowBody}}'],
            [$this->modelClass, $this->table, $this->routeName, $this->viewTitle, implode("\n", $validationLines), implode("\n", $gridColumnLines), implode(',', $columnsArrayParts), $searchColumnsStr, implode("\n", $dtRowLines)],
            $this->stub('controller.stub')
        );

        $dir = app_path('Http/Controllers/portal/master');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir.'/'.$this->modelClass.'Controller.php';
        file_put_contents($path, $content);

        return $path;
    }

    private function writeView(): string
    {
        $fieldLines = [];
        foreach ($this->columns as $col) {
            if (! $col['inForm']) {
                continue;
            }
            $attrs = 'name="'.$col['name'].'"';
            if ($col['component'] === 'x-textarea') {
                $attrs .= ' rows="3"';
            }
            $fieldLines[] = "            <div class=\"col-md-6\">\n                <{$col['component']} {$attrs} />\n            </div>\n";
        }

        $content = str_replace('{{FIELDS}}', implode("\n", $fieldLines), $this->stub('view-add.stub'));

        $dir = resource_path('views/portal/master/'.$this->routeName);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir.'/add.blade.php';
        file_put_contents($path, $content);

        return $path;
    }

    private function mutateRoutes(): bool
    {
        $file = base_path('routes/web.php');
        $needle = "\$masters[] = ['{$this->routeName}',";
        if ($this->alreadyContains($file, $needle)) {
            $this->warn('routes/web.php already registers "'.$this->routeName.'" — skipping.');
            return false;
        }

        $newLine = "    \$masters[] = ['{$this->routeName}',\$this->master_path.'{$this->modelClass}Controller'];\n";

        return $this->insertBeforeAnchor($file, 'foreach ($masters as $master){', $newLine);
    }

    private function mutateConfig(): bool
    {
        $file = config_path('master-crud.php');
        $needle = "'{$this->routeName}' => [";
        if ($this->alreadyContains($file, $needle)) {
            $this->warn('config/master-crud.php already has a "'.$this->routeName.'" module entry — skipping.');
            return false;
        }

        $newBlock = "        '{$this->routeName}' => [\n"
            ."            'path' => 'App\\Http\\Controllers\\portal\\master\\{$this->modelClass}Controller',\n"
            ."            'name' => '{$this->viewTitle} Management',\n"
            ."        ],\n";

        return $this->insertAfterAnchor($file, "'modules' => [", $newBlock);
    }

    private function mutateMenu(): bool
    {
        $file = resource_path('views/portal/template/menu.blade.php');
        $needle = "is_allowed('{$this->routeName}')";
        if ($this->alreadyContains($file, $needle)) {
            $this->warn('menu.blade.php already has an entry for "'.$this->routeName.'" — skipping.');
            return false;
        }

        $newBlock = "@if( \\Access::is_allowed('{$this->routeName}') )\n"
            ."<li>\n"
            ."    <a href=\"{{route('{$this->routeName}.master')}}\" class=\"@if(isset(\$active) && \$active=='{$this->routeName}') {{'active'}} @endif\">\n"
            ."        <span class=\"nav-link-icon\">\n"
            ."            <i class=\"bi {$this->icon}\"></i>\n"
            ."        </span>\n"
            ."        <span>{$this->viewTitle}</span>\n"
            ."    </a>\n"
            ."</li>\n"
            ."@endif\n\n";

        return $this->insertBeforeAnchor($file, "@if( \Access::is_allowed('setting','list') || \Access::is_allowed('user','list') )", $newBlock);
    }

    private function alreadyContains(string $file, string $needle): bool
    {
        return file_exists($file) && str_contains(file_get_contents($file), $needle);
    }

    private function insertBeforeAnchor(string $file, string $anchor, string $newContent): bool
    {
        $lines = file($file);
        foreach ($lines as $i => $line) {
            if (trim($line) === $anchor) {
                array_splice($lines, $i, 0, [$newContent]);
                file_put_contents($file, implode('', $lines));

                return true;
            }
        }

        return false;
    }

    private function insertAfterAnchor(string $file, string $anchor, string $newContent): bool
    {
        $lines = file($file);
        foreach ($lines as $i => $line) {
            if (trim($line) === $anchor) {
                array_splice($lines, $i + 1, 0, [$newContent]);
                file_put_contents($file, implode('', $lines));

                return true;
            }
        }

        return false;
    }

    private function printSummary(array $created, array $modified): void
    {
        $this->newLine();
        $this->info('Done.');
        $this->line('Created:');
        foreach ($created as $f) {
            $this->line('  + '.str_replace(base_path().DIRECTORY_SEPARATOR, '', $f));
        }
        $this->line('Modified:');
        foreach ($modified as $f) {
            $this->line('  ~ '.$f);
        }
    }
}
