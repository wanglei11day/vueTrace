
$(document).ready(() => {
    bindClickEvent();
})

//绑定页面的事件
function bindClickEvent() {

    // 点击某个区域之外隐藏内容
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest(".header-action").length == 0) {
            $('.header-action-panle').hide();
        }
        if (target.closest(".project-select-input").length == 0) {
            $('.pro-select-view').hide();
        }
        if(target.closest(".project-item").length == 0){
            $('.project-item-panle').hide();
        }
    })

    $(".header-action").on("click", function () {
        if ($('.header-action-panle').is(':hidden')) {
            $('.header-action-panle').show();
        }
        else {
            $('.header-action-panle').hide();
        }
    });

    $('.search-ip').on('focus', function () {
        $('.header-input-select').show();
    })
    $('.header-select-item').on('click', function () {
        $('.vo-optfive-modal').show();
        $('.header-input-select').hide();
    })
    $('.project-item').on('click', function (res) {
        let index = res.currentTarget.dataset.index;
        $('.project-item-panle').hide();
        if ($('.project-i-panle-' + index + '').is(':hidden')) {
            $('.project-i-panle-' + index + '').show();
        }
        else {
            $('.project-i-panle-' + index + '').hide();
        }
    })
    $('#new-project').click(function (res) {
        $('.vo-optseven-modal').show();
    })

    $('.help-icon-box').click(function () {
        if ($('.create-help-panle').is(':hidden')) {
            $('.create-help-panle').show();
        }
        else {
            $('.create-help-panle').hide();
        }
    })


    // opt1 modal click
    $("#vo_optone_confirm").on("click", function () {
        $('.vo-optone-modal').hide();
    });

    // opt2 modal click   
    $('#vo_opttwo_confirm').on('click', function () {
        $('.vo-opttwo-modal').hide();
    })
    $('#vo_opttwo_cancel').on('click', function () {
        $('.vo-opttwo-modal').hide();
    })

    // opt3 modal click   
    $('#vo_optthree_confirm').on('click', function () {
        $('.vo-optthree-modal').hide();
    })
    $('#vo_optthree_cancel').on('click', function () {
        $('.vo-optthree-modal').hide();
    })

    // opt4 modal click   
    $('#vo_optfour_confirm').on('click', function () {
        $('.vo-optfour-modal').hide();
    })

    //modal 5
    $('#vo_optfive_cancel').on('click', function () {
        $('.vo-optfive-modal').hide();
    })
    $('#vo_optfive_confirm').on('click', function () {
        $('.vo-optfive-modal').hide();
    })
    $('.optfive-modal-add-myor').on('click', function () {
        $('.vo-optfive-modal').hide();
        $('.vo-optsix-modal').show();
    })
    $('#five-modal-findJoin').on('focus', function () {
        $('.vo-optfive-body .select-type').show();
    })
    $('.five-modal-select-item').on('click', function () {
        $('.vo-optfive-body .select-type').hide();
    })

    //modal 6
    $('.modal-six-org-type').on('focus', function () {
        $('.vo-optsix-modal .select-type').show();
    })
    $('.six-modal-select-item').on('click', function () {
        $('.vo-optsix-modal .select-type').hide();
    })
    $('#vo_optsix_cancel').on('click', function () {
        $('.vo-optsix-modal').hide();
        $('.vo-optfive-modal').show();
    })
    $('#vo_optsix_confirm').on('click', function () {
        $('.vo-optsix-modal').hide();
        $('.vo-optsix-modal').hide();
    })

    //modal 7 new project
    $('.seven-org-yearending').on('focus', function () {
        $('.vo-optseven-modal .seven-newproject-deta-selct').show();
    })
    $('.seven-modal-select-item').on('click', function () {
        $('.vo-optseven-modal .seven-newproject-deta-selct').hide();
    })
    $('#vo_optseven_cancel').on('click', function () {
        $('#vo-optseven-modal').hide();
    })

    $('.optseven-create-help').on('click', function () {
    })
    //modal 8 new project
    $('#vo_opteight_cancel').on('click', function () {
        $('#vo-opteight-modal').hide();
    })
    $('#vo_opteight_confirm').on('click', function () {
        $('#vo-opteight-modal').hide();
    })
    //modal 9 new project
    $('#vo_optnine_cancel').on('click', function () {
        $('#vo-optnine-modal').hide();
    })
    $('#vo_optnine_confirm').on('click', function () {
        $('#vo-optnine-modal').hide();
    })
    //modal 10 new project
    $('.seven-org-yearending').on('focus', function () {
        $('.vo-optten-modal .seven-newproject-deta-selct').show();
    })
    $('#vo_optten_cancel').on('click', function () {
        $('#vo-optten-modal').hide();
    })

    //左上角弹出菜单项点击事件
    $('.header-action-panle .action-item').on('click', function (res) {
        let name = res.target.dataset.name;
        console.log(name)
        $('.header-action-panle').hide();
        switch (name) {
            case 'opt1':
                $('.vo-optone-modal').show();
                break;
            case 'opt2':
                $('.vo-opttwo-modal').show();
                break;
            case 'opt3':
                $('.vo-optthree-modal').show();
                break;
            case 'opt4':
                $('.vo-optfour-modal').show();
                break;
        }

    })
    $('.project-select-input').on('focus', function (res) {
        $('.pro-select-view').show();
    })
}