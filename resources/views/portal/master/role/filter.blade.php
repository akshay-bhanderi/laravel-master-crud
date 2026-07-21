@if( \Access::is_allowed('role','view') )
<style type="text/css">
.mobile-flex{
  flex-direction: row;
  flex-wrap: wrap;
  align-content: center;
  justify-content: space-between;
  align-items: center;
}
</style>
<h4 class="mt-0">Role List</h4>
<div class="row row-cols-1 g-4" id="abcd_tbl" >
  <div class="col" >
    <div class="row g-2" >
      @foreach ($role_data as $key => $value)
      <div class="col-md-3 col-12">
          <div class="card p-4 d-md-block d-flex mobile-flex">
            <h4 class="card-title">{{$value->role_title}}</h4>
            <label > Total {{$value->user_count}} User</label>

            <div class="btn-group w-100 d-block" role="group" aria-label="Basic example">
              @if( \Access::is_allowed('role','edit') )
              <a class="text-end text-danger card-link" 
                style="font-size: 15px; font-weight: 600;" href="{{route('role.edit',[$value->role_id])}}" >Edit</a>
              @endif
              @if( \Access::is_allowed('role','delete') )
              @if($value->role_id > 1)
                <span class="text-left text-danger card-link" style="cursor: pointer; font-size: 15px;font-weight: 600;float: right" onclick="js_delete_user({{$value->role_id}})" >Delete</span>
              @endif
              @endif
            </div>
          </div>
      </div> 
      @endforeach
      @if( \Access::is_allowed('role','add') )
      <div class="col-md-3 col-12">
          <a class="card border-1 p-4 card-danger" href="{{route('role.add')}}" >
            <span >&nbsp;</span>
            <h4 class="card-title text-center">+ Add New Role</h4>
            <div class="btn-group w-100 d-block" role="group" aria-label="Basic example">
              <span class="text-end text-danger card-link">&nbsp;</span>
            </div>
          </a>
      </div> 
      @endif
    </div>
  </div>
</div>

<script>
   function js_delete_user(id){
    var form_data={_token:'{{csrf_token()}}',id:id}; 
    Swal.fire({
      title: "Confirm",
      text: "Are you sure you want to delete this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#1FAB45",
      confirmButtonText: "Yes, Delete it.",
      cancelButtonText: "Cancel",
      buttonsStyling: true
    }).then((result) => {
        if (result.value == true) {
          jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            type: "POST",
            data: form_data,
            url: "{{route('role.delete')}}",
            cache: false,
            success: function(response) {
              if (response.status=="200") { 
                Swal.fire({
                  title: "Success!",
                  text: "Data deleted successfully!",
                  type: "success",
                  timer: 800
                });
                window.location.reload()
                abcd_tbl.ajax.reload(); 
              }else{
                  Swal.fire("Internal Error",response.message,"error")};
              }
          });
        } else{ Swal.fire({ title: "Cancelled", text: "Your data is safe Now! ", type:"error", timer:800}); }
    }, 
    function (dismiss) { if (dismiss === "cancel") { Swal.fire("Internal Error","Oops, Some Error Occurred.","error"); } })
  }
</script>
@endif

<h4>User List</h4>