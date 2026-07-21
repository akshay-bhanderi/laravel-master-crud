<?php
if (empty($id)) {
    $id = time() * rand(11, 99999999);
}
if (empty($label)) {
    $label = str_replace('_', ' ', $name) . ' : ';
}

$min = is_numeric($min ?? null) ? (int) $min : 1;
$max = is_numeric($max ?? null) ? (int) $max : 100;

// 5 named levels across the range. The 1-100 case (the common one) keeps the
// familiar round numbers instead of whatever a quarter-split of 1..100 works out to.
if ($min === 1 && $max === 100) {
    $levelValues = [1, 25, 50, 75, 100];
} else {
    $span = $max - $min;
    $levelValues = [
        $min,
        (int) round($min + $span * 0.25),
        (int) round($min + $span * 0.5),
        (int) round($min + $span * 0.75),
        $max,
    ];
}
$levelLabels = ['Urgent', 'High', 'Medium', 'Low', 'Lowest'];

$nearestLevelIndex = function ($val) use ($levelValues) {
    $bestIndex = 0;
    $bestDiff = PHP_INT_MAX;
    foreach ($levelValues as $i => $lv) {
        $diff = abs($lv - $val);
        if ($diff < $bestDiff) {
            $bestDiff = $diff;
            $bestIndex = $i;
        }
    }
    return $bestIndex;
};

$rawValue = (int) ($value ?? $data[$name] ?? $levelValues[2]);

// Pills: snapped straight to one of the 5 named levels.
$pillIndex = $nearestLevelIndex($rawValue);
$pillValue = $levelValues[$pillIndex];

// Dots: a finer 10-point scale, each dot still labelled with its nearest named level.
$dotCount = 10;
$dotValues = [];
for ($i = 0; $i < $dotCount; $i++) {
    $dotValues[] = (int) round($min + ($max - $min) * $i / ($dotCount - 1));
}
$dotIndex = 0;
$bestDiff = PHP_INT_MAX;
foreach ($dotValues as $i => $dv) {
    $diff = abs($dv - $rawValue);
    if ($diff < $bestDiff) {
        $bestDiff = $diff;
        $dotIndex = $i;
    }
}
$dotValue = $dotValues[$dotIndex];

// Initial variant only — a page-wide toggle lets the person filling the form switch
// every priority field between pills/dots on the fly (not saved, resets on reload).
$type = ($type ?? 'pills') === 'dots' ? 'dots' : 'pills';
$current = $type === 'dots' ? $dotValue : $pillValue;
$currentLevelIndex = $nearestLevelIndex($current);
?>
@if(empty($nolabel))
<label for="{{ $id ?? '' }}">
    {!! $label ?? '' !!}
    <small class="range-choice-readout" data-readout>{{ $levelLabels[$currentLevelIndex] }} &middot; {{ $current }}</small>
    <button type="button" class="range-choice-toggle" data-range-toggle title="Switch style"><i class="bi bi-arrow-left-right"></i></button>
</label>
@endif

<input type="hidden" id="{{ $id ?? '' }}" {!! $attr ?? '' !!} name="{{ $name ?? '' }}" value="{{ $current }}">

<div class="range-choice-field {{ $class ?? '' }}" data-range-field>

    <div class="range-choice range-choice-pills {{ $type === 'pills' ? '' : 'd-none' }}" data-range-choice data-variant="pills" data-target="{{ $id ?? '' }}">
        @foreach($levelValues as $i => $lv)
            <button type="button"
                class="range-choice-item range-choice-item--level-{{ $i }} {{ $lv === $pillValue ? 'active' : '' }}"
                data-value="{{ $lv }}"
                data-label="{{ $levelLabels[$i] }} &middot; {{ $lv }}"
                title="{{ $levelLabels[$i] }} ({{ $lv }})">
                {{ $levelLabels[$i] }}
            </button>
        @endforeach
    </div>

    <div class="range-choice-dots-wrap {{ $type === 'dots' ? '' : 'd-none' }}" data-variant="dots-wrap">
        <div class="range-choice range-choice-dots" data-range-choice data-variant="dots" data-target="{{ $id ?? '' }}">
            @foreach($dotValues as $i => $dv)
                @php $dvLevel = $nearestLevelIndex($dv); @endphp
                <button type="button"
                    class="range-choice-item range-choice-item--level-{{ $dvLevel }} {{ $dv === $dotValue ? 'active' : '' }}"
                    data-value="{{ $dv }}"
                    data-label="{{ $dv }} &middot; {{ $levelLabels[$dvLevel] }}"
                    title="{{ $dv }} ({{ $levelLabels[$dvLevel] }})">
                    <span class="range-choice-dot"></span>
                </button>
            @endforeach
        </div>
    </div>

</div>

