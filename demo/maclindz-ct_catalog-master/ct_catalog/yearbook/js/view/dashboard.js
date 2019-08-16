
$(document).ready(() => {
    bindClickEvent();
})

//绑定页面的事件
function bindClickEvent() {
    $('.tooltip-show').tooltip({ html: true });
    $(".show-slider-panle").click(function (res) {
        let index = res.currentTarget.dataset.index;
        $('.item-slider-content-c' + index + '').slideToggle();
        res.stopPropagation();
    });
    $(".shezhi-btn-box").on("click", function (res) {
        let index = res.currentTarget.dataset.index;
        if ($('.setting-action-panle-p' + index + '').is(':hidden')) {
            $('.setting-action-panle-p' + index + '').show();
        }
        else {
            $('.setting-action-panle-p' + index + '').hide();
        }
        res.stopPropagation();
    });
    $(".setting-action-panle .action-item").on("click", function (res) {
        $(this).parent().hide();
        res.stopPropagation();
    });

    // 点击某个区域之外隐藏内容
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest(".shezhi-btn-box").length == 0) {
            $('.setting-action-panle').hide();
        }
    })

    // modal 1 ->Are you sure?
    $("#vo_optone_cancel").on("click", function (res) {
        $('#vo-optone-modal').hide();
    });
    $("#vo_optone_confirm").on("click", function (res) {
        $('#vo-optone-modal').hide();
    });
    $(".show-vo-optone-body").on("click", function (res) {
        $('#vo-optone-modal').show();
    });
    $(".show-page-manage-panle").on("click", function (res) {
        $('#section-page-manage-panle').show();
    });
    


    // modal 2-> Request Project Proof
    $('.request-proof').click(function (res) {
        $('#vo-opttwo-modal').show();
    })
    $("#vo_opttwo_cancel").on("click", function (res) {
        $('#vo-opttwo-modal').hide();
    });
    $("#vo_opttwo_confirm").on("click", function (res) {
        $('#vo-opttwo-modal').hide();
        $('#vo-optthree-modal').show();
    });

    // modal 3-> Request Project Proof -> next
    $("#vo_optthree_cancel").on("click", function (res) {
        $('#vo-optthree-modal').hide();
        $('#vo-opttwo-modal').show();
    });
    $("#vo_optthree_confirm").on("click", function (res) {
        $('#vo-optthree-modal').hide();
    });

    // modal 4-> What type of section do you want to create
    $('.new-section').click(function (res) {
        $('#vo-optfour-modal').show();
    })
    $("#vo_optfour_cancel").on("click", function (res) {
        $('#vo-optfour-modal').hide();
    });
    $("#vo_optfour_confirm").on("click", function (res) {
        $('#vo-optfour-modal').hide();
        $('#vo-optfive-modal').show();
    });

    // modal 5-> What type of section do you want to create -> next
    $("#vo_optfive_cancel").on("click", function (res) {
        $('#vo-optfive-modal').hide();
    });
    $("#vo_optfive_back").on("click", function (res) {
        $('#vo-optfive-modal').hide();
        $('#vo-optfour-modal').show();
    });
    $("#vo_optfive_confirm").on("click", function (res) {
        $('#vo-optfive-modal').hide();
    });

    // modal 6-> Add Portrait Section
    $("#vo_optsix_back").on("click", function (res) {
        $('#vo-optsix-modal').hide();
    });
    $("#vo_optsix_confirm").on("click", function (res) {
        $('#vo-optsix-modal').hide();
    });

    // modal 7-> Edit Portrait Rules
    $("#vo_optseven_cancel").on("click", function (res) {
        $('#vo-optseven-modal').hide();
    });
    $("#vo_optseven_confirm").on("click", function (res) {
        $('#vo-optseven-modal').hide();
    });

    // modal 8-> Add New Teammate 
    $('#new-team-member').click(function () {
        $('#vo-opteight-modal').show();
    })
    $("#vo_opteight_cancel").on("click", function (res) {
        $('#vo-opteight-modal').hide();
    });
    $("#vo_opteight_confirm").on("click", function (res) {
        $('#vo-opteight-modal').hide();
    });

    // modal 9-> Email Team
    $('#email-team').click(function () {
        $('#vo-optnine-modal').show();
    })
    $("#vo_optnine_cancel").on("click", function (res) {
        $('#vo-optnine-modal').hide();
    });

    // modal 10-> Edit Photo
    $("#vo_optten_cancel").on("click", function (res) {
        $('#vo-optten-modal').hide();
        $('.photos-wrap-head-h1').hide();
        $('.photos-wrap-head-h2').show();

    });


    $('#copy-text').click(function () {
        var urlresult = document.getElementById("pa-url-value");
        urlresult.select();
        document.execCommand("Copy");
        alert('复制成功！')
    })

    //图片管理
    $('#manage-photos').click(function () {
        $('#manage-photo-panle').show();
        $('.wrap1').hide();
    })
    $('#back-wwap1').click(function () {
        $('#manage-photo-panle').hide();
        $('.wrap1').show();
    })
    $('#managePanle-nav .item-nav').click(function (res) {
        let name = res.currentTarget.dataset.name;
        switch (name) {
            case 'Hardcover':
                $('.manage-nav-content').show();
                break;
            default:
                $('.manage-nav-content-other').show();
                $('.manage-nav-content').hide();
                break;
        }
    })


    //*********** 照片选中***********
    // 布局一
    $('.flex1-img-box-select').click(function (res) {
        let index = res.currentTarget.dataset.index;
        let layout = res.currentTarget.dataset.layout;
        $('.img-edit-tool-box').hide();
        $('.flex1-img-box-select').css('border', 'none');
        if ($('.flex1-img-edit-tool_t' + index + '').is(':hidden')) {
            $('.flex1-img-edit-tool_t' + index + '').show();
            $('.img-box-select_active_' + index + '').css('border', '1px solid #00B5E2');
        }
        else {
            $('.flex1-img-edit-tool_t' + index + '').hide();
            $('.img-box-select_active_' + index + '').css('border', 'none');
        }
    })
    //照片编辑
    $('.manage-panle .layout-flex1 .tool-item').click(function (res) {
        let index = res.currentTarget.dataset.index,
            name = res.currentTarget.dataset.name;
        switch (name) {
            case 'tool-apm':
                console.log('放大第' + index + '个')
                break;
            case 'tool-delect':
                console.log('删除第' + index + '个')
                break;
            case 'tool-editor':
                console.log('编辑第' + index + '个')
                $('#vo-optten-modal').show();
                break;
        }
        res.stopPropagation();
    })
    //布局二
    $('.flex2-img-box-select').click(function (res) {
        let index = res.currentTarget.dataset.index;
        let layout = res.currentTarget.dataset.layout;
        $('.img-edit-tool-box').hide();
        if ($('.flex2-img-edit-tool_t' + index + '').is(':hidden')) {
            $('.flex2-img-edit-tool_t' + index + '').show();
            $('.img-box-select_active_' + index + '').css('border', '1px solid #00B5E2');
        }
        else {
            $('.flex2-img-edit-tool_t' + index + '').hide();
            $('.img-box-select_active_' + index + '').css('border', 'none');
        }
    })
    //照片编辑
    $('.manage-panle .layout-flex2 .tool-item').click(function (res) {
        let index = res.currentTarget.dataset.index,
            name = res.currentTarget.dataset.name;
        switch (name) {
            case 'tool-apm':
                console.log('放大第' + index + '个')
                break;
            case 'tool-delect':
                console.log('删除第' + index + '个')
                break;
            case 'tool-editor':
                console.log('编辑第' + index + '个')
                $('#vo-optten-modal').show();
                break;
        }
        res.stopPropagation();
    })
    // 布局切换
    $('.change-layout').click(function (res) {
        let type = res.currentTarget.dataset.type;
        switch (type) {
            case 'line':
                lineLayout();
                break;
            case 'row':
                rowLayout();
                break;
        }
    })

    function lineLayout() {
        $('.layout-flex1').show();
        $('.layout-flex2').hide();
    }
    function rowLayout() {
        $('.layout-flex1').hide();
        $('.layout-flex2').show();
    }


    // manage panle manage people
    $("#manage_people").click(function () {
        if ($("#manage-people-panle").is(':hidden')) {
            $("#manage-people-panle").show();
        } else {
            $("#manage-people-panle").hide();
        }
    })
    $('#managepeople-back').click(function(res){
        $("#manage-people-panle").hide();
    })
    $(".close_section-page-manage").click(function () {
        $("#section-page-manage-panle").hide();
    })
    

    // section page manage panle
    $("#section-page-manage-panle .show_ul").click(function () {
        if ($("#section-page-manage-panle .softcover_ul").is(':hidden')) {
            $("#section-page-manage-panle .softcover_ul").show(1000);
        } else {
            $("#section-page-manage-panle .softcover_ul").hide(1000);
        }
    })
    
    $("#section-page-manage-panle .curror_li").click(function () {
        $("#section-page-manage-panle .curror_li").eq($(this).index()).addClass("active").siblings().removeClass("active");
        $("#section-page-manage-panle .show_div").hide().eq($(this).index()).show();
    });
    $("#section-page-manage-panle .curror_li1").click(function () {
        $("#section-page-manage-panle .show_div").hide();
        $("#section-page-manage-panle .curror_li1").eq($(this).index()).addClass("active1").siblings().removeClass("active1");
        $("#section-page-manage-panle .show_div1").hide().eq($(this).index()).show();
    });
}

