<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
?>

<!-- Bootstrap Datepicker -->
<script type="text/javascript" src="/ext/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<link href="/ext/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">

<?php if (lang('meta-language') != 'en') { ?>
    <script type="text/javascript" src="/ext/bootstrap-datepicker/locales/bootstrap-datepicker.<?php echo lang('meta-language') ?>.min.js"></script>
<?php } ?>
    
<script type="text/javascript">
    $('#date_available_product_stock').datepicker({
        language: "<?php echo lang('meta-language') ?>",
        autoclose: true,
        updateViewDate: false,
        clearBtn: true,
        startDate: '+1d',
        calendarWeeks: true
    });
</script>