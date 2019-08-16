<?php echo $html_text; ?>
<script>
    function checkorder(){
        $.ajax({
            url: 'index.php?route=checkout/checkout/checkorder&order_id=<?php echo $order_id;?>',
            type: 'get',
            data:'',
            dataType: 'json',
            success: function(res) {
                if(res){
                    location='<?php echo $redirect;?>';
                }
            }
        });
    }
    setInterval(checkorder,5000);
</script>