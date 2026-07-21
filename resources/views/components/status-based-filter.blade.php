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

<ul class="nav nav-tabs d-none d-md-flex">
    @foreach($status_arr as $key => $status)
    <li class="nav-item">
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
        ?>
        <a class="nav-link {{ ($get_status === (string)$key) ? 'active' : '' }} "
        href="{{url()->current()}}?status={{$key}}">
            {{$status}} ( {{$status_count[$key] ?? '0'}} )
        </a>
    </li>
    @endforeach
    @if(!empty($customoption))
    @foreach($customoption as $key => $value)
    <li class="nav-item">
        <?php 
        $get_status = request()->get($value['column']);
        if( !empty($get_status) ){
            $get_status = 'active';
        }
        ?>
        <a class="nav-link {{ $get_status }} " aria-current="page" 
        href="{{url()->current()}}?{{$value['column'].$value['operator'].$value['value']}}">
            {{$value['title']}}
            ( {{$dispatch_date_count[$value['map_value']] ?? '0'}} )
        </a>
    </li>
    @endforeach
    @endif
</ul>