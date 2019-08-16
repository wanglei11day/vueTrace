<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>control</title>
  <link rel="stylesheet" href="catalog/view/javascript/view/common.css">
  <link rel="stylesheet" href="catalog/view/javascript/view/control.css">
  <link rel="stylesheet" href="catalog/view/javascript/view/base.css">
  <link rel="stylesheet" href="catalog/view/javascript/view/jeDate.css">
  <link rel="stylesheet" href="catalog/view/javascript/view/toastr.css">
  <link rel="stylesheet" href="catalog/view/javascript/bootstrap/css/bootstrap.min.css">
  <style>
    .msg-hint{display:none}
  </style>
</head>

<body>
<div class="whole">
  <div class="hearer-view">
    <div class="header-action">
      <div id="SchoolLan" style="display: inline-block;letter-spacing: 1px;">
        <?php echo $joinedSchools[0]['name']; ?>
        <input type="hidden" name="ycid" value="<?php echo $joinedSchools[0]['school_id']; ?>" />
      </div>
      <div class="point-move">
        <span class="point p1"></span>
        <span class="point p2"></span>
        <span class="point p3"></span>
        <div class="header-action-panle">
          <div class="arrow"></div>
          <div class="action-item" data-name="opt1" id="OrganizationDetails">Organization Details</div>
          <div class="action-item" data-name="opt2">Transfer a Single Project</div>
          <div class="action-item" data-name="opt3">Transfer All Project and Stores</div>
          <div class="action-item" data-name="opt4">Leave Organization</div>
        </div>
      </div>
    </div>
    <div class="header-right-view">
      <div class="header-right-c">
        <div class="col-lg-6">
          <div class="input-group">
            <div class="form-control-box"  id="Organizations">
              <input type="text" placeholder="My Organizations..." class="form-control search-ip">
            </div>
            <div class="arrow-box">
              <div class="arrow-1"></div>
            </div>
            <div class="header-input-select" >
              <span class="select-item header-select-item school"> + Organizations</span>
              <div id="schoolName" class="school">
                <?php foreach ($joinedSchools as $item) { ?>
                <span class="school Sname"  name="myspan"  value="<?php echo $item['school_id']; ?>"><?php echo $item['name']; ?></span>
                <?php } ?>

              </div>
            </div>
          </div>
        </div>
        <span>Switch to All Projects</span>
      </div>
    </div>
  </div>
  <div class="project-columns">
    <div class="my-project">
      <div class="header-cell">
        <div class="title">My Projects</div>
        <div class="new-project-btn" id="new-project">New Porject</div>
      </div>
      <div class="project-list" id="projectList">


      </div>
    </div>
    <div class="peoject-manage">
      <div class="header-cell">
        <div class="title">Storefronts</div>
        <div class="new-project-btn">Manage Storefronts</div>
      </div>
      <div class="manage-opt">
        <p class="hint">This organization has no storefronts.</p>
      </div>
    </div>
  </div>
  <!-- modal nav opt1  -->
  <div class="vo-modal-mask vo-optone-modal" id="vo-optone-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optone-body">
        <div class="modal-title">title</div>
        <div class="project-name" id="projectSchool">school Lan</div>
        <p class="project-dec" id="Singapore">Singapore Singapore 61231</p>
        <p class="phone-num">phone Number</p>
        <div class="phone-input-cell">
          <input type="text" value="" class="form-control phone-input" id="phoneCell">
          <div class="modal-submit-btn"  id="UpdatePhone">Update</div>
        </div>
        <div class="vo-modal-footer">
          <div class="confirm" id="vo_optone_confirm">close</div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal nav opt2  -->
  111111111111111111111111
  <div class="vo-modal-mask vo-opttwo-modal" id="vo-opttwo-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-opttwo-body">
        <div class="modal-title">title</div>
        <div class="project-name">Applet provides a simple and efficient application development framework
          and rich components and apis to help developers develop services with native APP experience in
          WeChat.</div>
        <p class="project-dec">This chapter introduces the small program development language, framework,
          ability, debugging and other content, to help developers quickly and comprehensively understand
          all aspects of small program development.</p>
        <p class="phone-num">For more details on the framework, components, and apis, please refer to the
          corresponding reference documentation:</p>
        <div class="phone-input-cell">
          <p class="input-title">project to transfer</p>
          <div class="input-box">
            <select>
              <option value="High School">High School</option>
              <option value="High School">High School</option>
              <option value="High School">High School</option>
              <option value="High School">High School</option>
              <option value="High School">High School</option>
            </select>
          </div>
        </div>
        <div class="phone-input-cell phone-input-cell-c2">
          <p class="input-title">Email addres of existing account</p>
          <input type="text" value="" class="form-control phone-input">
        </div>
        <div class="vo-modal-footer">
          <div class="i-need-help">I need help</div>
          <div class="footer-rg-right">
            <div class="cancel" id="vo_opttwo_cancel">Cancel</div>
            <div class="confirm" id="vo_opttwo_confirm">close</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal nav opt3  -->
  <div class="vo-modal-mask vo-optthree-modal" id="vo-optthree-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optthree-body">
        <div class="modal-title">title</div>
        <div class="project-name">Applet provides a simple and efficient application development framework
          and rich components and apis to help developers develop services with native APP experience in
          WeChat.
          This chapter introduces the small program development language, framework,
          ability, debugging and other content, to help developers quickly and comprehensively understand
          all aspects of small program development.
        </div>
        <div class="projects">
          <span class="sp1">Projects:</span>
          <span class="sp2">Year 7, Year 8</span>
        </div>
        <div class="stores">
          <span class="sp1">Stores:</span>
          <span class="sp2">None</span>
        </div>
        <div class="phone-input-cell phone-input-cell-c2">
          <p class="input-title">Email addres of existing account</p>
          <input type="text" value="" class="form-control phone-input">
        </div>
        <div class="vo-modal-footer">
          <div class="i-need-help">I need help</div>
          <div class="footer-rg-right">
            <div class="cancel" id="vo_optthree_cancel">Cancel</div>
            <div class="confirm" id="vo_optthree_confirm">close</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal nav opt4  -->
  <div class="vo-modal-mask vo-optfour-modal" id="vo-optfour-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optfour-body">
        <div class="modal-title">title</div>
        <div class="project-name">Applet provides a simple and efficient application development framework
          and rich components and apis to help developers develop services with native APP experience in
          WeChat.
          This chapter introduces the small program development language, framework
        </div>
        <div class="vo-modal-footer">
          <div class="footer-rg-right">
            <div class="confirm" id="vo_optfour_confirm">Okay，got it</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal 5 -->
  <div class="vo-modal-mask vo-optfive-modal" id="vo-optfive-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optfive-body">
        <div class="content">
          <div class="lf-view">
            <p>Find and join your organization:</p>
            <div class="input-box">
                <div class="input-group"  style="width: 391px;margin-top: 3px;height: 35px;line-height: 35px;">
                  <input type="text" class="form-control"placeholder="organization" / >
                  <span class="input-group-btn">
                     <button type="submit" class="btn btn-info btn-search glyphicon glyphicon-search" style="margin-top: -1px;"></button>
                  </span>
                </div>
            </div>
          </div>
          <div class="rg-view">
            <div class="img-box"><img src="catalog/view/javascript/assets/control-modal-5.png" alt=""></div>
            <div class="text-dec">Applet provides a simple and efficient application development
              frameworkand rich components help developers develop services with native APP
              experience inWeChat.<a class="here">here</a>
            </div>
          </div>
        </div>
        <div class="vo-modal-footer">
          <div class="i-need-help optfive-modal-add-myor">I can't find my organization</div>
          <div class="footer-rg-right">
            <div class="cancel" id="vo_optfive_cancel">Cancel</div>
            <div class="confirm" id="vo_optfive_confirm">Next</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal 6 -->
  <div class="vo-modal-mask vo-optsix-modal" id="vo-optsix-modal">
    <div class="vo-modal" id="createProject-modal">
      <form action="javascript:;" method="post">
         <div class="vo-modal-body vo-optsix-body">
        <div class="modal-title">Add Your Organization</div>
        <P class="title-aid">Tell us a little about your organization</P>
        <div class='con-cell-list'>
          <div class="con-cell con-cell-l1">
            <p>Organization Name</p>
            <input type="text" name="org-name" class="" value="" id="org-name">
          </div>

          <div class="con-cell con-cell-l1">
            <p>Address</p>
            <input type="text" name="org-city" class="" value="" id="org-city">
          </div>

          <div class="con-cell con-cell-l3">
            <p>Organization Type</p>
            <div class="input-box" >
              <select name="org_type" id="OrganizationType">
                <?php foreach ($bookTypes as $id=>$item) { ?>
                <option value="<?php echo $id; ?>"><?php echo $item; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="con-cell con-cell-l4">
            <p>Phone Number</p>
            <div class="input-box">
              <input type="text" name="org-phone" class="" value="" id="org-phone">
              <span>So we can provide the best service,please provide a valid phone number</span>
            </div>
          </div>
          <div class="vo-modal-footer" style="padding-top: 76px;">
            <div class="footer-rg-right">
              <div class="cancel" id="vo_optsix_cancel">Go back</div>
              <div class="confirm" id="vo_optsix_confirm" onclick="commitcontact()">Add</div>
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
  <!-- modal 7 editor my project -->
  <div class="vo-modal-mask vo-optseven-modal" id="vo-optseven-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optseven-body">
        <div class="modal-title">Start New Project</div>
        <P class="title-aid">Please tell us about your new Project</P>
        <div class='con-cell-list'>
          <div class="con-cell-checked con-cell-checked-l1 radio radio-info radio-inline">
            <input name="BlankProject" type="radio" value="0" class="check-text" id="Blank" checked="checked"/><label>Create Blank Project </label>
          </div>
          <div class="con-cell-checked con-cell-checked-l2 radio radio-inline" style="margin-left: 0px;">
            <input name="BlankProject" type="radio" value="1" class="check-text" id="ExistingProject"/><label>Create From Existing Project </label>

            <div class="create-help optseven-create-help">
              <div class="help-icon-box">
                <img src="catalog/view/javascript/assets/control-7-help.png" alt="">
                <div class="create-help-panle">
                  <div class="arrow"></div>
                  <div class="action-item" data-name="opt1">Organization Details</div>
                  <div class="action-item" data-name="opt2">Transfer a Single Project</div>
                  <div class="action-item" data-name="opt3">Transfer All Project and Stores
                  </div>
                  <div class="action-item" data-name="opt4">Leave Organization</div>
                </div>
              </div>

            </div>
          </div>
          <div class="con-cell con-cell-l1 " id="BookType" style="display:none;">
            <div class="input-box">
              <select id="booktapexist">

              </select>
            </div>
          </div>
          <div class="con-cell con-cell-l1">
            <p>Book Name</p>
            <input type="text" name="org-blankname" class="" id="BlankName">
            <div class="msg-hint msg-hint1">×Required</div>
          </div>
          <div class="con-cell con-cell-l1">
            <p>Book Type</p>
            <div class="input-box">
              <select id="fukusyaNendo">
                <option value="0">High School</option>
                <option value="1">Middle</option>
                <option value="2">Elementary</option>
                <option value="3">K-8 School</option>
                <option value="4">K-12 School</option>
                <option value="5">College/University</option>
              </select>
            </div>
          </div>
          <div class="con-cell con-cell-l2">
            <p>Year Ending</p>
            <div class="input-box">
              <select id="yearEnding">
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
              </select>
            </div>
          </div>
          <div class="con-cell con-cell-l3">
            <p>Estimated Page Quantity</p>
            <input type="text" name="org-epage" class="" id="org-epage" value="">
            <div class="msg-hint msg-hint2">×Required</div>
          </div>
          <div class="con-cell con-cell-l3">
            <p>Estimated Book Quantity</p>
            <input type="text" name="org-ebook" class="" id="org-ebook" value="">
            <div class="msg-hint msg-hint3">×Required</div>
          </div>
          <div class="con-cell con-cell-l4">
            <p>Estimated Order Date</p>
            <div class="input-box">
              <input type="text" placeholder="Select date..." name="org-type"
                     class="modal-six-org-type" id="date" value="">
              <div class="date-icon-box">
                <img src="catalog/view/javascript/assets/control-7-date.png" alt="">
              </div>
            </div>
            <div class="msg-hint msg-hint4">×Required</div>
          </div>
          <div class="book-preview-view">
            <div class="title">Yearbook Size</div>
            <div class="title-aid">This size CANNOT be changed later</div>
            <div class="book-icon-preview">
              <div class="icon-type-item icon-type-item-1">
                <img src="catalog/view/javascript/assets/control-7-book-max.png" alt="">
                <div class="icon-check-cell">
                  <div class="icon-check-box radio radio-info radio-inline" style="border:none;">
                    <input name="Yearbook" type="radio" value="0" class="check-text" checked="checked"/><label></label>
                  </div>
                </div>
              </div>
              <div class="icon-type-item">
                <img src="catalog/view/javascript/assets/control-7-book-min.png" alt="">
                <div class="icon-check-cell">
                  <div class="icon-check-box radio  radio-inline" style="border:none;">
                    <input name="Yearbook" type="radio" value="1" class="check-text" /><label></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="vo-modal-footer">
            <div class="footer-rg-right">
              <div class="cancel" id="vo_optseven_cancel">Cancel</div>
              <div class="confirm" id="vo_optseven_confirm" onclick="addProject()">Save</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal 8 -->
  <div class="vo-modal-mask vo-opteight-modal" id="vo-opteight-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-opteight-body">
        <h4 class="modal-title">Change Project Organization</h4>
        <P class="title-aid">Choose an organization you already belong to. This project will be associated with your chosen organization. To add an organization to this list, close this window and join the organization from the "My Organizations" list in the organization view.</P>
        <div class='con-cell-list'>
          <div class="con-cell con-cell-l1">
            <p>Move project to the following organization</p>
            <div class="input-box">
              <select id="following">
                <?php foreach ($joinedSchools as $item) { ?>
                <option value="<?php echo $item['school_id']; ?>"><?php echo $item['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="vo-modal-footer">
            <div class="footer-rg-right">
              <div class="cancel" id="vo_opteight_cancel">Cancel</div>
              <div class="confirm editProject" id="vo_opteight_confirm" >Save</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal 9 -->
  <div class="vo-modal-mask vo-optnine-modal" id="vo-optnine-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optnine-body">
        <h4 class="modal-title">Are you sure?</h4>
        <P class="title-aid">You are about to delete the project <em id="projectedit" style="display:inline-block;font-size: 26px;font-style: normal;">test1</em>. This cannot be undone.</P>
        <div class="vo-modal-footer">
          <div class="footer-rg-right">
            <div class="cancel" id="vo_optnine_cancel">Cancel</div>
            <div class="confirm deleteProject btn-danger" id="vo_optnine_confirm">Delete</div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- modal 10 editor my project -->
  <div class="vo-modal-mask vo-optten-modal" id="vo-optten-modal">
    <div class="vo-modal" id="createProject-modal">
      <div class="vo-modal-body vo-optten-body">
        <div class="modal-title">Copy Project '<em id="projectcopy" style="display:inline-block;font-size: 22px;font-style: normal;">test1</em>'</div>
        <P class="title-aid">Please tell us about your new project</P>
        <div class='con-cell-list'>
          <div class="con-cell con-cell-l1">
            <p>Book Name</p>
            <input type="text" name="org-blankname" class="" value="" id="BookNameCopy">
            <div class="msg-hint msg-hint6">×Required</div>
          </div>
          <div class="con-cell con-cell-l2">
            <p>Year Ending</p>
            <div class="input-box">
              <select id="yearEndingCopy" style="    width: 40%;">
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
              </select>
            </div>
          </div>
          <div class="con-cell con-cell-l3">
            <p>Estimated Page Quantity</p>
            <input type="text" name="org-epage" class="" id="orgepageCopy" value="">
            <div class="msg-hint msg-hint7">×Required</div>
          </div>
          <div class="con-cell con-cell-l3">
            <p>Estimated Book Quantity</p>
            <input type="text" name="org-ebook" class="" id="orgebookCopy" value="">
            <div class="msg-hint msg-hint8">×Required</div>
          </div>
          <div class="con-cell con-cell-l4">
            <p>Estimated Order Date</p>
            <div class="input-box">
              <input type="text" placeholder="Select date..." name="org-type"
                     class="modal-six-org-type" id="dateCopy" value="">
              <div class="date-icon-box">
                <img src="catalog/view/javascript/assets/control-7-date.png" alt="">
              </div>
            </div>
            <div class="msg-hint msg-hint9">×Required</div>
          </div>
          <div class="book-preview-view">
            <div class="title">Yearbook Size</div>
            <div class="title-aid">This size CANNOT be changed later</div>
            <div class="book-icon-preview">
              <div class="icon-type-item icon-type-item-1">
                <img src="catalog/view/javascript/assets/control-7-book-max.png" alt="">
                <div class="icon-check-cell">
                  <div class="icon-check-box">
                  </div>
                </div>
              </div>
              <div class="icon-type-item">
                <img src="catalog/view/javascript/assets/control-7-book-min.png" alt="">
                <div class="icon-check-cell">
                  <div class="icon-check-box">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="vo-modal-footer">
            <div class="footer-rg-right">
              <div class="cancel" id="vo_optten_cancel">Cancel</div>
              <div class="confirm copyProject" id="vo_optten_confirm" >Save</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="catalog/view/javascript/view/js/jquery.min.js"></script>
<script src="catalog/view/javascript/view/js/bootstrap.min.js"></script>
<script src="catalog/view/javascript/view/js/jedeta.js"></script>
<script src="catalog/view/javascript/view/js/control.js"></script>
<script src="catalog/view/javascript/view/js/toastr.min.js"></script>

<script type="text/javascript">
  $('#ExistingProject').on('click',function(){
    $('#BookType').css('display','block');
  })
  $('#Blank').on('click',function(){
    $('#BookType').css('display','none');
  })

  // 添加学校
  function commitcontact(){
    var org_type = "";
    $("select[name='org_type']").each(function (index) {
      if($.trim($(this).val())==null||$.trim($(this).val())==""){
        err=1;
      }
      org_type+=$(this).val();
    });
    // 机构名称
    var name=$("#org-name").val();

    if($.trim(name)==""||$.trim(name)==null){
      toastr.error('请输入机构名称');return;
    }
    var address = $('#org-city').val();
    if($.trim(address) == "" || $.trim(address) == null){
      toastr.error('请输入地址');
    }

    // 手机号
    var Phone = $("#org-phone");
    var Phone_reg = /^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8])|(19[7]))\d{8}$/;
    Phone.blur(function(){
      if(Phone_reg.test(Phone.val())){
        $("#org-phone").val();
      }else{
        toastr.error('请输入正确的手机号');
        $("#org-phone").val("");
      }
    })
    var telephone = $("#org-phone").val();

    $.ajax({
      url:'index.php?route=account/school/add',
      data:{name:name,telephone:telephone,address:address,org_type:org_type},
      type: 'post',
      dataType: 'json',  // 请求方式为jsonp
      success:function(data){
        if(data.status==1){
          toastr.success("创建成功");
        }
      }
    })
  }

  // 全局变量
  let school_id
  school_id = $("input:hidden[name='ycid']").val();

  //默认获取的项目
  // 获取我的项目
  $.ajax({
    url: 'index.php?route=account/project/projectList',
    data:{school_id:school_id},
    type: 'get',
    dataType: 'json',  // 请求方式为jsonp
    success: function (data) {
      var result = data.data
      var value = '';
      var arr = '';
      var project_id ;   //项目id
      var project_name ;   //项目name
      for(var i = 0; i < result.length; i++){
        var year = parseInt(result[i].year) + 1;
        value += ' <div class="project-item" data-index='+i+' data-project_id = '+ result[i].project_id+' data-project_name = '+ result[i].name+'>\n' +
                '          <div class="project-thumb"><img src="" alt=""></div>\n' +
                '          <div class="project-dec">\n' +
                '            <p>'+result[i].name+'</p>\n' +
                '            <span style="font-size:12px;">'+result[i].year+' - '+year+'</span>\n' +
                '          </div>\n' +
                '          <div class="c3 f12 p20 project-item-panle project-i-panle-'+i+'">\n' +
                '            <div class="list-box">\n' +
                '              <div class="arrow"></div>\n' +
                '              <div class="item mb5 editProjects">Edit</div>\n' +
                '              <div class="item mb5 Change" id="Change">Change Organization</div>\n' +
                '              <div class="item mb5 projectCopy">Copy Project</div>\n' +
                '              <span class="line pb10 mb10"></span>\n' +
                '              <div class="item projectDelete">Delete</div>\n' +
                '            </div>\n' +
                '          </div>\n' +
                '        </div>' ;


      }
      for(var i = 0; i < result.length; i++){
        arr += '<option value="year">'+result[i].name+'</option>';
      }
      $("#projectList").html(value);
      $("#booktapexist").html(arr);
      $('.project-item').on('click', function (res) {
        let index = res.currentTarget.dataset.index;
        project_id = res.currentTarget.dataset.project_id;
        project_name = res.currentTarget.dataset.project_name;
        $('.project-item-panle').hide();
        if ($('.project-i-panle-' + index + '').is(':hidden')) {
          $('.project-i-panle-' + index + '').show();
        }
        else {
          $('.project-i-panle-' + index + '').hide();
        }
      })
      $('.Change').click(function (res) {
        $('.vo-opteight-modal').show();
      })
      $('.projectCopy').click(function (res) {
        $('.vo-optten-modal').show();
        $("#projectcopy").html(project_name);
      })
      $('.copyProject').click(function() {
        // 机构名称
        var name=$("#BookNameCopy").val();
        var year=$("#yearEndingCopy").val();
        var page=$("#orgepageCopy").val();
        var book=$("#orgebookCopy").val();
        var date=$("#dateCopy").val();
        if(name == ""){
           $('.msg-hint6').show();
        }else if(page==""){
          $('.msg-hint7').show();
        }else if(book==""){
          $('.msg-hint8').show();
        }else if(date==""){
          $('.msg-hint9').show();
        }else{
          console.log(1)
          $('#vo_optten_confirm').on('click', function () {
            $('#vo-optten-modal').hide();
          })
          $.ajax({
            url:'index.php?route=account/project/copy',
            data:{project_id:project_id,year:year,pages:page,qty:book,order_date:date,name:name,school_id:school_id},
            type: 'post',
            dataType: 'json',  // 请求方式为jsonp
            success:function(data){
              if(data.status==1){
                toastr.success("复制成功");
                window.location.reload()//刷新当前页面.
              }
            }
          })
        }

      })
      $('.projectDelete').click(function (res) {
        $('.vo-optnine-modal').show();
        $("#projectedit").html(project_name);
      })
      $('.editProject').click(function() {
        var school_id=$("#following").val();
        $.ajax({
          url:'index.php?route=account/project/changeOrg',
          data:{project_id:project_id,school_id:school_id},
          type: 'post',
          dataType: 'json',  // 请求方式为jsonp
          success:function(data){
            if(data.status==1){
              toastr.success("修改成功");
              window.location.reload()//刷新当前页面.
            }
          }
        })
      })
      $('.deleteProject').click(function() {
        $.ajax({
          url:'index.php?route=account/project/delete',
          data:{project_id:project_id},
          type: 'get',
          dataType: 'json',  // 请求方式为jsonp
          success:function(data){
            if(data.status==1){
              toastr.success("删除成功");
              window.location.reload()//刷新当前页面.
            }
          }
        })
      })
      $('.editProjects').click(function() {
        window.location.href = "index.php?route=account/project&project_id="+project_id;
      })
    },
    error: function () {

    }
  })
  $('#schoolName').on('click', '.Sname', function () {
    let text = $(this).text();
    school_id = $(this).attr('value')
    $('#SchoolLan').html(text);
    // 获取我的项目
    $.ajax({
      url: 'index.php?route=account/project/projectList',
      data:{school_id:school_id},
      type: 'get',
      dataType: 'json',  // 请求方式为jsonp
      success: function (data) {
        var result = data.data
        var value = '';
        var project_id ;   //项目id
        var project_name ;   //项目name
        for(var i = 0; i < result.length; i++){
          var year = parseInt(result[i].year) + 1;
          value += ' <div class="project-item" data-index='+i+' data-project_id = '+ result[i].project_id+' data-project_name = '+ result[i].name+'>\n' +
                  '          <div class="project-thumb"><img src="" alt=""></div>\n' +
                  '          <div class="project-dec">\n' +
                  '            <p>'+result[i].name+'</p>\n' +
                  '            <span>'+result[i].year+' - '+year+'</span>\n' +
                  '          </div>\n' +
                  '          <div class="c3 f12 p20 project-item-panle project-i-panle-'+i+'">\n' +
                  '            <div class="list-box">\n' +
                  '              <div class="arrow"></div>\n' +
                  '              <div class="item mb5 editProjects">Edit</div>\n' +
                  '              <div class="item mb5 Change" id="Change">Change Organization</div>\n' +
                  '              <div class="item mb5 projectCopy">Copy Project</div>\n' +
                  '              <span class="line pb10 mb10"></span>\n' +
                  '              <div class="item projectDelete">Delete</div>\n' +
                  '            </div>\n' +
                  '          </div>\n' +
                  '        </div>' ;
        }
        $("#projectList").html(value);
        $('.project-item').on('click', function (res) {
          let index = res.currentTarget.dataset.index;
          project_id = res.currentTarget.dataset.project_id;
          project_name = res.currentTarget.dataset.project_name;
          $('.project-item-panle').hide();
          if ($('.project-i-panle-' + index + '').is(':hidden')) {
            $('.project-i-panle-' + index + '').show();
          }
          else {
            $('.project-i-panle-' + index + '').hide();
          }
        })
        $('.Change').click(function (res) {
          $('.vo-opteight-modal').show();
        })
        $('.projectCopy').click(function (res) {
          $('.vo-optten-modal').show();
          $("#projectcopy").html(project_name);
        })
        $('.copyProject').click(function() {
          // 机构名称
          var name=$("#BookNameCopy").val();
          if($.trim(name)==""||$.trim(name)==null){
            toastr.error('请输入book name');return;
          }
          var year=$("#yearEndingCopy").val();
          var page=$("#orgepageCopy").val();
          var book=$("#orgebookCopy").val();
          var date=$("#dateCopy").val();
          $.ajax({
            url:'index.php?route=account/project/copy',
            data:{project_id:project_id,year:year,pages:page,qty:book,order_date:date,name:name,school_id:school_id},
            type: 'post',
            dataType: 'json',  // 请求方式为jsonp
            success:function(data){
              if(data.status==1){
                toastr.success("复制成功");
                window.location.reload()//刷新当前页面.
              }
            }
          })
        })
        $('.projectDelete').click(function (res) {
          $('.vo-optnine-modal').show();
          $("#projectedit").html(project_name);
        })
        $('.editProject').click(function() {
          var school_id=$("#following").val();
          $.ajax({
            url:'index.php?route=account/project/changeOrg',
            data:{project_id:project_id,school_id:school_id},
            type: 'post',
            dataType: 'json',  // 请求方式为jsonp
            success:function(data){
              if(data.status==1){
                toastr.success("修改成功");
                window.location.reload()//刷新当前页面.
              }
            }
          })
        })
        $('.deleteProject').click(function() {
          $.ajax({
            url:'index.php?route=account/project/delete',
            data:{project_id:project_id},
            type: 'get',
            dataType: 'json',  // 请求方式为jsonp
            success:function(data){
              if(data.status==1){
                toastr.success("删除成功");
                window.location.reload()//刷新当前页面.
              }
            }
          })
        })
        $('.editProjects').click(function() {
          window.location.href = "index.php?route=account/project&project_id="+project_id;
        })
      },
      error: function () {

      }
    })
  })

  // 添加项目
  function addProject(){
    var select = $("input[name='BlankProject']:checked").val();
    // if(select == 0){
    // 机构名称
    var name=$("#BlankName").val();
    var type=$("#fukusyaNendo").val();
    var year=$("#yearEnding").val();
    var Page=$("#org-epage").val();
    var Book =$("#org-ebook").val();
    var date =$("#date").val();
    var size = $("input[name='Yearbook']:checked").val();
    if(name == ""){
      $('.msg-hint1').show();
    }else if(Page==""){
      $('.msg-hint2').show();
    }else if(Book==""){
      $('.msg-hint3').show();
    }else if(date=="") {
      $('.msg-hint4').show();
    }else{
      $('#vo_optseven_confirm').on('click', function () {
        $('#vo-optseven-modal').hide();
      })
      $.ajax({
        url:'index.php?route=account/project/add',
        data:{name:name,type:type,year:year,pages:Page,qty:Book,order_date:date,size:size,school_id:school_id},
        type: 'post',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          if(data.status==1){
            toastr.success("创建成功");
            window.location.reload()//刷新当前页面.
          }
        }
      })
    }

    // }

  }
  // 获取学校信息
  $('#OrganizationDetails').on('click',function() {
    var name ;
    var school_id ;
    $.ajax({
      url: 'index.php?route=account/school/get',
      type: 'get',
      dataType: 'json',  // 请求方式为jsonp
      data:{school_id:5866},
      success: function (data) {
        if(data.status==1){
          console.log(data.data)
          var result = data.data;
          name = result.name;
          school_id = result.school_id;
          $('#projectSchool').html(result.name);
          $('#Singapore').html(result.address);
          $('#phoneCell').val(result.telephone);
        }
      }
    })
    //编辑学校
    $('#UpdatePhone').on('click',function() {
      var telephone = $('#phoneCell').val();
      console.log(telephone);
      console.log(school_id,name);
      $.ajax({
        url: 'index.php?route=account/school/modifyName',
        type: 'post',
        dataType: 'json',  // 请求方式为jsonp
        data:{school_id:school_id,name:name,telephone:telephone},
        success: function (data) {
          console.log(data)
          if(data.status==1){
            console.log(data.data)

          }
        }
      })
    })

  })

</script>
<script>
    var enLang = {
        name: "en",
        month: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
        weeks: ["SUN", "MON", "TUR", "WED", "THU", "FRI", "SAT"],
        times: ["Hour", "Minute", "Second"],
        timetxt: ["Time", "Start Time", "End Time"],
        backtxt: "Back",
        clear: "Clear",
        today: "Now",
        yes: "Confirm",
        close: "Close"
    }
    jeDate("#date", {
        // language:enLang,
        format: "YYYY/MM/DD"
    });
    jeDate("#dateCopy", {
      // language:enLang,
      format: "YYYY/MM/DD"
    });
</script>

</body>

</html>