
$(document).ready(()=>{
    bindClickEvent();
})

//绑定页面的事件
function bindClickEvent(){
    $("#create-modal").on("click",function(){
        $('#vo-modal-mask').show();
    });
    $("#cancel").on("click",function(){
        $('#vo-modal-mask').hide();
    });
    $("#confirm").on("click",function(){
        $('#vo-modal-mask').hide();
        $('.project-list').show();
        $('.project-content').hide();
    });
}