<style type="text/css">
    .range-choice { display: flex; gap: .35rem; }
    .range-choice-item {
        border: 1px solid var(--border, #e4e4e7);
        background: var(--muted, #f4f4f5);
        color: var(--muted-foreground, #71717a);
        border-radius: 9999px;
        cursor: pointer;
        transition: background .12s, color .12s, border-color .12s, transform .1s;
    }
    .range-choice-item:hover:not(.active) { border-color: var(--zinc-400, #a1a1aa); color: var(--foreground, #18181b); }
    .range-choice-item:focus-visible { outline: 2px solid var(--ring, #18181b); outline-offset: 2px; }
    .range-choice-item.active { background: var(--primary, #18181b); color: var(--primary-foreground, #fafafa); border-color: var(--primary, #18181b); }

    .range-choice-pills { flex-wrap: wrap; }
    .range-choice-pills .range-choice-item { flex: 1; min-width: 4.25rem; padding: .4rem .5rem; font-size: .75rem; font-weight: 600; }

    .range-choice-dots { align-items: center; flex-wrap: wrap; }
    .range-choice-dots .range-choice-item { width: 22px; height: 22px; padding: 0; display: flex; align-items: center; justify-content: center; }
    .range-choice-dots .range-choice-dot { width: 9px; height: 9px; border-radius: 50%; background: currentColor; opacity: .35; }
    .range-choice-dots .range-choice-item.active .range-choice-dot { opacity: 1; }
    .range-choice-item--level-0.active { background: var(--destructive, #ef4444); border-color: var(--destructive, #ef4444); color: var(--destructive-foreground, #fef2f2); }
    .range-choice-item--level-1.active { background: var(--warning, #f59e0b); border-color: var(--warning, #f59e0b); color: var(--warning-foreground, #451a03); }
    .range-choice-item--level-3.active { background: var(--info, #3b82f6); border-color: var(--info, #3b82f6); color: var(--info-foreground, #eff6ff); }
    .range-choice-item--level-4.active { background: var(--zinc-400, #a1a1aa); border-color: var(--zinc-400, #a1a1aa); color: var(--zinc-900, #18181b); }

    .range-choice-ends { font-size: .6875rem; font-weight: 600; color: var(--muted-foreground, #71717a); margin-top: .3rem; }

    .range-choice-toggle {
        border: none; background: transparent; color: var(--muted-foreground, #71717a);
        cursor: pointer; padding: .1rem .25rem; font-size: .75rem; vertical-align: middle;
        border-radius: var(--radius-sm, .25rem);
    }
    .range-choice-toggle:hover { color: var(--foreground, #18181b); background: var(--muted, #f4f4f5); }
    .range-choice-toggle:focus-visible { outline: 2px solid var(--ring, #18181b); outline-offset: 1px; }
</style>

<script type="text/javascript">
    (function () {
        // Pills (5 values) and dots (10 values) don't share exact values, so syncing
        // the non-clicked variant highlights whichever of its own buttons is closest.
        function syncGroupsTo(targetId, value) {
            var num = parseInt(value, 10);
            document.querySelectorAll('[data-range-choice][data-target="' + targetId + '"]').forEach(function (group) {
                var items = group.querySelectorAll('.range-choice-item');
                var closest = null, bestDiff = Infinity;
                items.forEach(function (b) {
                    var diff = Math.abs(parseInt(b.dataset.value, 10) - num);
                    if (diff < bestDiff) { bestDiff = diff; closest = b; }
                });
                items.forEach(function (b) { b.classList.toggle('active', b === closest); });
            });
        }

        function bindChoiceClicks(root) {
            (root || document).querySelectorAll('[data-range-choice]').forEach(function (group) {
                if (group.dataset.rangeChoiceBound) return;
                group.dataset.rangeChoiceBound = '1';

                group.addEventListener('click', function (e) {
                    var btn = e.target.closest('.range-choice-item');
                    if (!btn || !group.contains(btn)) return;

                    var targetId = group.dataset.target;
                    var input = document.getElementById(targetId);
                    var label = document.querySelector('label[for="' + targetId + '"] [data-readout]');

                    syncGroupsTo(targetId, btn.dataset.value);

                    if (input) {
                        input.value = btn.dataset.value;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (label) label.innerHTML = btn.dataset.label;
                });
            });
        }

        // Page-wide: switching style on any one field flips every priority field at once.
        function currentGlobalMode() {
            var anyDotsWrap = document.querySelector('[data-variant="dots-wrap"]');
            return anyDotsWrap && !anyDotsWrap.classList.contains('d-none') ? 'dots' : 'pills';
        }

        function applyGlobalMode(mode) {
            document.querySelectorAll('[data-range-field]').forEach(function (f) {
                var pills = f.querySelector('[data-variant="pills"]');
                var dotsWrap = f.querySelector('[data-variant="dots-wrap"]');
                if (!pills || !dotsWrap) return;
                pills.classList.toggle('d-none', mode === 'dots');
                dotsWrap.classList.toggle('d-none', mode === 'pills');
            });
        }

        function bindToggles(root) {
            (root || document).querySelectorAll('[data-range-toggle]').forEach(function (toggle) {
                if (toggle.dataset.rangeToggleBound) return;
                toggle.dataset.rangeToggleBound = '1';

                toggle.addEventListener('click', function () {
                    applyGlobalMode(currentGlobalMode() === 'dots' ? 'pills' : 'dots');
                });
            });
        }

        function init(root) {
            bindChoiceClicks(root);
            bindToggles(root);
        }

        window.initRangeChoice = init;
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () { init(); });
        } else {
            init();
        }
    })();
</script>
