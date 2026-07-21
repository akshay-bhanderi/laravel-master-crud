@extends('portal.template.app')
@section('content')
<style type="text/css">
th{
  text-transform: capitalize;
}
</style>



<!-- for filter  -->
@if(!empty($filter_file))
  @include($filter_file)
@endif
<!-- for filter  -->

<div class="card">
  <div class="card-body">
    <div class="row">

      <div class="col-md-2">
          <select class="form-select" id="page_len">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="40">40</option>
              <option value="50">50</option>
          </select>
      </div>
      <div class="col-md-7">
          <div class="input-group">
              <input type="text" class="form-control" placeholder="Search" id="tblSearch">
              <button class="btn btn-outline-light" type="button">
                  <i class="bi bi-search"></i>
              </button>
          </div>
      </div>

      <div class="col-md-3 text-end">
        <div class="dropdown ms-auto">
            <?php if (isset($grid['grid_add_button']) && $grid['grid_add_button']==false){
            }else { if (isset($grid['grid_add_url']) && $grid['grid_add_url']!='') { ?>
                <a href="<?php echo $grid['grid_add_url']?>" class="btn btn-primary btn-icon " href="<?php echo $grid['grid_add_url']?>">
                    <i class="bi bi-plus-circle"></i>
                    <?php echo $grid['grid_add_button_name']; ?>
                </a>
            <?php } else {  ?>
                <a href="javascript:;" type="button" class="btn add_<?php echo $grid['grid_tbl_name']; ?> btn-primary btn-icon " data-bs-toggle="modal" data-bs-target=".add_<?php echo $grid['grid_tbl_name']; ?>_modal">
                    <i class="bi bi-plus-circle"></i>
                    <?php echo $grid['grid_add_button_name']; ?>
                </a>
            <?php } }  ?>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="table-responsiv e" style="overflow: initial;outline: none;">
    <form  name="<?php echo $grid['grid_tbl_name']; ?>_form" id="<?php echo $grid['grid_tbl_name']; ?>_form" method="post">
      <table id="<?php echo $grid['grid_tbl_name']; ?>_tbl" class="table table-custom ">
        <thead>
          <tr>
            <?php $i=0; foreach ($grid['grid_columns'] as $key => $value) {  ?>
              <th
              <?php echo $value['width'] ?> <?php echo $value['style'] ?> <?php echo $value['class'] ?>
              <?php ++$i; if($i == 2){echo 'data-priority="1"';}   ?>
              >
                <?php echo $value['name'];?>
              </th>
            <?php  } ?>
          </tr>
        </thead>
      </table>
    </form>
  </div>
</div>


<script type="text/javascript">
  jQuery("input:text:visible:first").focus();

  function js_status(id,status) {
      jQuery.ajax({
            type: "POST",
            data: {'_token':'{{ csrf_token() }}', id:id, status:status},
            url: "<?php echo $grid['grid_status_url']?>",
            cache: false,
            success: function(response) {
              console.log(response);
              var json_data = response;
              if (json_data.status == 200) {
                  iziToast.success({
                    title: 'Status Changed!',
                    position: 'topRight',
                  });
               /* Swal.fire({
                  title: "Status Changed!",
                  type: "success",
                  timer: 800  });*/
                  <?php echo $grid['grid_tbl_name']; ?>_tbl.ajax.reload();
              }else{
                  Swal.fire("Oops","Try Again.","error")};
              }
          });
  }

  function js_edit(id) {
      jQuery.ajax({
      type: "GET",
      url: '<?php echo $grid['grid_data_url']?>/'+id,
      success: function(response)
          {
            jQuery('.appends').html(response);
            jQuery('#edit_<?php echo $grid['grid_tbl_name']; ?>_modal').modal('show');
            ! function(e) {
                "use strict";
                  var t = function() {};
                  t.prototype.init = function() {
                      e(".select2").select2({
                          width: "100%"
                      })
                  }, e.AdvancedForm = new t, e.AdvancedForm.Constructor = t
                }(window.jQuery),
                function(t) {
                    "use strict";
                    window.jQuery.AdvancedForm.init()
                }();
          },

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
            url: "<?php echo $grid['grid_delete_url']?>",
            cache: false,
            success: function(response) {
                var json_data = response;
              if (json_data.status=="200") {
                Swal.fire({
                title: "Success!",
                text: "Data deleted successfully!",
                type: "success",
                timer: 800});
                <?php echo $grid['grid_tbl_name']; ?>_tbl.ajax.reload();
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
          console.log(response.responseText);
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

  <?php echo $grid['grid_tbl_name']; ?>_tbl = jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl').DataTable({
                      "oLanguage": {
                          "sSearch": "Search",
                          "sLengthMenu": "Show _MENU_ enteries",
                          "sInfo":  " Showing  _START_  to  _END_  of  _TOTAL_  entries ",
                      },
                      "stateSave": true,
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
                      "responsive": true,
                      /*"scrollY":300,   */
                      "iDisplayLength": <?php echo $grid['grid_tbl_length']; ?>,
                      "ajaxSource": "<?php echo $grid['grid_dt_url']?>",
                      "aoColumns": [<?php foreach($grid['grid_columns'] as $key=>$value) { if($value['sortable']=='true'){ echo "{ 'bSortable' : true}," ;}  else {echo "{ 'bSortable' : false}," ;} }?>
                      ],
                      "order":[['{{$grid["grid_order_by"]}}','{{$grid["grid_order_by_type"]}}']],
                      "sDom": "t<'row'<'col-sm-5 hidden-xs paging-class'i><'col-sm-7 col-xs-12 clearfix'p>>",

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
  window.data_table_url = "<?php echo $grid['grid_dt_url']; echo (strpos($grid['grid_dt_url'], '?')) ? '&' : '?'; ?>"+send_data_get;
  {{$grid['grid_tbl_name'];}}_tbl.ajax.url(window.data_table_url).load();
});

$('#page_len').on('change , keyup',function(){
  <?php echo $grid['grid_tbl_name']; ?>_tbl.page.len($(this).val()).draw();
});

$('#tblSearch').keyup(function(){
  <?php echo $grid['grid_tbl_name']; ?>_tbl.search($(this).val()).draw();
});

</script>


@endsection