@extends('portal.template.app') 
@section('content')  
<style type="text/css">
th{
  text-transform: capitalize;
}
div.dataTables_wrapper div.dataTables_filter input{
  width: 250px;
}
div.dataTables_wrapper div.dataTables_filter input{
  width: 600px;
  height: 40px;
  max-width: 75vw;
  font-size: 16px;
}
[data-href]{
  cursor: pointer;
}
</style>
<div class="row">
  <div class="col-lg-12 bd-content">
    
    <!-- for filter  -->
    @if(!empty($filter_file))
      @include($filter_file)
    @endif
    <!-- for filter  -->

    <div class="text-end m-2">
      <?php if (isset($grid['show_btn']) && $grid['show_btn']==false){
      }else { if (isset($grid['btn_url']) && $grid['btn_url']!='') { ?> 
          <a href="<?php echo $grid['btn_url']?>" class="btn btn-outline-primary btn-lg " href="<?php echo $grid['btn_url']?>">
              <?php echo $grid['btn_name']; ?>
          </a>
      <?php } else {  ?>
          <a href="javascript:;" type="button" class="btn add_<?php echo $grid['grid_tbl_name']; ?> btn-outline-primary btn-lg " data-bs-toggle="modal" data-bs-target=".add_<?php echo $grid['grid_tbl_name']; ?>_modal">
              <?php echo $grid['btn_name']; ?>
          </a>
      <?php } }  ?>
    </div>


    <div class="card">
      <div class="card-body pb-2">
        <form  name="<?php echo $grid['grid_tbl_name']; ?>_form" id="<?php echo $grid['grid_tbl_name']; ?>_form" method="post">
          <table id="<?php echo $grid['grid_tbl_name']; ?>_tbl" class="table">
            <thead>
              <tr>
                <?php $i=0; foreach ($grid['grid_columns'] as $key => $value) {  ?>
                  <th 
                    width="{!! $value[1] ?? '' !!}" 
                    style="{!! $value[3] ?? '' !!}" 
                    class="{!! $value[4] ?? '' !!}"
                    <?php ++$i; if($i == 2){echo 'data-priority="1"';}   ?>
                  > 
                    {!! $value[0] ?? '' !!}
                  </th> 
                <?php  } ?>
              </tr>
            </thead>
          </table>
        </form>
      </div>
    </div>

  </div>
</div>

<div class="appends"></div> 

@if(isset($extra_pages) && !empty($extra_pages)) 
  @foreach($extra_pages as $key=>$value)
    @include($value) 
  @endforeach 
@endif 


@if(!empty($user_modal_include))
    @include($user_modal_include)
@endif

<script type="text/javascript">
  jQuery("input:text:visible:first").focus();
  
  function js_status(id,status,value='') { 
      var status_text = '';
      if(value == ''){
        if(status==1){
           status_text = 'Active';
        }else{
           status_text = 'InActive';
        }
      }else{
        status_text = value;
      }
      jQuery.ajax({
            type: "POST",
            data: {'_token':'{{ csrf_token() }}', id:id, status:status},
            url: "{{route($route.'.status')}}",
            cache: false,
            success: function(response) {
              var json_data = response;       
              if (json_data.status == 200) { 
                  successToast('Status Changed to '+status_text);
                  $('#<?php echo $grid['grid_tbl_name']; ?>_tbl').DataTable().ajax.reload(null, false);
              }else{
                  errorToast("Oops","Try Again.","error");
              }
            } 
      }); 
  }

  function js_delete(id){
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
            url: "{{route($route.'.delete')}}",
            cache: false,
            success: function(response) {
                var json_data = response;       
              if (json_data.status=="200") { 
                Swal.fire({
                title: "Success!",
                text: "Data deleted successfully!",
                type: "success",
                timer: 800});
                $('#<?php echo $grid['grid_tbl_name']; ?>_tbl').DataTable().ajax.reload(null, false);
              }else{
                  Swal.fire(
                  "Internal Error",
                  "Oops,Error Occurred.",
                  "error"
                  )};
            }
          });
        } else{
            Swal.fire({
            title: "Cancelled",
            text: "Your data is safe Now! ",
            type:"error",
            timer:800
            })  ;
        }
    }, 
    function (dismiss) {
      if (dismiss === "cancel") {
        Swal.fire(
        "Internal Error",
        "Oops, Some Error Occurred.",
        "error"
        );
      }
    })
  }

jQuery( "#add_<?php echo $grid['grid_tbl_name']; ?>_up_modal" ).on('shown', function(){ 
  jQuery('.datepicker').css('z-index', 500);
});


