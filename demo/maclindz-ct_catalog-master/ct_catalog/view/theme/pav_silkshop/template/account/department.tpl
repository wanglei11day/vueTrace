<?php echo $header; ?>
<style>
    .line{
        margin-top: 10px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        font-size: 24px;
    }
    .row .media-body {
        display: table-cell;
        vertical-align: middle;
    }
    .row .media .media-left img {
        width: 40px;
        color: #222;
    }


    .row .media .member-name {
        background-color: #38f;
        border: 1px solid #ddd;
        border-radius: 25px;
        width: 50px;
        height: 50px;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        line-height: 50px;
        text-align: center;
        float:left;

    }

    .row .media .member-ck
    {
        float:left;
    }

    .row .media .member-ck input
    {
        height: 50px;
        margin-right: 10px;
        margin-top: 0px;
    }
    @media (max-width: 465px)
        {
        .row .media .media-left img {
            width: 30px;
            color: #222;
        }
        .row .media{
            padding:5px;
            text-align: center;
        }
        .media-heading {
            margin-top: 5px;
        }
        .media-left {
            float: none;
            display:block;
            padding-right: 0px;
        }

    }
</style>
<div class="breadcrumb">
    <div class="container">

        <h1 class="breadcrumb-heading"><?php echo $heading_title; ?></h1>
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>" style="color:#9b9b9b !important;"><?php echo $breadcrumb['text']; ?></a> /</li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="container">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>

    <?php if ($error) { ?>
    <div class="alert alert-warning"><i class="fa fa-check-circle"></i> <?php echo $error; ?></div>
    <?php } ?>

    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <?php echo $content_top; ?>
            <div id="modal-create-department" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title main-heading"><?php echo $text_create ?></h4>
                        </div>
                        <div class="modal-body product-info">

                            <div class="clearfix box-product-infomation">
                                <div class="form-group required">
                                    <label class="control-label" for="input-email"><?php echo $entry_department; ?></label>
                                    <input type="text" name="folder" value=""   class="form-control" />
                                </div>
                                <div class="buttons">
                                    <div class="pull-left">
                                        <button type="button" class="btn btn-primary" id="button-create-invite" data-loading-text="<?php echo $text_loading; ?>" ><?php echo $button_confirm; ?></button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-invite-user" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title main-heading"><?php echo $text_invite ?></h4>
                        </div>
                        <div class="modal-body product-info">

                            <div class="clearfix box-product-infomation">
                                <div  style="overflow: auto">
                                    <div class="form-group required pull-left" style="width: 80%">
                                        <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                        <input type="text" name="invite_email" value=""   class="form-control" />
                                    </div>
                                    <div class="buttons pull-left" style="margin-left: 0px;margin-top: 24px;">
                                        <button style="height: 40px" type="button" class="btn btn-primary" id="button-invite-email" data-loading-text="<?php echo $text_loading; ?>" ><?php echo $button_confirm; ?></button>
                                    </div>
                                </div>

                                <span style="margin-left: 5px;"><?php echo $text_invite_tip; ?><p id="invite_link"><?php echo $text_invite_link; ?></p></span>

                                <div class="buttons pull-right" style="margin-right: 20px;">

                                    <button class="btn btn-primary" id="do-split-btn" data-clipboard-target="#invite_link" style="text-transform:none;"><?php echo $button_copy;?></button>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modal-update-department" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title main-heading"><?php echo $text_update ?></h4>
                        </div>
                        <div class="modal-body product-info">

                            <div class="clearfix box-product-infomation">
                                <div class="form-group required">
                                    <label class="control-label" for="input-email"><?php echo $entry_update_name; ?></label>
                                    <input type="text" name="dp-name" value="<?php echo $cur_department['name'];?>"   class="form-control" />
                                </div>
                                <div class="buttons">
                                    <div class="pull-left">
                                        <button type="button" class="btn btn-primary" id="button-update-name" data-loading-text="<?php echo $text_loading; ?>" ><?php echo $button_confirm; ?></button>
                                    </div>
                                    <div class="pull-right">
                                        <a type="button" class="btn btn-primary" id="button-delete-department" href="<?php echo $delete_department;?>" data-loading-text="<?php echo $text_loading; ?>" ><?php echo $button_delete_department; ?></a>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div   style="margin-top: 0px; background: #ddd;">





                <div class="block" style="background: #fff;padding: 18px 0px;    ">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <div class="media" id="button-invite">
                                <div class="media-left"><img src="image/data/14.png" /></div>
                                <div class="media-body">
                                    <div class="media-heading"><?php echo $text_invite; ?></div>
                                </div>
                            </div>
                        </div>
                        <?php if($isCreator || $department == '0') { ?>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                            <div class="media" id="button-folder">
                                <div class="media-left"><img src="image/data/15.png" /></div>
                                <div class="media-body">
                                    <div class="media-heading"><?php echo $text_create; ?> </div>

                                </div>
                            </div>
                        </div>


                        <?php if($department != '0') { ?>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                            <div class="media" id="button-update">
                                <div class="media-left"><img src="image/data/16.png" /></div>
                                <div class="media-body">
                                    <div class="media-heading"><?php echo $text_update ?></div>

                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                            <div class="media" id="button-delete">
                                <div class="media-left"><img src="image/data/17.png" /></div>
                                <div class="media-body">
                                    <div class="media-heading"><?php echo $button_delete ?></div>

                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>

                <div style="background: #fff">
                    <?php if($department != '0') { ?>
                    <form id="search" class="input-group" action="<?php echo $refresh;?>" method="post" style="margin-bottom: 0px;    padding: 10px 0px; margin-top: 5px;">
                        <div class="container" style="padding-left: 5px;">
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <span><?php echo $text_search_member;?></span><input type="text" style="background: #eeeeee;border: none;border-radius: 3px;" placeholder="<?php echo $text_search_member_holder;?>" name="filter_member" value="<?php echo $filter_member;?>"  id="input-search" class="form-control input-lg">
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <span><?php echo $text_search_task;?></span><input style="background: #eeeeee;border: none;border-radius: 3px;" type="text" placeholder="<?php echo $text_search_task_holder;?>" name="filter_task" value="<?php echo $filter_task;?>"  id="input-search" class="form-control input-lg">
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <input style="height: 32px;margin-top: 17px;" type="submit" class="btn btn-default btn-lg" value="<?php echo $text_search;?>"/>
                                </div>
                            </div>

                        </div>



                    </form>
                    <?php } ?>
                </div>

                <?php if($departments) { ?>

                <div style="margin:0px 0px;background: #fff;">
                    <div class="row" style="    margin: 0px;">
                        <?php foreach ($departments as $department) { ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 10px;background: #f8f7f7;font-size: 16px;    margin-bottom: 1px;">

                            <div class="media pull-left">
                                <a href="<?php echo $department['href'];?>"> <span style="font-size:20px;font-weight: 700;"><?php echo $department['name'];?></span> <?php if($department['show']) { ?><a href="index.php?route=account/department/view_tasks&mid=<?php echo $department['member_id'];?>&tids=<?php echo $department['tids']?>" target="_blank"></a><?php } ?></a>
                            </div>
                            <div class="pull-right"><a href="<?php echo $department['href'];?>"> <i style="margin-top: 6px;" class="fa fa-arrow-right"></i> </a></div>
                        </div>
                        <?php } ?>

                    </div>
                </div>

                <?php } ?>
                <?php if($members) { ?>

                <div style="    padding-top: 10px;background: #fff;">
                    <div class="row"  style="margin:0px;">
                        <?php foreach ($members as $member) { ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 10px;background: #f8f7f7;margin-bottom: 1px;">

                            <div class="media pull-left">

                                <div class="image-content">
                                    <div class="ck member-ck">
                                        <input type="checkbox" name="path[]" value="<?php echo $member['path']; ?>" />
                                    </div>
                                    <div class="member-name"><?php echo $member['name']; ?></div>
                                    <span style="    line-height: 50px;margin-left: 5px;"><?php echo $member['name']; ?> <?php if($member['show']||$member['is_self']) { ?><a style="line-height: 50px;
    margin-left: 5px;" href="index.php?route=account/department/view_tasks&mid=<?php echo $member['member_id'];?>&tids=<?php echo $member['tids']?>" target="_blank"><?php echo $search?'':'( '.count($member['task_projects']).' )';?></a> <?php } ?></span>
                                </div>
                                <input id="<?php echo $members['path']; ?>" type="hidden" name="pathv" value="<?php echo $members['name']; ?>" />

                            </div>
                            <?php if($member['show']) { ?>
                            <div class="pull-right"><a href="index.php?route=account/task/add&department_id=<?php echo $member['department_id']?>&back=<?php echo $department_root_id;?>&name=<?php echo $group_name;?>" ><i style="line-height: 50px;" class="fa fa-plus-square adduser" aria-hidden="true" path="<?php echo $members['path']; ?>"  name="<?php echo $members['name']; ?>"></i></a></div>
                            <?php } ?>
                        </div>
                        <?php } ?>

                    </div>
                </div>

                <?php } ?>
            </div>

            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>

<link href="../../../../javascript/jquery/magnific/magnific-popup.css" rel="stylesheet">
<script type="text/javascript" src="../../../../javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>
<script type="text/javascript"><!--
    new ClipboardJS('.btn');
    $(document).ready(function() {
        $('.filemanager-images .image').magnificPopup({
            type:'image',
            delegate: 'a',
            image: {
                verticalFit: true
            },
            gallery: {
                enabled:true
            }
        });
    });

    $('#button-delete-department').click(function(e) {
        if(confirm('<?php echo $text_confirm;?>'))
        {
            return true;
        }
        else
        {
            e.preventDefault();
            return false;
        }
    })

    $('.pagination a').on('click', function(e) {
        e.preventDefault();

        $('#modal-image').load($(this).attr('href'));
    });

    $('#button-parent').on('click', function(e) {
        e.preventDefault();

        $('#modal-image').load($(this).attr('href'));
    });



    $('#button-refresh').on('click', function(e) {
        e.preventDefault();
        location.reload();
    });

    $('input[name=\'search\']').on('keydown', function(e) {
        if (e.which == 13) {
            $('#button-search').trigger('click');
        }
    });

    $('#button-search').on('click', function(e) {
        var url = 'index.php?route=account/department&department=<?php echo $department; ?>';

        var filter_name = $('#modal-image input[name=\'search\']').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        <?php if ($thumb) { ?>
            url += '&thumb=' + '<?php echo $thumb; ?>';
        <?php } ?>

        <?php if ($target) { ?>
            url += '&target=' + '<?php echo $target; ?>';
        <?php } ?>

        $('#modal-image').load(url);
    });
    //--></script>
<script type="text/javascript"><!--












    $('#button-folder').on('click',function(e) {
        $('#modal-create-department').modal('show');
    })


    $('#button-create-invite').on('click', function() {
        $.ajax({
            url: 'index.php?route=account/department/folder&department=<?php echo $department_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
            beforeSend: function() {
                $('#button-create-invite').prop('disabled', true);
            },
            complete: function() {
                $('#button-create-invite').prop('disabled', false);
            },
            success: function(json) {
                if (json['error']) {
                    alert(json['error']);
                }
                if (json['success']) {
                    alert(json['success']);

                    location.reload();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });


    $('#button-update').on('click',function(e) {
        $('#modal-update-department').modal('show');
    })
    $('#button-update-name').on('click', function() {
        $.ajax({
            url: 'index.php?route=account/department/setting&department=<?php echo $department_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'name=' + encodeURIComponent($('input[name=\'dp-name\']').val()),
            beforeSend: function() {
                $('#button-update-name').prop('disabled', true);
            },
            complete: function() {
                $('#button-update-name').prop('disabled', false);
            },
            success: function(json) {
                if (json['error']) {
                    alert(json['error']);
                }
                if (json['success']) {
                    alert(json['success']);

                    location.reload();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });


    $('#button-invite').on('click',function(e) {
        $('#modal-invite-user').modal('show');
    })
    $('#button-invite-email').on('click', function() {
        $.ajax({
            url: 'index.php?route=account/department/bat_join&department=<?php echo $department_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'emails=' + encodeURIComponent($('input[name=\'invite_email\']').val())+'&department='+<?php echo $department_id; ?>,
            beforeSend: function() {
                $('#button-invite-email').prop('disabled', true);
            },
            complete: function() {
                $('#button-invite-email').prop('disabled', false);
            },
            success: function(json) {
                location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#button-delete').on('click', function(e) {
        var data = $('input[name^=\'path\']:checked');
        if(!data.length) {
            alert('<?php echo $text_empty; ?>')
            return;
        }
        if (confirm('<?php echo $text_confirm; ?>')) {
            $.ajax({
                url: 'index.php?route=account/department/delete',
                type: 'post',
                dataType: 'json',
                data: $('input[name^=\'path\']:checked'),
                beforeSend: function() {
                    $('#button-delete').prop('disabled', true);
                },
                complete: function() {
                    $('#button-delete').prop('disabled', false);
                },
                success: function(json) {
                    if (json['error']) {
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);
                        location.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
    //--></script>
<?php echo $footer; ?>