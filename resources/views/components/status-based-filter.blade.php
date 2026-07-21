<div class="d-flex d-md-none">
    <select class="form-select" onchange="window.location.href=this.value">
        @foreach($status_arr as $key => $status)
            <?php 
                $get_status = request()->get('status');
                if($key == ""){
                    $status = 'All';
                    $key = '213';
                    $status_count[$key] = array_sum($status_count);
                }
                if($get_status == 'all'){
                    $key = '213';
                }
                $url = url()->current() . "?status=" . $key;
            ?>
            <option value="{{ $url }}" {{ ($get_status === (string)$key) ? 'selected' : '' }}>
                {{$status}} ( {{$status_count[$key] ?? '0'}} )
            </option>
        @endforeach
        @if(!empty($customoption))
            @foreach($customoption as $key => $value)
                <?php 
                    $get_status = request()->get($value['column']);
                    $url = url()->current() . "?" . $value['column'] . $value['operator'] . $value['value'];
                ?>
                <option value="{{ $url }}" {{ !empty($get_status) ? 'selected' : '' }}>
                    {{$value['title']}} ( {{$dispatch_date_count[$value['map_value']] ?? '0'}} )
                </option>
            @endforeach
        @endif
    </select>
</div>

<div class="btn-group d-none d-md-flex" role="group" aria-label="Status filter">
    @foreach($status_arr as $key => $status)
    <?php
        $get_status = request()->get('status');
        if($key == ""){
            $status = 'All';
            $key = '213';
            $status_count[$key] = array_sum($status_count);
        }
        if($get_status == 'all'){
            $key = '213';
        }
        $is_active = ($get_status === (string)$key);
    ?>
    <a class="btn btn-sm {{ $is_active ? 'btn-primary' : 'btn-outline-primary' }}"
    href="{{url()->current()}}?status={{$key}}">
        {{$status}} ( {{$status_count[$key] ?? '0'}} )
    </a>
    @endforeach
    @if(!empty($customoption))
    @foreach($customoption as $key => $value)
    <?php
        $get_status = request()->get($value['column']);
        $is_active  = !empty($get_status);
    ?>
    <a class="btn btn-sm {{ $is_active ? 'btn-primary' : 'btn-outline-primary' }}" aria-current="page"
    href="{{url()->current()}}?{{$value['column'].$value['operator'].$value['value']}}">
        {{$value['title']}}
        ( {{$dispatch_date_count[$value['map_value']] ?? '0'}} )
    </a>
    @endforeach
    @endif
</div>