<?php echo $header; ?>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> /</li>
            <?php } ?>
        </ul>
        
    </div>
</div>

<div class="container">
<style>
    .combineop{
        display:none;
    }
    .text-title{
        color:#9c9595;
    }
    .text-content{
        font-size:18px;
        margin-top:2px;
        font-weight: 500;

    }

    .long-text-content
    {
        width:180px;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap
    }
    .row-tr{
        background: #f8f8f8;
        margin-bottom: 10px;
    }
    .task-tags
    {
        height: 38px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    @media (max-width: 568px) {
        .row-2 .product-col{
            margin-top:5px;  !important;
        }
    }


</style>
  <?php if ($success) { ?>
  <div  class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1 id="task-title"><?php echo $text_task_book; ?></h1>

       <div class="clearfix tabs-group" style="padding: 0px;">
        <ul class="nav nav-tabs" role="tablist">
            <li class="<?php echo $myhistorytask_class;?>" style="float:right !important;"><input style="    height: 14px;margin-right: 5px;float: left;line-height: 20px;" type="checkbox" id="combinedesign_box" value=""><span style="line-height: 20px;"><?php echo $text_combine_design;?></span></li>
        </ul>
      </div>



      <?php if ($tasks) { ?>
        <form action="" method="post" id="mainform">
     <div class="task-table" >
        <input type="hidden" name="filter_task" value="<?php echo $filter_task;?>"/>
         <input type="hidden" name="filter_name" value="<?php echo $filter_task;?>"/>
        <?php foreach ($tasks as $k =>$result) { ?>

            <div class="row row-tr"  click="0" row="<?php echo $k;?>" style="padding-bottom: 10px; border-bottom: 1px #ddd solid;">

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 product-col border">

                <div class="text-left combineop" ><span style="margin-right:3px;"><?php echo $text_cover;?></span><input type="radio" style=" height: 12px;margin: 0px;vertical-align: middle; "name="cover" value="<?php echo $result['task_id'];?>"><span style="margin:0 5px;"><?php echo $column_sort; ?></span><input type="text" style="width:50px;text-align:right;color:#000;font-weight: 600;"   class="row-sort" name="I[<?php echo $k;?>][sort]" placeholder="<?php echo $hint_sort_text;?>" value=""><input type="hidden"  name="I[<?php echo $k;?>][task_id]" value="<?php echo $result['task_id']; ?>"></div>
                <div class="text-title"><?php echo $column_name; ?></div>
                <div class="text-content long-text-content"><?php echo $result['name']; ?></div>

                <div class="text-title" style="margin-top: 10px;"><?php echo $column_description; ?></div>
                <div class="text-content"><?php echo $result['description']; ?></div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 product-col border">
                <div class="text-title"><?php echo $column_project; ?></div>
                <div class="text-content ">
                    <?php $c_p = $result['c_p'];?>
                    <div class="long-text-content"><a  href="<?php echo $c_p['href']; ?>"><?php echo $c_p['name']; ?></a></div>
                    <?php if(!empty($result['projects'])) { ?>
                    <div class="btn-group">
                        <button style="padding:5px 0px;" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                            <span><?php echo $text_history; ?></span> <i class="fa fa-caret-down"></i></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($result['projects'] as $project) { ?>
                            <li><a href="<?php echo $project['href']; ?>"><?php echo $project['name']; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                    <div class="edit" style="color:#35373e;font-size:13px;margin-top:3px;"><a  href="<?php echo $result['first_edituri'];?>" ><?php echo $button_edit;?></a> </div>
                </div>
            </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 product-col border" style="margin-top:0px;">
                    <div class="row row-2" style="margin:-5px;">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 product-col border">
                            <div class="text-title"><?php echo $post?$column_excutor:$column_creator; ?></div>
                            <div class="text-content"><?php echo $post?$result['excutor']:$result['creator']; ?></div>

                            <div style="margin-top: 10px;"><span class="text-title" ><?php echo $column_priority; ?></div>
                            <div class="text-content"></span><?php echo $result['priority']; ?></div>

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 product-col border">
                            <div class="text-title"><?php echo $column_date_added; ?></div>
                            <div class="text-content"><?php echo date('Y-m-d',strtotime($result['date_added'])); ?></div>
                            <div class="text-title" style="margin-top: 10px;"><?php echo $column_date_end; ?></div>
                            <div class="text-content" style="<?php  echo $result['over']?'color:#eb0b0b':'';?>"><?php echo $result['date_end']&&$result['date_end']!='0000-00-00 00:00:00'?date('Y-m-d',strtotime($result['date_end'])):''; ?></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 product-col border">
                            <div ><span class="text-title">

                                    <?php if($post==1){ ?>
                                    <div class="btn-group">
                                        <button style="padding:5px 0px;" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                            <span><?php echo $result['status_info']; ?></span> <i class="fa fa-caret-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <?php foreach ($statuss as $k=>$s) { ?>
                                                <li><a href="<?php echo $modify_url.'&task_id='.$result['task_id'].'&status='.$k;?>"><?php echo $s; ?></a></li>
                                            <?php } ?>
                                        </ul>
                </div>
                                    <?php } else { ?>
                                        <span><?php echo $result['status_info']; ?></span>
                                    <?php } ?>


                </div>
                            <div class="text-content" style="font-size: 25px;">
                        <?php
            if($result['total_page_count'] == 0){
              $percent = 0;
            }else{
              $finish =$result['total_page_count']-$result['empty_page_count'];
              $percent = ($finish/$result['total_page_count'])*100;
            }
          ?>
                                <?php echo $percent?round($percent):0;?> %
                            </div>
                            <div class="btn-group">
                                <button style="padding:5px 0px;" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                    <span><?php echo $text_operate; ?></span> <i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu">

                                    <?php foreach ($result['actions'] as $action) { ?>
                                    <li><a href="<?php echo $action['href']; ?>"><?php echo $action['name']; ?></a></li>
                                    <?php } ?>

                                </ul>
                        </div>
                    </div>
                </div>
            </div>



            </div>

        <?php } ?>
     </div>
      
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>

      <div class="buttons clearfix" style="margin-top: 15px;">
        <div class="pull-left" style="display: none;"><a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $button_back; ?></a></div>
    
          <div class="pull-right combineop" style="margin-right: 10px;"><button type="submit" class="btn btn-primary"><?php echo $button_combine_design; ?></button></div>

      </div>
        </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">

    $('input[name="selectall"]').click(function(){
        //alert(this.checked);
        if($(this).is(':checked')){
            $('.subckbox').each(function(){
                //此处如果用attr，会出现第三次失效的情况
                $(this).prop("checked",true);
            });
        }else{
            $('.subckbox').each(function(){
                $(this).removeAttr("checked",false);
            });
            //$(this).removeAttr("checked");
        }

    });

    count = 1;
    conbine = false;
    $(".row-sort").on('click',function() {
        if(!conbine) return;
        var click_row = $(this);
        var val = click_row.val();
        if(val>0)
        {
            console.log('>0');
            $('.row-tr').each(function(index,ele){

                var row_sort = $(ele).find('.row-sort');
                rowval = row_sort.val();
                console.log(rowval);
                if(rowval>val)
                {
                    row_sort.val(rowval-1)
                }
            });
            click_row.val('');
        }
        else
        {
            console.log('==');
            max = 0;
            $('.row-tr').each(function(index,ele){

                var row_sort = $(ele).find('.row-sort');
                rowval = row_sort.val();
                max = rowval>max?rowval:max;
            });
            console.log(max);
            max = Number(max)+1;
            click_row.val(max);
        }

    })

    $("#combinedesign_box").on('change',function () {
        if($(".combineop").is(":hidden")){
            $(".combineop").show();    //如果元素为隐藏,则将它显现
            conbine = true;
        }else{
            $(".combineop").hide();     //如果元素为显现,则将其隐藏
            conbine = false;
            $(".row-tr").css('background-color', '#ffffff');
        }
    });
  $(".save_project").on('click',function(e){
    e.preventDefault();
    var cret = confirm("<?php echo $tip_save_project; ?>");
    if(cret){
      $.ajax({
        url: this.href,
        type: 'get',
        data: '',
        dataType: 'json',
        beforeSend: function() {
           $(this).attr('disabled', true);
        },
        complete: function() {
           $(this).attr('disabled', false);
        },
        success: function(json) {
          $("#task-title").after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      });
    }
  });
</script>
<?php echo $footer; ?>