<?php 
    try {
        $whatsapp_no = \InsertSettings::get_settings_value('whatsapp-no');
    } catch (\Exception $e) {
        $whatsapp_no = '';
    }
?>
@if(!empty($whatsapp_no))
<script
  src="https://liliya.io/public/assets/liliya-whatsapp-widget.js?v={{date('o-\WW')}}"
  data-phone="{{$whatsapp_no}}"
  data-message="Hi! How can We help you?"
  data-name="{{ config('app.name') }}"
  data-status="Online"
  data-color="#25D366"
  data-position="bottom-right"
  data-avatar=""
  data-auto-open="false"
  data-auto-open-time="5"
></script>
@endif