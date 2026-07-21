@extends('portal.template.app')
@section('content')

<x-form id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
    @if(isset($data))
        <x-mode-edit />
    @else
        <x-mode-add />
    @endif
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <x-page-title />

                <div class="col-md-6">
                    <x-text label="name" name="role_title" />
                </div>

                <div class="col-md-3">
                    <x-status-select name="status" />
                </div>

                <div class="row mt-4" all-modules>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">   
                                <h4 class="mb-0">Set Permission</h4>
                            </div>
                            <div class="col-md-6 text-end">   
                                <input type="checkbox" class="mb-auto m-1 d-none" id="main_check_all" style="height:15px;width: 15px;" onClick="selectall(this)"/>
                                <label style="font-size: 15px;" class="mt-2" for="main_check_all">Click Here to Select All</label>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <?php
                            $role_permission = isset($data) ? json_decode($data['role_permission'], true) : [];
                            foreach ((array)$module_data as $key => $val) { ?>

                                <div class="col-md-12" module-item>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <div>
                                            <label style="font-size: 16px;" class="me-3"><b><?php echo $val['name']; ?></b></label>
                                        </div>

                                        <div>
                                            <input class="form-check-input" type="checkbox" check-all id="check_all_{{$key}}" onclick="check_all(this)">
                                            <label class="form-check-label font-weight-bold" for="check_all_{{$key}}" onclick="check_all(this)">Select all</label>
                                        </div>
                                    </div>

                                    <div class="form-group ms-2 mt-2 row">
                                        <?php if(!empty($val['permissions'])){ 
                                             foreach ($val['permissions'] as $sub_key => $sub_val) {  
                                                $id = 'check_'.$val['name'].'_'.$sub_key;
                                            ?>
                                            <div class="col-md-2 col-3 form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="{{$id}}" 
                                                @if(isset($data) && !empty($role_permission[$key][$sub_key]))
                                                checked 
                                                @endif
                                                value="{{$sub_key}}" data-check-group="{{$id}}" name="role_permission[{{$key}}][{{$sub_key}}]">
                                                <label class="form-check-label" for="{{$id}}">{{$sub_val}}</label>
                                            </div>
                                        <?php } } ?>
                                    </div>

                                    @if(!empty($val['db_permissions']))
                                    <div class="d-flex align-items-center justify-content-start mt-3 ms-3">
                                        <div>
                                            <label style="font-size: 14px;" class="me-3"><b>- Special Permissions</b></label>
                                        </div>
                                    </div>
                                    <div class="form-group ms-3 mt-2 row">
                                        <?php foreach ($val['db_permissions'] as $sub_key => $sub_val) {  
                                            $id = 'check_'.$val['name'].'_'.$sub_key;
                                        ?>
                                            <div class="col-md-2 col-3 form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="{{$id}}" 
                                                @if(isset($data) && !empty($role_permission[$key]['db_permissions']) && !empty($role_permission[$key]['db_permissions'][$sub_key]))
                                                checked 
                                                @endif
                                                value="{{$sub_key}}" data-check-group="{{$id}}" name="role_permission[{{$key}}][db_permissions][{{$sub_key}}]">
                                                <label class="form-check-label" for="{{$id}}">{{$sub_val}}</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <x-save-btn/>
        </div>
    </div>
</form>

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" script="window.location.href='{{route('user.master')}}'" />

<script type="text/javascript">
function check_all(passed_this){
    setTimeout(function(){
        $(passed_this).parents('[module-item]').find('[data-check-group]').prop('checked',$(passed_this).is(':checked'));
    },50);
}

function edit_check_all(){
    $('#edit_role_form').find('[module-item]').each(function(index, el) {
        if($(el).find('[data-check-group]:checked').length == $(el).find('[data-check-group]').length ){
            $(el).find('[check-all]').prop('checked',true);
        }else{
            $(el).find('[check-all]').prop('checked',false);
        }
    });
}

function selectall(passed_data) {
    $(passed_data).parents('form').find('[type="checkbox"]').prop('checked',$(passed_data).is(':checked'));
}

// Initialize checkboxes on page load
$(document).ready(function() {
    if($('#edit_form').length) {
        edit_check_all();
    }
});
</script>
@endsection 