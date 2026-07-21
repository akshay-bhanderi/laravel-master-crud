<div class="row mb-2">
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <div class="text-end m-2">
            <a href="{{$href ?? $grid['btn_url'] ?? '#'}}" class="btn btn-outline-primary btn-lg text-capitalize {{$class ?? ''}}">{{$grid['btn_name'] ?? $title ?? 'Add'}}</a>
        </div>
    </div>
</div>
@if(!empty($filter))
<div class="btn-group" role="group" aria-label="Type filter">
    <?php
        $types = array_flip($filter);
        $get_type = request()->get('type', '0');
    ?>
    @foreach($filter as $value)
    <?php
        $type_key = $types[$value] ?? null;
        $url = url($route.'-master' . ($type_key !== null ? '?type=' . $type_key : ''));
        $is_active = ($get_type == $type_key);
     ?>
    <a class="btn btn-sm {{ $is_active ? 'btn-primary' : 'btn-outline-primary' }}" aria-current="page" href="{{$url}}">
        {{$value}}
    </a>
    @endforeach
</div>
@endif