jQuery(document).ready(function() {  
    var dd = { 
        beforeSend: function() 
        { 
        
        },
        uploadProgress: function(event, position, total, percentComplete) 
        {
          
        },
        success: function() 
        {
        },
        complete: function(response) 
        {
          // console.log(response.responseText);
          var result = jQuery.parseJSON(response.responseText)  ;
          if (result.status == 200) 
          {
            Swal.fire({
              type: 'success',
              title: 'Successfully saved',
              showConfirmButton: false,
              timer: 1500
            });
            <?php echo $grid['grid_tbl_name']; ?>_tbl.ajax.reload();
             
            jQuery('#<?php echo $grid['grid_tbl_name']; ?>').trigger("reset"); 
            
          }else{
            Swal.fire({
              type: 'warning',
              title: 'Oops',
              text: result.message,
              showConfirmButton: false,
              timer: 2000
            });
          } 
        },
        error: function()
        {

        } 
    }; 

    jQuery("#<?php echo $grid['grid_tbl_name']; ?>").ajaxForm(dd);
});   

var is_first_time_load = true;
jQuery(document).ready(function(){ 
  jQuery.fn.DataTable.ext.errMode = 'none';
  

  <?php echo $grid['grid_tbl_name']; ?>_tbl = jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl').dataTable({
                      "aLengthMenu": [
                          [25, 50, 100, 200, -1],
                          [25, 50, 100, 200, "All"]
                      ],
                      "oLanguage": {
                          "sSearch": "Search",
                          "sLengthMenu": "Show _MENU_ enteries",
                          "sInfo":  " Showing  _START_  to  _END_  (of  _TOTAL_  entries) ", 
                      },
                      "language": {
                        "paginate": {
                          "previous": "Previous",
                          "next": "Next",
                        },
                       "emptyTable": "No data available in table"
                      },
                      "processing": true,
                      "fixedHeader": true,
                      "serverSide": true, 
                      "bAutoWidth": false, 
                      // "responsive": true,
                      /*"scrollY":300,   */
                      "responsive": {
                        "details": {
                            "display": $.fn.dataTable.Responsive.display.childRowImmediate,
                            // "type": 'none'
                        }
                      },
                      "iDisplayLength": <?php echo $grid['grid_tbl_length']; ?>,
                      "ajaxSource": "{{ route($route.'.list') }}{{$grid['dt_list_param'] ?? ''}}",
                      "aoColumns": [<?php foreach($grid['grid_columns'] as $key=>$value) { if($value[2]=='sortable'){ echo "{ 'bSortable' : true}," ;}  else {echo "{ 'bSortable' : false}," ;} }?>                     
                      ],
                      "order":[['{{$grid["grid_order_by"]}}','{{$grid["grid_order_by_type"]}}']],
                      "sDom": "<'row'<'col-sm-3 col-xs-3'l><'col-sm-9 col-xs-9'f>r>t<'row'<'col-sm-5 hidden-xs paging-class'i><'col-sm-7 col-xs-12 clearfix'p>>",
                      
                      'fnDrawCallback' : function(){
                        jQuery('th:first-child').removeClass('sorting_desc');
                        jQuery('th:first-child').removeClass('sorting');
                        if(is_first_time_load){
                          jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl_length select').addClass("form-control");
                          jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl_filter input').addClass("form-control"); 
                          is_first_time_load = false;
                        } 
                      },
  });  



  jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl').delay(100).css("width","100%");  
});

jQuery(window).resize(function(){ 
  // jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl').dataTable().fnAdjustColumnSizing();
});

   
  jQuery(document).ready(function() {
  jQuery('#{{$grid["grid_tbl_name"]}}_tbl').on('init.dt',function() {
        jQuery("#{{$grid["grid_tbl_name"]}}_tbl").removeClass('table-loading').show();
      });
  setTimeout(function(){
    jQuery('#{{$grid["grid_tbl_name"]}}_tbl').dataTable();
  }, 3000);
    
});

$(document).on('submit', 'form[name="filter-form"]', function(event) {
  event.preventDefault();
  var send_data_get = $(this).serialize();
  window.data_table_url = "<?php echo route($route.'.list') ?><?php echo (strpos(route($route.'.list'), '?')) ? '&' : '?'; ?>"+send_data_get;
  $('#<?php echo $grid['grid_tbl_name']; ?>_tbl').DataTable().ajax.url(window.data_table_url).load();   
});

</script>
 
@endsection