<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>dashboard</title>
  <link rel="stylesheet" href="catalog/view/javascript/project/common.css">
  <link rel="stylesheet" href="catalog/view/javascript/project/base.css">
  <link rel="stylesheet" href="catalog/view/javascript/project/zl.css">
  <link rel="stylesheet" href="catalog/view/javascript/view/toastr.css">
  <link rel="stylesheet" href="catalog/view/javascript/bootstrap/css/bootstrap.min.css">
  <style>
    .ReadyGreen{    color: #fff !important;
      font-weight: 500;
      font-size: 10px;
      border-radius: 4px;
      padding: 0 5px;
      text-transform: uppercase;
      display: none;
    }

    .uploadifive-queue-item {
      background-color: #F5F5F5;
      border-bottom: 1px dotted #D5D5D5;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      font: 12px Arial, Helvetica, Sans-serif;
      margin: 10px 0 0;
      padding: 15px;
    }
    .uploadifive-button {
      height: 34px !important;
      line-height: 34px !important;
      overflow: hidden !important;
      position: relative !important;
      text-align: center!important;
      width: 100px !important;
      font-weight: 500 !important;
      border-radius: 4px !important;
      background: #00b5e2 !important;
      border: 1px solid #00b5e2 !important;
      outline: none !important;
      margin: 0 5px 0 5px !important;
      color: #fff !important;
      background: none;
      cursor:pointer;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      border-radius: 2px;
      border: 1px solid #d9d9d9;
      color: #333;
      font: 14px Arial, Helvetica, sans-serif;
      text-align: center;
      text-transform: uppercase;
      width: 100%;
      float:left;
      transition:all 0.3s ease;
    }
    html input[type=button]{
      display: inline-block;
      font-weight: 500;
      padding: 1px 11px;
      border-radius: 4px;
      background: #00b5e2;
      border: 1px solid #00b5e2;
      outline: none;
      margin: 6px 5px 0 5px;
      color: #fff;
    }
    .uploadifive-button input{
      cursor:pointer;

    }
    .uploadifive-button:hover,
    #clean-up:hover{
      box-shadow:0 0 5px rgba(0,0,0,.3);
    }
    .uploadifive-queue-item {
      background-color: #F5F5F5;
      border-bottom: 1px dotted #D5D5D5;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      font: 12px Arial, Helvetica, Sans-serif;
      margin: 10px 0 0;
      padding: 15px;
    }
    .uploadifive-queue-item .close {
      background: url('/res/manage/default/statics/images/upload_del.png') 0 0 no-repeat;
      display: block;
      float: right;
      height: 16px;
      text-indent: -9999px;
      width: 16px;
    }
    .uploadifive-queue-item .progress {
      border: 1px solid #e9e8e8;
      height: 5px !important;
      margin-top: 5px !important;
      width: 100%;
      background:#fff;
      border-radius:24px;
      overflow:hidden;
      transition:none;
    }
    .uploadifive-queue-item .progress-bar {
      background-color: #32a4e0;
      height: 5px !important;
      width: 0;
      transition:none;
    }
    .uploadifive-queue-item.error .fileinfo{
      color: #ff0000;
    }
    .fileinfo {
      color: green;
      line-height: 16px;
      float: right;
      margin: 0 15px 0 0;
    }
    .filesize {
      vertical-align: middle;
      line-height: 16px;
    }
    .filename {
      /*max-width: calc(100% - 140px);*/
      display: inline-block;
      vertical-align: middle;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      vertical-align: middle;
      line-height: 16px;
    }
  </style>
</head>

<body style="background-color: #ccc">
<div class="wrap1">
  <div class="top-bar tr">
    <label for="">Take a tour!</label>
  </div>
  <div class="flex">
    <div class="body-left flex1">

      <div class="body-left-wrap2 bg-f mt15 slider-item-view">
        <div class="pl20 pr20" style="line-height: 52px;">
          <p class="title-bar">Covers</p>
        </div>
        <div class="covers-item item-slider">
          <div class="item-h flexV-sb p0_20 show-slider-panle" data-index="0">
            <div class="flexV">
              <div class="img-box"></div>
              <p class="f13 c028 ml5">Hardcoves</p>
            </div>
            <div class="flexV">
              <div class="tool flexV-sb">
                <div class="shezhi-btn-box" data-index="0">
                  <img class="shezhi-btn" src="catalog/view/javascript/assets/setting.png" alt="">
                  <div class="setting-action-panle setting-action-panle-p0">
                    <div class="arrow"></div>
                    <div class="action-item" data-name="opt1">Organization Details</div>
                    <div class="action-item" data-name="opt2">Transfer a Single Project</div>
                    <div class="action-item" data-name="opt3">Transfer All Project and Stores
                    </div>
                    <div class="action-item" data-name="opt4">Leave Organization</div>
                  </div>
                </div>
                <span class="arrow-1"></span>
              </div>
            </div>
          </div>
          <div class="item-b item-slider-content p0_20 item-slider-content-c0 show-vo-optone-body">
            <div class="flexV-sb f12 c3">
              <p>Assigned to: Mac Zhao</p>
              <p>Updated: 3/14/2019 3:34</p>
            </div>
            <div class="flexV-sb mt10">
              <div class="img-list">
                <div class="img-box1"></div>
                <div class="img-box1"></div>
              </div>
              <div style="width: 180px">
                <p class="f13 f3 fb">Print hardcoves</p>
                <p class="fi f12 c6">Note:you can't change this once the book is for sale in your
                  store storefront</p>
              </div>
            </div>
          </div>
        </div>
        <div class="covers-item item-slider">
          <div class="item-h flexV-sb p0_20 show-slider-panle" data-index="1">
            <div class="flexV">
              <div class="img-box"></div>
              <p class="f13 c028 ml5">Hardcoves</p>
            </div>
            <div class="flexV">
              <div class="tool flexV-sb">
                <div class="shezhi-btn-box" data-index="1">
                  <img class="shezhi-btn" src="catalog/view/javascript/assets/setting.png" alt="">
                  <div class="setting-action-panle setting-action-panle-p1">
                    <div class="arrow"></div>
                    <div class="action-item" data-name="opt1">Organization Details</div>
                    <div class="action-item" data-name="opt2">Transfer a Single Project</div>
                    <div class="action-item" data-name="opt3">Transfer All Project and Stores
                    </div>
                    <div class="action-item" data-name="opt4">Leave Organization</div>
                  </div>
                </div>
                <span class="arrow-1"></span>
              </div>
            </div>
          </div>
          <div class="item-b item-slider-content p0_20 item-slider-content-c1 show-vo-optone-body">
            <div class="flexV-sb f12 c3">
              <p>Assigned to: Mac Zhao</p>
              <p>Updated: 3/14/2019 3:34</p>
            </div>
            <div class="flexV-sb mt10">
              <div class="img-list">
                <div class="img-box1"></div>
                <div class="img-box1"></div>
              </div>
              <div class="" style="width: 180px">
                <p class="f13 f3 fb">Print hardcoves</p>
                <p class="fi f12 c6">Note:you can't change this once the book is for sale in your
                  store storefront</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="body-left-wrap3 bg-f mt15 slider-item-view">
        <div class="flexV-sb p20_10 pl20 pr20">
          <div class="flexV">
            <p class="title-bar">Sections</p>
          </div>
          <div>
            <span class="lf-text">Section / Page Manager</span>
            <button class="btn-bar mr10 request-proof">Request Proof</button>
            <button class="btn-bar new-section">New Section</button>
          </div>
        </div>
        <ul id="bar" class="ui-sortable">
          <?php foreach ($sections as $section){ ?>
          <li class="covers-item item-slider covers-items-<?php echo $section['section_id'];?>" id="image-list-702" data-id="<?php echo $section['section_id'];?>" >
            <div class="item-h flexV-sb p0_20 show-slider-panle" data-index="<?php echo $section['section_id'];?>">
              <div class="flexV" id="lists">
                <img style="margin-left:-8px;width:20px;height:20px;margin-right: 10px;" src="catalog/view/javascript/assets/jian.png" alt="">
                <div class="img-box"></div>
                <img style="margin-left:8px" src="catalog/view/javascript/assets/person-icon.png" alt="">
                <p class="f13 c028 ml5" style="margin:7px;"><?php echo $section['name'];?></p>
                <span class="ReadyGreen ReadyGreen-<?php echo $section['section_id'];?>" data-index="<?php echo $section['section_id'];?>" style="margin-left: 10px;    background-color: #6cc302;">Ready for Review</span>
              </div>
              <div class="flexV">
                <span style="margin-right:6px;font-size:12px"><?php echo $section['pages'];?> pages(1-10),<?php echo $section['blanks'];?> blank</span>
                <div class="tool flexV-sb">
                  <div class="shezhi-btn-box" data-index="<?php echo $section['section_id'];?>">
                    <img class="shezhi-btn" src="catalog/view/javascript/assets/setting.png" alt="">
                    <div class="setting-action-panle setting-action-panle-p-<?php echo $section['section_id'];?>">
                      <div class="arrow"></div>
                    <div class="action-item editSection" data-name="opt1"><a href="<?php echo $section['edituri'];?>">Edit</a></div>
                      <div class="action-item editPortrait" data-name="opt1">Edit Portrait Rules</div>
                      <div class="action-item Mark" data-name="opt2" data-index="<?php echo $section['section_id'];?>" section_id="<?php echo $section['section_id'];?>" review="<?php echo $section['review'];?>">Mark as Ready for Review</div>
                      <div class="action-item Lock" data-name="opt3" section_id="<?php echo $section['section_id'];?>" locked="<?php echo $section['locked'];?>" data-index="<?php echo $section['section_id'];?>">Lock</div>
                      <div class="action-item Rename" data-name="opt4" section_id="<?php echo $section['section_id'];?>">Rename</div>
                      <div class="action-item Delete" data-name="opt4" section_id="<?php echo $section['section_id'];?>" section_name="<?php echo $section['name'];?>">Delete</div>
                    </div>
                  </div>
                  <span class="arrow-1"></span>
                </div>
              </div>
            </div>
            <div class="item-b item-slider-content p0_20 item-slider-content-c2-<?php echo $section['section_id'];?>">
              <div class="flexV-sb f12 c3">
                <p><?php echo $section['pages'];?> pages(1-10),<?php echo $section['blanks'];?> blank</p>
                <p>Updated: 3/14/2019 3:34PM</p>

              </div>
              <div class="flexV-sb mt10">
                <div class="img-list show-page-manage-panle">
                  <ul id="barpage" class="ui-sortable ui-sortable-<?php echo $section['section_id'];?>">
                    <?php if($section['content_url']){  ?>
                        <?php foreach ($section['thumbnails'] as $thumbnails){ ?>
                          <li class="covers-item item-slider covers-items-<?php echo $section['section_id'];?>" id="image-list-702" data-id="<?php echo $section['section_id'];?>"  style="float:left;">
                            <div class="img-box1" data-index="<?php echo $section['section_id'];?>">

                              <img src="<?php echo $thumbnails;?>" alt="" style="width: 58px;height: 78px;">
                              <div class="setting-action-panle Add-action-panle-p-<?php echo $section['section_id'];?>">
                                <div class="arrow"></div>
                                <div class="action-item AddPages" data-name="opt1">Add Pages</div>
                                <div class="action-item editPortrait" data-name="opt1">import Pages</div>
                                <div class="action-item Deletesection" data-name="opt4" section_id="<?php echo $section['section_id'];?>">Delete</div>
                              </div>
                            </div>
                          </li>
                        <?php } ?>
                    <?php   }else{     ?>
                          <?php     $pages = $section['pages'];
                            while($pages){   ?>
                              <div class="img-box1"></div>
                          <?php     }     ?>

                    <?php  }   ?>



                    <div class="img-box1"></div>
                  </ul>

                </div>
                <div class="confirm AddPagesProject" id="AddPages" section_id="<?php echo $section['section_id'];?>" style="padding: 6px 10px;background: #00B5E2;font-size: 12px;color: #fff;border-radius: 4px;width: 78px;">Addpages</div>
              </div>

            </div>
          </li>
          <?php } ?>
        </ul>
      </div>

    </div>
    <div class="body-right flex1">

      <div class="body-right-wrap2 bg-f mt15">
        <div class="flexV-sb">
          <div class="flexV">
            <p class="title-bar ">Team</p>
          </div>
          <div>
            <button class="btn-bar mr10" id="email-team">Email Team</button>
            <button class="btn-bar" id="new-team-member">New Team Member</button>
          </div>
        </div>
        <div class="mt15" style="    width: 100%;">
          <div class="flex1" style="display:inline-block;width:33%">
            <p class="f3 f15 fb">Name</p>
          </div>
          <div class="flex1" style="display:inline-block;width:33%">
            <p class="f3 f15 fb">Role</p>
          </div>
          <div class="flex1" style="display:inline-block;width:30%">
            <p class="f3 f15 fb">Sections</p>
          </div>

        </div>

        <?php foreach ($customers as $customers){ ?>10 pages(1-10),9 blank

        <div class="mt15" style="    width: 100%;">
          <div class="flex1" style="display:inline-block;width:100%">

            <p class="f12 f6" style="display:inline-block;width:33%"><img src="catalog/view/javascript/assets/ye.png" alt=""> <img src="catalog/view/javascript/assets/email.png" alt="">
              <?php echo $customers['firstname'];?></p>
            <p class="f12 f6" style="display:inline-block;width:33%"><?php echo $customers['role'];?></p>
            <p class="f12 f6" style="display:inline-block;width:30%"><?php echo $customers['section_id']==0?'All':$customers['section_id'];?></p>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="body-right-wrap3 bg-f mt15">
        <div class="flexV-sb">
          <div class="flexV">
            <p class="title-bar ">Recent Photos</p>
          </div>
          <div>
            <button class="btn-bar" id="manage-photos">Manage Photos</button>
          </div>
        </div>
        <div class="flex-wrap img-wrap mt10">
          <img src="catalog/view/javascript/assets/pic-p.png" alt="" srcset="">
          <img src="catalog/view/javascript/assets/pic-p.png" alt="" srcset="">
          <img src="catalog/view/javascript/assets/pic-p.png" alt="" srcset="">
          <img src="catalog/view/javascript/assets/pic-p.png" alt="" srcset="">
          <img src="catalog/view/javascript/assets/pic-p.png" alt="" srcset="">
          <img src="catalog/view/javascript/assets/pic-p.png" alt="" srcset="">
        </div>
      </div>
      <div class="body-right-wrap4 bg-f mt15">
        <div class="flexV-sb flex-st">
          <div class="left-view">
            <p class="title-bar">Contributed Photos</p>
            <span>(0 pending review)</span>
          </div>
          <div>
            <button class="btn-bar mr10">Request Photos</button>
            <button class="btn-bar">Review Photos</button>
          </div>
        </div>
      </div>
      <div class="body-right-wrap5 bg-f mt15">
        <div class="flexV-sb flex-st">
          <div class="left-view">
            <p class="title-bar">People</p>
          </div>
          <div>
            <button class="btn-bar mr10">Import PSPA</button>
            <button class="btn-bar" id='manage_people'>Manage People</button>
          </div>
        </div>
        <div class="number-view flex mt15">
          <div class="flex1">
            <span class="f3 f15 fb">0</span>
            <p class="f12 f6">People</p>
          </div>
          <div class="flex1">
            <span class="f3 f15 fb">0</span>
            <p class="f12 f6">Missing Photos</p>
          </div>
          <div class="flex1 flex1-right">
            <span class="f3 f15 fb">0</span>
            <p class="f12 f6">Missing Info</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 1 -> Are you sure?  -->
<div class="vo-modal-mask vo-optone-modal" id="vo-optone-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optone-body">
      <div class="modal-title">Are your sure?</div>
      <div class="project-name">Applet provides a simple and efficient application development framework
        and rich components and apis to help developers develop services with native APP experience in
        WeChat.
        This chapter introduces the small program development language, framework
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optone_cancel">cancel</div>
          <div class="confirm" style="background: #CC0000;padding: 2px 6px;font-size: 12px;"
               id="vo_optone_confirm">Continue</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 2 -> Request Project Proof  -->
<div class="vo-modal-mask vo-opttwo-modal" id="vo-opttwo-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-opttwo-body">
      <div class="modal-title">Request Project Proof</div>
      <div class="project-name">Applet provides a simple and efficient application development framework
        and rich components and apis to help developers develop services with native APP experience in
        WeChat.
        This chapter introduces the small program development language, framework
      </div>
      <div class="email-cell">
        <p>addresser entered below:</p>
        <input type="text" value="" name="email" class="email-input">
        <p>Separate email addresses with comma</p>
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_opttwo_cancel">cancel</div>
          <div class="confirm" id="vo_opttwo_confirm">Next</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 3 -> Request Project Proof -next -->
<div class="vo-modal-mask vo-optthree-modal" id="vo-optthree-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optthree-body">
      <div class="modal-title">Request Project Proof</div>
      <div class="check-item flex-st">
        <div class="checked-box">
          <span class="checked-active f-ab-mask"></span>
        </div>
        <span class="check-text">Request Proof for the Entire Yearbook</span>
      </div>
      <div class="check-item flex-st">
        <div class="checked-box">
          <span class="checked-active f-ab-mask"></span>
        </div>
        <span class="check-text">Request Proof for Cover or Section</span>
      </div>
      <div class="select-cell">
        <div class="select-list">
          <select>
            <option value="--Select--">--Select--</option>
            <option value="0">Volvo</option>
            <option value="1">Saab</option>
            <option value="2">Opel</option>
            <option value="3">Audi</option>
          </select>
        </div>
      </div>
      <div class="check-item flex-st" style="margin:4px 0;">
        <div class="checked-box">
          <span class="checked-active f-ab-mask"></span>
        </div>
        <span class="check-text">Request Proof for Pages(s):</span>
      </div>
      <div class="page-select">
        <input type="text" class="page-1">
        <span>to</span>
        <input type="text" class="page-2">
      </div>
      <div class="examples-cell">
        Examples:20 to 20,20 to 25
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optthree_cancel">Back</div>
          <div class="confirm" id="vo_optthree_confirm">Send</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 4 What type of section do you want to create -->
<div class="vo-modal-mask vo-optfour-modal" id="vo-optfour-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optfour-body">
      <div class="modal-title">What type of section do you want to create？</div>
      <div class="text-content">
        <p>Freefrom</p>
        <div class="text-dec">
          A blank slate limited only by your imagination
        </div>
      </div>
      <div class="text-content mt5">
        <p>Protrait</p>
        <div class="text-dec">
          A blank slate limited only by your imaginationA blank slate limited only by your imagination
          A blank slate limited only by your imagination A blank slate limited only by your <a>Guide to
            Protraits</a>
        </div>
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optfour_cancel">Cancel</div>
          <div class="confirm" id="vo_optfour_confirm">Next</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 5 What type of section do you want to create -> next-->
<div class="vo-modal-mask vo-optfive-modal" id="vo-optfive-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optfive-body">
      <div class="modal-title">Add section</div>
      <div class="text-content text-content-1">
        <p>Section Name</p>
        <input type="text" value="任务测试01" name="" id="task" class="" >
      </div>
      <div class="text-content text-content-2">
        <p>Number of Pages</p>
        <input type="text" value="1" name="" id="Pagesnumber" class="">
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optfive_cancel">Cancel</div>
          <div class="confirm back" id="vo_optfive_back">Back</div>
          <div class="confirm" id="vo_optfive_confirm">Finish</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 6  Add Portrait Section -->
<div class="vo-modal-mask vo-optsix-modal" id="vo-optsix-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optsix-body">
      <div class="modal-title">Add Portrait Section</div>
      <div class="text-content text-content-1">
        <p>Section Name</p>
        <input type="text" placeholder="Example:Fifth Grade" value="" name="" id="" class="">
      </div>
      <div class="text-content text-content-2">
        <p>Planned Number of Pages</p>
        <input type="text" value="1" name="" id="" class="">
      </div>
      <div class="text-content text-content-3">
        <p>No portrait information found</p>
        <div class="text-dec">
          This function is used to determine the state of one or more socket interfaces. For each socket
          interface, the caller can query its readability, writability and error status information.
        </div>
      </div>
      <div class="vo-modal-footer flexV-sb">
        <div class="i-guide">Guide to portraits</div>
        <div class="footer-rg-right">
          <div class="confirm back" id="vo_optsix_back">Back</div>
          <div class="confirm" id="vo_optsix_confirm">Finish</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 7  Edit Portrait Rules -->
<div class="vo-modal-mask vo-optseven-modal" id="vo-optseven-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optseven-body">
      <div class="modal-title">Edit Portrait Rules</div>
      <div class="flexV flex-sb">
        <div class="flex1">
          <div class="title-aid">
            Inclued portrait that match the following
          </div>
          <div class="text-content text-content-1">
            <p>Grade is</p>
            <div class="select-list">
              <select>
                <option value="--Select--">--Select--</option>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="opel">Opel</option>
                <option value="audi">Audi</option>
              </select>
            </div>
          </div>
          <div class="text-content text-content-2">
            <p>Teacher is</p>
            <div class="select-list">
              <select>
                <option value="--Select--">--Select--</option>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="opel">Opel</option>
                <option value="audi">Audi</option>
              </select>
            </div>
          </div>
          <div class="text-content text-content-3">
            <p>Homeroom is</p>
            <div class="select-list">
              <select>
                <option value="--Select--">--Select--</option>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="opel">Opel</option>
                <option value="audi">Audi</option>
              </select>
            </div>
          </div>
        </div>
        <div class="flex1 right-view">
          <div class="p-sections">
            <p>0</p>
            <p>Number of people in section</p>
          </div>
        </div>
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optseven_cancel">Cancel</div>
          <div class="confirm" id="vo_optseven_confirm">Save</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 8 Add New Teammate -->
<div class="vo-modal-mask vo-opteight-modal" id="vo-opteight-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-opteight-body">
      <div class="modal-title">Add New Teammate</div>
      <div class="flexV flex-sb">
        <div class="flex1">
          <div class="text-content text-content-1">
            <p>First Name</p>
            <input type="text" value="" class="first-name" id="first-name">
          </div>
          <div class="text-content text-content-2">
            <p>Last Name</p>
            <input type="text" value="" class="first-name" id="last_name">
          </div>
          <div class="text-content text-content-3">
            <p>Email</p>
            <input type="text" value="" class="first-name" id="email">
          </div>
          <div class="text-content text-content-4">
            <p>Role</p>
            <div class="select-list">
              <select id="role">

                <?php echo 123;?>
                <?php foreach ($roles as $role){ ?>
                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="flex1 right-view">
          <div class="x-icon-box">
            <img src="catalog/view/javascript/assets/zhang-icon.png" alt="">
          </div>
          <div class="right-dec">
            This function is used to determine the state of one or more socket interfaces. For each
            socket interface, the caller can query its readability, writability and error status
            information, and use the fd_set structure to represent a group of socket interfaces waiting
            to be checked when the call returns
          </div>
        </div>
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_opteight_cancel">Cancel</div>
          <div class="confirm saveTeammate" id="vo_opteight_confirm">Save</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 9 Email Team -->
<div class="vo-modal-mask vo-optnine-modal" id="vo-optnine-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optnine-body">
      <div class="modal-title">Email Team</div>
      <div class="text-content">
        <p class="p1">Send via your computer's email client (eg.Outlook,Apple Mail)</p>
        <p class="p1">Send via your computer's email client (eg.Outlook,Apple Mail)</p>
        <p class="p1">Send via your computer's email client (eg.Outlook,Apple Mail)</p>
        <p class="p1">Send via your computer's email client (eg.Outlook,Apple Mail)</p>
        <p class="p2">Send via your computer's email client (eg.Outlook,Apple Mail)</p>
      </div>
      <div class="copy-url flexV-sb">
        <input type="text" name="pa-url-value" value="baidu.com" class="pa-url-value" id="pa-url-value"
               readonly="readonly">
        <div class="copy-btn" id="copy-text">Copy</div>
      </div>
      <div class="vo-modal-footer mt20">
        <div class="footer-rg-right">
          <div class="confirm" id="vo_optnine_cancel">Cancel</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- manage panle manage photo  -->
<div class="manage-panle" id='manage-photo-panle'>
  <div class="flex" style="height: 100%;">
    <div class="left-nav-wrap">
      <div class="c028 f12 back-wwap1" id='back-wwap1'>
        <img style="width:9px;" src="catalog/view/javascript/assets/right-arrow.png" alt="">
        Back to project
      </div>
      <div class="nav-wrap">
        <div class="nav-title c3 f13 fb">Sections</div>
        <div class="nav-item-wrap" id="managePanle-nav">
          <div class="item-nav flexV-sb on SoftcoverflexV flexV-sb-<?php echo $section['section_id'];?>" data-name="Hardcover">
            <p class="f12 c6">Hardcover</p>
            <span class="number-box f12 cf">3</span>
          </div>
          <div class="item-nav flexV-sb SoftcoverflexV flexV-sb-<?php echo $section['section_id'];?>" data-name="project_1">
            <p class="f12 c6">Softcover</p>
            <span class="number-box f12 cf">3</span>
          </div>
          <?php foreach ($sections as $section){ ?>
          <div class="item-nav flexV-sb SoftcoverflexV flexV-sb-<?php echo $section['section_id'];?>" data-name="project_2" data-index="<?php echo $section['section_id'];?>">
            <p class="f12 c6"><?php echo $section['name'];?></p>
            <span class="number-box f12 cf" id="section_<?php echo $section['section_id'];?>_image_count"><?php echo $section['image_count'];?></span>
          </div>
          <?php } ?>

        </div>
      </div>
      <div class="nav-wrap">
        <div class="nav-title c3 f13 fb">Unassigned</div>
        <div class="nav-item-wrap">
          <div class="item-nav flexV-sb">
            <p class="f12 c6">All</p>
            <span class="number-box f12 cf">3</span>
          </div>
        </div>
      </div>
      <div class="nav-wrap">
        <div class="nav-title c3 f13 fb">Tags</div>
        <div class="nav-item-wrap">
          <div class="item-nav flexV-sb">
            <p class="f12 c6">All</p>
            <span class="number-box f12 cf">3</span>
          </div>
        </div>
      </div>
      <div class="nav-wrap">
        <div class="nav-title c3 f13 fb">Artwork Added By Team</div>
        <div class="nav-item-wrap">
          <div class="item-nav flexV-sb">
            <p class="f12 c6">Backgrounds</p>
            <span class="number-box f12 cf">3</span>
          </div>
        </div>
        <div class="nav-item-wrap">
          <div class="item-nav flexV-sb">
            <p class="f12 c6">Stickes</p>
            <span class="number-box f12 cf">3</span>
          </div>
        </div>
      </div>
      <div class="nav-wrap">
        <div class="nav-title c3 f13 fb">Skipple</div>
        <div class="mt5">
          <p class="f12 c6 mr10">This organization is not set up for Skipple. Visit <a class="c028"
                                                                                       style="display: inline-block;">GetSkipple.com</a> and
            contact your Customer Support Specialist</p>
        </div>
      </div>
    </div>
    <div class="select-file-wrap flex1">
      <div class="jdjz h100 null-photos">
        <div class="photo-icon"><img src="catalog/view/javascript/assets/camera-icon.png" alt=""></div>
        <p class="f18 c3 fb">Manage Photos</p>
        <p class="f12 c6 mt5">Select a Section or Tag from the left to view its photos.</p>
      </div>
      <div class="h100 flex-C photos-wrap">
        <div class="photos-wrap-head-h1">
          <div class="photos-wrap photos-wrap-head flexV-sb">
            <div class="layout-list">
              <div class="layout-item layout-t1 mr5 tooltip-show change-layout"
                   data-placement="bottom" data-toggle="tooltip" title="line view" data-type="line">
                <img src="catalog/view/javascript/assets/layout-b.png" alt="">
              </div>
              <div class="layout-item layout-t2 tooltip-show change-layout" data-placement="bottom"
                   data-toggle="tooltip" title="List view" data-type="row">
                <img src="catalog/view/javascript/assets/layout-a.png" alt="">
              </div>
            </div>
            <div class="display-select mr10" id="displayfilteruse">
              <select name="filter_in_use" id="filteruse">
                <option value="0">Display all</option>
                <option value="1">Hide in use</option>
                <option value="2">Show only in use</option>
              </select>
            </div>
            <div class="oldest-select mr10" id="oldestsort">
              <select name="sort" id="sortFilename">
                <option value="c1.name ASC">Filename(A to Z)</option>
                <option value="c1.name DESC">Filename(Z to A)</option>
                <option value="c1.date_added ASC">Oldest</option>
                <option value="c1.date_added DESC">Newest</option>
              </select>
            </div>



            <form style="    width: 300px;position:relative;">
              <input id="file_upload" name="file_upload" onClick="javascript:$('#file_upload').uploadifive()" type="file" multiple="true" />
              <a class="prompt_box" msg="文件支持格式：gif, jpg, png, jpeg  最大不超过50M"><i style="margin-top: 55%;" class="iconfont icon-wenhao"></i></a>
              <div class="clearboth"></div>
              <div id="queue" style="position: absolute;">
                <div id="upload_select"><i></i></div>
              </div>
            </form>



          </div>
        </div>
        <div class="photos-wrap-head-h2">
          <div class="photos-wrap photos-wrap2 flexV">
            <div class="select-box display-select mr10">
              <select name="" id="">
                <option value="">Move to</option>
                <option value="">Move to</option>
                <option value="">Move to</option>
              </select>
            </div>
            <div class="select-box oldest-select mr5">
              <select name="" id="">
                <option value="">Copy to</option>
                <option value="">Hardcover</option>
                <option value="">Softcover</option>
                <option value="">Section 02</option>
              </select>
            </div>
            <button class="btn delete f12 mr10">Delete</button>
            <button class="btn remove f12 mr10">Remove From Section</button>
            <button class="btn assign f12 mr10">Assign Tags</button>
          </div>
        </div>
        <div class="manage-nav-content">
          <div class="flex1 layout-flex1">
            <div class="horizontal-wrap flex-wrap">
              <div class="item">
                <div class="img-box flex1-img-box-select img-box-select_active_1 tooltip-show"
                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"
                     data-index="1" data-layout="line" id="uploadifive-file_upload-file-<?php echo $section['section_id'];?>">
                  <img src="catalog/view/javascript/assets/pic-p2.png" alt="">
                  <div class="img-box-active-icon">
                    <img src="" alt="">
                  </div>
                  <div class="img-edit-tool-box flex1-img-edit-tool_t1" data-index="1">
                    <div class="img-edit-tool flexHV">
                                                <span class="tool-item tool-apm" data-index="0" data-name="tool-apm">
                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">
                                                </span>
                      <span class="tool-item tool-delect mr5 ml5" data-index="0"
                            data-name="tool-delect">
                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">
                                                </span>
                      <span class="tool-item tool-editor" data-index="0"
                            data-name="tool-editor">
                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">
                                                </span>
                    </div>
                  </div>
                </div>
                <p class="f12 c6">
                  <span class="c028">HC</span>,<span class="c028">HC</span>,
                  <span class="c028">HC</span>,<span class="c028">HC</span>,
                  <span class="c028">HC</span>,<span class="c028">HC</span>,
                </p>
              </div>
              <div class="item">
                <div class="img-box flex1-img-box-select img-box-select_active_2 tooltip-show"
                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"
                     data-index="2" data-layout="line">
                  <img src="./assets/pic-p2.png" alt="">
                  <div class="img-box-active-icon">
                    <img src="" alt="">
                  </div>
                  <div class="img-edit-tool-box flex1-img-edit-tool_t2" data-index="2">
                    <div class="img-edit-tool flexHV">
                                                <span class="tool-item tool-apm" data-index="0" data-name="tool-apm">
                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">
                                                </span>
                      <span class="tool-item tool-delect mr5 ml5" data-index="0"
                            data-name="tool-delect">
                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">
                                                </span>
                      <span class="tool-item tool-editor" data-index="0"
                            data-name="tool-editor">
                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">
                                                </span>
                    </div>
                  </div>
                </div>
                <p class="f12 c6">
                  <span class="c028">HC</span>,<span class="c028">HC</span>,
                  <span class="c028">HC</span>,<span class="c028">HC</span>,
                  <span class="c028">HC</span>,<span class="c028">HC</span>,
                </p>
              </div>
              <div class="item">
                <div class="img-box flex1-img-box-select tooltip-show img-box-select_active_3"
                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"
                     data-index="3" data-layout="line">
                  <img src="./assets/pic-p2.png" alt="">
                  <div class="img-box-active-icon">
                    <img src="" alt="">
                  </div>
                  <div class="img-edit-tool-box flex1-img-edit-tool_t3" data-index="3">
                    <div class="img-edit-tool flexHV">
                                                <span class="tool-item tool-apm">
                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">
                                                </span>
                      <span class="tool-item tool-delect mr5 ml5">
                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">
                                                </span>
                      <span class="tool-item tool-editor">
                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">
                                                </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex1 layout-flex2 p10 pt20">
            <div class="cell-item flex mb40">
              <div class="check-this-cell">
                <span class="check-status"></span>
              </div>
              <div class="img-box ml20 mr10 flex2-img-box-select" data-index="1" data-layout="row">
                <img src="catalog/view/javascript/assets/pic-p2.png" alt="">
                <div class="img-box-active-icon">
                  <img src="" alt="">
                </div>
                <p>HC,HC,HC,CS</p>
                <div class="img-edit-tool-box flex2-img-edit-tool_t1" data-index="1">
                  <div class="img-edit-tool flexHV">
                                            <span class="tool-item tool-apm" data-index="1" data-name="tool-apm">
                                                <img src="catalog/view/javascript/assets/f-max.png" alt="">
                                            </span>
                    <span class="tool-item tool-delect mr5 ml5" data-index="2
                                            data-name=" tool-delect">
                    <img src="catalog/view/javascript/assets/delect.png" alt="">
                    </span>
                    <span class="tool-item tool-editor" data-index="3" data-name="tool-editor">
                                                <img src="catalog/view/javascript/assets/editor.png" alt="">
                                            </span>
                  </div>
                </div>
              </div>
              <div class="img-info-cell">
                <div class="i-item mb10">
                  <p>Filename</p>
                  <input type="text" value="123456.jpg" class="filename">
                </div>
                <div class="i-item mb10">
                  <p>Notes</p>
                  <input type="text" value="" class="notes">
                </div>
                <div class="i-item i-item-3 mb10">
                  <p>Tags</p>
                  <input type="text" value="" class="tags">
                  <span>Add new tag and pross Enter</span>
                </div>
              </div>
            </div>
            <div class="cell-item flex mb20">
              <div class="check-this-cell">
                <span class="check-status"></span>
              </div>
              <div class="img-box ml20 mr10 flex2-img-box-select" data-index="2" data-layout="row">
                <img src="catalog/view/javascript/assets/pic-p2.png" alt="">
                <div class="img-box-active-icon">
                  <img src="" alt="">
                </div>
                <p>HC,HC,HC,CS</p>
                <div class="img-edit-tool-box flex2-img-edit-tool_t2" data-index="2">
                  <div class="img-edit-tool flexHV">
                                            <span class="tool-item tool-apm" data-index="1" data-name="tool-apm">
                                                <img src="catalog/view/javascript/assets/f-max.png" alt="">
                                            </span>
                    <span class="tool-item tool-delect mr5 ml5" data-index="2
                                                data-name=" tool-delect">
                    <img src="catalog/view/javascript/assets/delect.png" alt="">
                    </span>
                    <span class="tool-item tool-editor" data-index="3" data-name="tool-editor">
                                                <img src="catalog/view/javascript/assets/editor.png" alt="">
                                            </span>
                  </div>
                </div>
              </div>
              <div class="img-info-cell">
                <div class="i-item mb10">
                  <p>Filename</p>
                  <input type="text" value="123456.jpg">
                </div>
                <div class="i-item mb10">
                  <p>Notes</p>
                  <input type="text" value="" class="notes">
                </div>
                <div class="i-item i-item-3 mb10">
                  <p>Tags</p>
                  <input type="text" value="" class="tags">
                  <span>Add new tag and pross Enter</span>
                </div>
              </div>
            </div>
            <div class="cell-item flex mb20">
              <div class="check-this-cell">
                <span class="check-status"></span>
              </div>
              <div class="img-box ml20 mr10 flex2-img-box-select" data-index="3" data-layout="row">
                <img src="catalog/view/javascript/assets/pic-p2.png" alt="">
                <div class="img-box-active-icon">
                  <img src="" alt="">
                </div>
                <p>HC,HC,HC,CS</p>
                <div class="img-edit-tool-box flex2-img-edit-tool_t3" data-index="3">
                  <div class="img-edit-tool flexHV">
                                            <span class="tool-item tool-apm" data-index="1" data-name="tool-apm">
                                                <img src="catalog/view/javascript/assets/f-max.png" alt="">
                                            </span>
                    <span class="tool-item tool-delect mr5 ml5" data-index="2
                                                data-name=" tool-delect">
                    <img src="catalog/view/javascript/assets/delect.png" alt="">
                    </span>
                    <span class="tool-item tool-editor" data-index="3" data-name="tool-editor">
                                                <img src="catalog/view/javascript/assets/editor.png" alt="">
                                            </span>
                  </div>
                </div>
              </div>
              <div class="img-info-cell">
                <div class="i-item mb10">
                  <p>Filename</p>
                  <input type="text" value="123456.jpg" class="filename">
                </div>
                <div class="i-item mb10">
                  <p>Notes</p>
                  <input type="text" value="" class="notes">
                </div>
                <div class="i-item i-item-3 mb10">
                  <p>Tags</p>
                  <input type="text" value="" class="tags">
                  <span>Add new tag and pross Enter</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="manage-nav-content-<?php echo $section['section_id'];?>">
          <div class="flex1 layout-flex1">
            <div class="horizontal-wrap flex-wrap manage-nav-contentitem">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- manage panle manage people  -->
<div class="manage-panle" id="manage-people-panle">
  <div class="flex" style="height: 100%;">
    <div class="left-nav-wrap">
      <div class="c028 f12 back-wwap1" id='managepeople-back'>
        <img style="width:9px;" src="catalog/view/javascript/assets/right-arrow.png" alt="">
        Back to project
      </div>
      <div class="c028 f12 img_box">
        <img src="catalog/view/javascript/assets/default-p.png" alt="">
      </div>
      <P class="img_box_pro">SHOWing O OUT OF O PEOPLE</P>
      <ul class="left_ul1">
        <li class="li1">
          <p>search</p>
          <p>
            <input type="text" />
          </p>
        </li>
        <li class="li1">
          <p>file by section</p>
          <p>
            <select>
              <option>select</option>
            </select>
          </p>
        </li>
        <li class="li1">
          <p>file by Grade</p>
          <p>
            <select>
              <option>select</option>
            </select>
          </p>
        </li>
        <li class="li1">
          <p>file by teach</p>
          <p>
            <select>
              <option>select</option>
            </select>
          </p>
        </li>
        <li class="li1">
          <p>file by homeroom</p>
          <p>
            <select>
              <option>select</option>
            </select>


          </p>
        </li>
        <li class="li1">
          <p>file by # times in book</p>
          <p>
            <select>
              <option>select</option>
            </select>
          </p>
        </li>
        <li class="li1">
          <p>
                            <span>
                                <input type="checkbox" />

                            </span>
            <span>
                                show only people missing required information
                            </span>
          </p>
        </li>

      </ul>
    </div>
    <div class="select-file-wrap flex1">
      <div class="jdjz h100 null-photos">
        <div class="photo-icon"><img src="catalog/view/javascript/assets/camera-icon.png" alt=""></div>
        <p class="f18 c3 fb">Manage Photos</p>
        <p class="f12 c6 mt5">Select a Section or Tag from the left to view its photos.</p>
      </div>
      <div class="h100 flex-C photos-wrap">
        <div class="manage-nav-content">
          <div class="content_top">
                            <span>
                                <button class="Edit">Edit</button>
                                <button class="Delete">Delete</button>
                            </span>
            <span>
                                <button class="Export">Export</button>
                                <select class="Add_Import">
                                    <option>Add/Import</option>
                                    <option>Add New Person</option>
                                    <option>Import PSPA</option>
                                    <option>Import From Another Source</option>
                                </select>
                            </span>
          </div>
        </div>
        <div style="clear: both"></div>
        <div class="manage-nav-content-other mt50">
          <p class="content-other_p1">
            Your project has no people
          </p>
          <p class="content-other_p2">
            <button class="content-other_btn1">Import PSPA Now</button>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- section page manage panle -->
<div class="manage-panle" id='section-page-manage-panle'>
  <div class="model_box">
    <div class="model_box_left">
      <div class="model_box_left_top">
        <p>Section</p>
      </div>
      <div class="model_box_left_main">
        <ul class="model_box_left_main_ul1">
          <li class="model_box_left_main_ul1_li curror_li active">Hardcover</li>
          <li class="model_box_left_main_ul1_li curror_li show_ul softcover_box">softcover</li>
          <ul class="softcover_ul">
            <li class="softcover_ul_li curror_li1 active1">Year 1</li>
            <li class="softcover_ul_li curror_li1">Year 2</li>
            <li class="softcover_ul_li curror_li1">Year 3</li>
          </ul>
          <li class="model_box_left_main_ul1_li curror_li">Section 01 </li>
          <li class="model_box_left_main_ul1_li curror_li">Section 02</li>
          <li class="model_box_left_main_ul1_li curror_li">index</li>
        </ul>
      </div>
    </div>
    <div class="model_box_right">
      <div class="model_box_right_top">
                    <span>
                        Pages
                    </span>
        <span class="close close_section-page-manage">
                        x
                    </span>
      </div>
      <div class="model_box_right_main p20">
        <div class="show_div">
          <div class="pic-list-view">
            <div class='pic-item-box mr20 mb20'>
              <span class="fb c3 f12">Softcover</span>
              <div class="pic-box"><img src="" alt=""></div>
              <p class="bt-text">1</p>
            </div>
            <div class='pic-item-box mr20 mb20'>
              <span class="fb c3 f12">Softcover</span>
              <div class="pic-box"><img src="" alt=""></div>
              <p class="bt-text">1</p>
            </div>
            <div class='pic-item-box mr20 mb20'>
              <span class="fb c3 f12">Softcover</span>
              <div class="pic-box"><img src="" alt=""></div>
              <p class="bt-text">1</p>
            </div>
            <div class='pic-item-box mr20 mb20'>
              <span class="fb c3 f12">Softcover</span>
              <div class="pic-box"><img src="" alt=""></div>
              <p class="bt-text">1</p>
            </div>
          </div>
        </div>
        <div class="show_div">softcover</div>
        <div class="show_div">Section 01 </div>
        <div class="show_div">Section 02</div>
        <div class="show_div">index</div>
        <div class="show_div show_div1">Year 1</div>
        <div class="show_div show_div1">Year 2</div>
        <div class="show_div show_div1">Year 3</div>
      </div>
    </div>
  </div>
</div>
<!-- model结束 -->
<!-- modal 10 manage panle dit Photo  -->
<div class="vo-modal-mask vo-optten-modal" id="vo-optten-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optten-body">
      <div class="modal-title">Edit Photo</div>
      <div class="cell-item flex mb40">
        <div class="img-box mr10">
          <img src="catalog/view/javascript/assets/pic-p2.png" alt="" id="editFilename">
          <div class="img-box-active-icon">
            <img src="" alt="">
          </div>
        </div>
        <div class="img-info-cell">
          <div class="i-item mb10">
            <p style="margin-bottom:10px;">Filename</p>
            <input type="text" value="123456.jpg" class="filename" id="Filename">
          </div>
          <div class="i-item mb10">
            <p>Notes</p>
            <input type="text" value="" class="notes" id="Notes">
          </div>
          <div class="i-item i-item-3 mb10">
            <p>Tags</p>
            <input type="text" value="" class="tags" id="tags">
            <span>Add new tag and pross Enter</span>
          </div>
        </div>
      </div>
      <div class="vo-modal-footer mt20" style="    margin-top: -43px;">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optten_cancel">Cancel</div>
          <div class="confirm DoneEdit" id="vo_optten_cancel">Done</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 11  lock  -->
<div class="vo-modal-mask vo-optEleven-modal" id="vo-optEleven-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optEleven-body">
      <h4 class="modal-title">Check Spelling</h4>
      <P class="title-aid">Checking Spelling ...</P>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optEleven_cancel">Cancel</div>
          <div class="confirm lockProject btn-danger" id="vo_optEleven_confirm" >Lock</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 12  rename  -->
<div class="vo-modal-mask vo-optTwelve-modal" id="vo-optTwelve-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optTwelve-body">
      <h4 class="modal-title">Rename Section</h4>
      <div class="text-content text-content-2">
        <input type="text" value="" name="rename" id="rename" class="">
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optTwelve_cancel">Cancel</div>
          <div class="confirm RenameProject btn-danger" id="vo_optTwelve_confirm">Rename</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 13  delete  -->
<div class="vo-modal-mask vo-optThirteen-modal" id="vo-optThirteen-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optThirteen-body">
      <h4 class="modal-title">Delete Section</h4>
      <P class="title-aid">Are you sure you want to delete the section "<em id="sectiondelete" style="display:inline-block;font-size: 26px;font-style: normal;"></em>"</P>
      <P class="title-aid">All the pages will be lost, but any photos will still be available.</P>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optThirteen_cancel">Cancel</div>
          <div class="confirm deleteSetion btn-danger" id="vo_optThirteen_confirm">delete</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 14  add pages  -->
<div class="vo-modal-mask vo-optFourteen-modal" id="vo-optFourteen-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optFourteen-body">
      <h4 class="modal-title">Add Pages</h4>
      <div class="text-content text-content-2">
        <p>How many Pages?</p>
        <input type="text" value="" name="pages" id="pages" class="">
      </div>
      <div class="vo-modal-footer">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optFourteen_cancel">Cancel</div>
          <div class="confirm AddPagesProjects btn-danger" id="vo_optFourteen_confirm">Add Pages</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal 15  add pages  -->
<div class="vo-modal-mask vo-optFifteen-modal" id="vo-optFifteen-modal">
  <div class="vo-modal" id="createProject-modal">
    <div class="vo-modal-body vo-optFifteen-body">
      <h4 class="modal-title">Delete this photo and remove from all pages?</h4>
      <div class="modal-body">
        <div>
          <img class="" src="" alt="" id="Deletephoto" style="width:150px;">
        </div>
      </div>
      <div class="vo-modal-footer" style="padding-top:0px;">
        <div class="footer-rg-right">
          <div class="cancel" id="vo_optFifteen_cancel">Cancel</div>
          <div class="confirm DeleteProjects btn-danger" id="vo_optFifteen_confirm">Delete</div>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="ShowImage_Form" class="modal fade" style="    z-index: 9999;">
  <div id="img-dialog" class="modal-dialog" style="width: 98%; height: 98%;text-align: center;">
    <div id="img-content" class="modal-content" style="    width: 100%;height: 100%;">
      <img id="img_show" src="" style="max-height: 1000px; max-width: 1000px;margin:10px;    height: 699px;">
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="catalog/view/javascript/view/js/jquery.min.js"></script>
<script src="catalog/view/javascript/view/js/bootstrap.min.js"></script>
<script src="catalog/view/javascript/project/js/dashboard.js"></script>
<script src="catalog/view/javascript/project/js/jquery-ui-1.12.1.js"></script>
<script src="catalog/view/javascript/project/js/jquery.uploadifive.js"></script>
<script src="catalog/view/javascript/view/js/toastr.min.js"></script>
<script>

  var image_server = '<?php echo $image_uri;?>';

  // 全局变量
  let project_id;
  project_id = '<?php echo $project_id;?>';
   // 添加项目
  locked = '<?php echo $section['locked'];?>';
  // 添加项目
  $('#vo_optfive_confirm').on('click',function() {
     var name = $('#task').val();
     var pages = $('#Pagesnumber').val();
    $.ajax({
      url:'index.php?route=account/section/add',
      data:{project_id:project_id,name:name,pages:pages},
      type: 'post',
      dataType: 'json',  // 请求方式为jsonp
      success:function(json){
        if(json.status==1){
          toastr.success("创建成功");
          var result = json.data

          json = eval('('+json+')');
          for(var i=0,len=json.data.length;i<len;i++){
            str +=   '<div class="img-box"></div> ';
            str +=   '<img style="margin-left:8px" src="catalog/view/javascript/assets/person-icon.png" alt=""> ';
            str +=   '<h3>'+json.data[i]['name']+'</a>';
            str +=   '<p class="f13 c028 ml5" style="margin:7px;">'+json.data[i]['name']+'</p>';
            str +=   '<span class="ReadyGreen ReadyGreen-'+json.data[i]['id']+'" data-index="'+json.data[i]['id']+'" style="margin-left: 10px;    background-color: #6cc302;">Ready for Review</span>';

          }

          $('#lists').append(str);$sections
          window.location.reload()//刷新当前页面.
        }
      }
    })
  })
  //标记为准备审查
  $('.Mark').on('click',function (res) {
    var section_id = $(this).attr('section_id');
    var review = $(this).attr('locked');
    review = (review==1)?0:1;

    let index = res.currentTarget.dataset.index;
    console.log(index)
    if($('.ReadyGreen-' + index + '').is(':hidden')) {
      $('.Mark').html('Unmark as Ready for Review');
      $('.ReadyGreen-' + index + '').show();
    }else{
      $('.Mark').html('Mark as Ready for Review');
      $('.ReadyGreen-' + index + '').hide();
    }
    $.ajax({
      url:'index.php?route=account/section/markReview',
      data:{project_id:project_id,section_id:section_id,'review':review},
      type: 'post',
      dataType: 'json',  // 请求方式为jsonp
      success:function(data){
        if(data.status==1){
          toastr.success("标记成功");
        }
      }
    })
  })
  //锁
  $('.Lock').click(function(res){
      var section_id = $(this).attr('section_id');
      var locked = $(this).attr('locked');
      locked = (locked==1)?0:1;
    let index = res.currentTarget.dataset.index;
    $('#vo-optEleven-modal').show();
    $('.lockProject').click(function(res) {

      $.ajax({
        url:'index.php?route=account/section/lock',
        data:{project_id:project_id,section_id:section_id,'locked':locked},
        type: 'post',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          if(data.status==1){
            toastr.success("锁成功");

            $('.covers-items-' + index + '').css('background-color','#E8E8ED');
          }
        }
      })
    })
  })
  //修改名字
  $('.Rename').click(function(){
    var section_id = $(this).attr('section_id');
    $('#vo-optTwelve-modal').show();

    $('.RenameProject').click(function() {
      var name = $('#rename').val();
      $.ajax({
        url:'index.php?route=account/section/rename',
        data:{project_id:project_id,section_id:section_id,name:name},
        type: 'post',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          if(data.status==1){
            toastr.success("修改成功");
          }
        }
      })
    })
  })
  //删除
  $('.Delete').click(function(){
      var section_id = $(this).attr('section_id');
      var section_name = $(this).attr('section_name');
      $('#sectiondelete').html(section_name);
    $('#vo-optThirteen-modal').show();
    $('#sectiondelete').html();
    $('.deleteSetion').click(function() {
      $.ajax({
        url:'index.php?route=account/section/delete',
        data:{project_id:project_id,section_id:section_id},
        type: 'post',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          if(data.status==1){
            toastr.success("删除成功");
            window.location.reload()//刷新当前页面.
          }
        }
      })
    })
  })

  //manage photo
  $('.SoftcoverflexV').click(function(res) {
    console.log(image_server)
    let section_id = res.currentTarget.dataset.index;
    if ($('.flexV-sb-' + section_id + '').css('background', '#E5E5E5')) {
      $('.flexV-sb-' + section_id + '').css('background', '#E5E5E5');
    } else {
      $('.flexV-sb-' + section_id + '').css('background', 'rgba(0, 0, 0, 0.1)');
    }
    $.ajax({
      url: 'index.php?route=account/section/images',
      data:{section_id:section_id},
      type: 'get',
      dataType: 'json',  // 请求方式为jsonp
      success: function (data) {
        var result = data.data
        if (data.status == 0) {
          var arr = '';
          for (var i = 0; i < result.length; i++) {
            arr += '<div class="item ">\n' +
                    '<div class="img-box flex1-img-box-select img-box-select_active_1 tooltip-show"\n' +
                    '                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"\n' +
                    '                     data-index="' + result[i].id + '" data-layout="line" id="uploadifive-file_upload-file">\n' +
                    '                  <img src="' + image_server + '' + result[i].thumb + '" alt="" style="width:130px;height:150px;">\n' +
                    '                  <div class="img-box-active-icon">\n' +
                    '                    <img src="" alt="">\n' +
                    '                  </div>\n' +
                    '                  <div class="img-edit-tool-box flex1-img-edit-tool_t1" style="display:block;" data-index="' + result[i].id + '">\n' +
                    '                    <div class="img-edit-tool flexHV">\n' +
                    '                                                <span class="tool-item tool-apm"  src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '" data-name="tool-apm">\n' +
                    '                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">\n' +
                    '                                                </span>\n' +
                    '                      <span class="tool-item tool-delects mr5 ml5" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                    '                            data-name="tool-delect">\n' +
                    '                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">\n' +
                    '                                                </span>\n' +
                    '                      <span class="tool-item tool-editor" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                    '                            data-name="tool-editor">\n' +
                    '                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">\n' +
                    '                                                </span>\n' +
                    '                    </div>\n' +
                    '                  </div>\n' +
                    '                </div>\n' +
                    '                <p class="f12 c6">\n' +
                    '                  <span class="c028">' + result[i].filename + '</span>\n' +
                    '                </p>\n' +
                    '              </div>'
            '                    </div>'


          }
          $(".manage-nav-contentitem").html(arr);
          //显示大图
          $('.tool-apm').click(function() {
            var source = $(this).attr('src');
            console.log(source,111)
            $("#ShowImage_Form").find("#img_show").attr("src", source);

            $("#ShowImage_Form").modal();

            $("#img_show").click(function(){
              $('#ShowImage_Form').modal("hide");
            });
            $("#img-dialog").click(function(){
              $('#ShowImage_Form').modal("hide");
            });
          })

          //编辑图片
          $('.tool-editor').click(function (res) {
            $('#vo-optten-modal').show();
            let id = res.currentTarget.dataset.index
            var src = $(this).attr('src');
            $('#editFilename').attr('src', src);
            $('.DoneEdit').click(function () {
              var name = $('#Filename').val();
              var note = $('#Notes').val();
              var tag = $('#tags').val();
              $.ajax({
                url: 'index.php?route=account/section/updateImage',
                data:{id:id,section_id:section_id,tag:tag,name:name,note:note},
                type: 'post',
                dataType: 'json',  // 请求方式为jsonp
                success: function (data) {
                  if (data.status == 1) {
                    toastr.success("编辑成功");
                  }
                }
              })
            })
          })
          //删除photo
          $('.tool-delects').click(function (res) {
            $('#vo-optFifteen-modal').show();
            let id = res.currentTarget.dataset.index
            var src = $(this).attr('src');
            $('#Deletephoto').attr('src', src);
            $('.DeleteProjects').click(function (res) {
              $.ajax({
                url: 'index.php?route=account/section/deleteImage',
                data:{id:id,section_id:section_id},
                type: 'post',
                dataType: 'json',  // 请求方式为jsonp
                success: function (data) {
                  if (data.status == 1) {
                    toastr.success("删除成功");
                    $.ajax({
                      url: 'index.php?route=account/section/images',
                      data:{section_id:section_id},
                      type: 'get',
                      dataType: 'json',  // 请求方式为jsonp
                      success: function (data) {
                        var result = data.data
                        if (data.status == 0) {
                          var arr = '';
                          for (var i = 0; i < result.length; i++) {
                            arr += '<div class="item ">\n' +
                                    '<div class="img-box flex1-img-box-select img-box-select_active_1 tooltip-show"\n' +
                                    '                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"\n' +
                                    '                     data-index="' + result[i].id + '" data-layout="line" id="uploadifive-file_upload-file">\n' +
                                    '                  <img src="' + image_server + '' + result[i].thumb + '" alt="" style="width:130px;height:150px;">\n' +
                                    '                  <div class="img-box-active-icon">\n' +
                                    '                    <img src="" alt="">\n' +
                                    '                  </div>\n' +
                                    '                  <div class="img-edit-tool-box flex1-img-edit-tool_t1" style="display:block;" data-index="' + result[i].id + '">\n' +
                                    '                    <div class="img-edit-tool flexHV">\n' +
                                    '                                                <span class="tool-item tool-apm"  src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '" data-name="tool-apm">\n' +
                                    '                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">\n' +
                                    '                                                </span>\n' +
                                    '                      <span class="tool-item tool-delects mr5 ml5" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                                    '                            data-name="tool-delect">\n' +
                                    '                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">\n' +
                                    '                                                </span>\n' +
                                    '                      <span class="tool-item tool-editor" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                                    '                            data-name="tool-editor">\n' +
                                    '                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">\n' +
                                    '                                                </span>\n' +
                                    '                    </div>\n' +
                                    '                  </div>\n' +
                                    '                </div>\n' +
                                    '                <p class="f12 c6">\n' +
                                    '                  <span class="c028">' + result[i].filename + '</span>\n' +
                                    '                </p>\n' +
                                    '              </div>'
                            '                    </div>'


                          }
                          $(".manage-nav-contentitem").html(arr);

                        }
                      }


                    })
                  }
                }
              })
            })

          })
        }
      }


    })
    //
    $('#displayfilteruse').click(function() {
      var filteruse = $('#filteruse').val();
      $.ajax({
        url:' index.php?route=account/section/images&section_id=section_id&filter_in_use=filteruse',
        data:{section_id:section_id},
        type: 'get',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          var result = data.data
          if(data.status==0){
            var arr = '';
            for (var i = 0; i < result.length; i++) {
              arr += '<div class="item ">\n' +
                      '<div class="img-box flex1-img-box-select img-box-select_active_1 tooltip-show"\n' +
                      '                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"\n' +
                      '                     data-index="' + result[i].id + '" data-layout="line" id="uploadifive-file_upload-file">\n' +
                      '                  <img src="' + image_server + '' + result[i].thumb + '" alt="" style="width:130px;height:150px;">\n' +
                      '                  <div class="img-box-active-icon">\n' +
                      '                    <img src="" alt="">\n' +
                      '                  </div>\n' +
                      '                  <div class="img-edit-tool-box flex1-img-edit-tool_t1" style="display:block;" data-index="' + result[i].id + '">\n' +
                      '                    <div class="img-edit-tool flexHV">\n' +
                      '                                                <span class="tool-item tool-apm"  src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '" data-name="tool-apm">\n' +
                      '                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">\n' +
                      '                                                </span>\n' +
                      '                      <span class="tool-item tool-delects mr5 ml5" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                      '                            data-name="tool-delect">\n' +
                      '                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">\n' +
                      '                                                </span>\n' +
                      '                      <span class="tool-item tool-editor" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                      '                            data-name="tool-editor">\n' +
                      '                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">\n' +
                      '                                                </span>\n' +
                      '                    </div>\n' +
                      '                  </div>\n' +
                      '                </div>\n' +
                      '                <p class="f12 c6">\n' +
                      '                  <span class="c028">' + result[i].filename + '</span>\n' +
                      '                </p>\n' +
                      '              </div>'
              '                    </div>'


            }
            $(".manage-nav-contentitem").html(arr);
            //显示大图
            $('.tool-apm').click(function() {
              var source = $(this).attr('src');
              console.log(source,111)
              $("#ShowImage_Form").find("#img_show").attr("src", source);

              $("#ShowImage_Form").modal();

              $("#img_show").click(function(){
                $('#ShowImage_Form').modal("hide");
              });
              $("#img-dialog").click(function(){
                $('#ShowImage_Form').modal("hide");
              });
            })

            //编辑图片
            $('.tool-editor').click(function (res) {
              $('#vo-optten-modal').show();
              let id = res.currentTarget.dataset.index
              var src = $(this).attr('src');
              $('#editFilename').attr('src', src);
              $('.DoneEdit').click(function () {
                var name = $('#Filename').val();
                var note = $('#Notes').val();
                var tag = $('#tags').val();
                $.ajax({
                  url: 'index.php?route=account/section/updateImage',
                  data:{id:id,section_id:section_id,tag:tag,name:name,note:note},
                  type: 'post',
                  dataType: 'json',  // 请求方式为jsonp
                  success: function (data) {
                    if (data.status == 1) {
                      toastr.success("编辑成功");
                    }
                  }
                })
              })
            })
            //删除photo
            $('.tool-delects').click(function (res) {
              $('#vo-optFifteen-modal').show();
              let id = res.currentTarget.dataset.index
              var src = $(this).attr('src');
              $('#Deletephoto').attr('src', src);
              $('.DeleteProjects').click(function (res) {
                $.ajax({
                  url: 'index.php?route=account/section/deleteImage',
                  data:{id:id,section_id:section_id},
                  type: 'post',
                  dataType: 'json',  // 请求方式为jsonp
                  success: function (data) {
                    if (data.status == 1) {
                      toastr.success("删除成功");
                      window.location.reload()//刷新当前页面.
                    }
                  }
                })
              })

            })
          }
        }
      })
    })
    $('#oldestsort').click(function() {
      var sortFilename = $('#sortFilename').val();
      $.ajax({
        url:' index.php?route=account/section/images&section_id=section_id&sort=sortFilename',
        data:{section_id:section_id},
        type: 'get',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          var result = data.data
          if(data.status==0){
            var arr = '';
            for (var i = 0; i < result.length; i++) {
              arr += '<div class="item ">\n' +
                      '<div class="img-box flex1-img-box-select img-box-select_active_1 tooltip-show"\n' +
                      '                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"\n' +
                      '                     data-index="' + result[i].id + '" data-layout="line" id="uploadifive-file_upload-file">\n' +
                      '                  <img src="' + image_server + '' + result[i].thumb + '" alt="" style="width:130px;height:150px;">\n' +
                      '                  <div class="img-box-active-icon">\n' +
                      '                    <img src="" alt="">\n' +
                      '                  </div>\n' +
                      '                  <div class="img-edit-tool-box flex1-img-edit-tool_t1" style="display:block;" data-index="' + result[i].id + '">\n' +
                      '                    <div class="img-edit-tool flexHV">\n' +
                      '                                                <span class="tool-item tool-apm"  src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '" data-name="tool-apm">\n' +
                      '                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">\n' +
                      '                                                </span>\n' +
                      '                      <span class="tool-item tool-delects mr5 ml5" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                      '                            data-name="tool-delect">\n' +
                      '                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">\n' +
                      '                                                </span>\n' +
                      '                      <span class="tool-item tool-editor" src = "' + image_server + '' + result[i].thumb + '" data-index="' + result[i].id + '"\n' +
                      '                            data-name="tool-editor">\n' +
                      '                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">\n' +
                      '                                                </span>\n' +
                      '                    </div>\n' +
                      '                  </div>\n' +
                      '                </div>\n' +
                      '                <p class="f12 c6">\n' +
                      '                  <span class="c028">' + result[i].filename + '</span>\n' +
                      '                </p>\n' +
                      '              </div>'
              '                    </div>'


            }
            $(".manage-nav-contentitem").html(arr);
            //显示大图
            $('.tool-apm').click(function() {
              var source = $(this).attr('src');
              console.log(source,111)
              $("#ShowImage_Form").find("#img_show").attr("src", source);

              $("#ShowImage_Form").modal();

              $("#img_show").click(function(){
                $('#ShowImage_Form').modal("hide");
              });
              $("#img-dialog").click(function(){
                $('#ShowImage_Form').modal("hide");
              });
            })

            //编辑图片
            $('.tool-editor').click(function (res) {
              $('#vo-optten-modal').show();
              let id = res.currentTarget.dataset.index
              var src = $(this).attr('src');
              $('#editFilename').attr('src', src);
              $('.DoneEdit').click(function () {
                var name = $('#Filename').val();
                var note = $('#Notes').val();
                var tag = $('#tags').val();
                $.ajax({
                  url: 'index.php?route=account/section/updateImage',
                  data:{id:id,section_id:section_id,tag:tag,name:name,note:note},
                  type: 'post',
                  dataType: 'json',  // 请求方式为jsonp
                  success: function (data) {
                    if (data.status == 1) {
                      toastr.success("编辑成功");
                    }
                  }
                })
              })
            })
            //删除photo
            $('.tool-delects').click(function (res) {
              $('#vo-optFifteen-modal').show();
              let id = res.currentTarget.dataset.index
              var src = $(this).attr('src');
              $('#Deletephoto').attr('src', src);
              $('.DeleteProjects').click(function (res) {
                $.ajax({
                  url: 'index.php?route=account/section/deleteImage',
                  data:{id:id,section_id:section_id},
                  type: 'post',
                  dataType: 'json',  // 请求方式为jsonp
                  success: function (data) {
                    if (data.status == 1) {
                      toastr.success("删除成功");
                      window.location.reload()//刷新当前页面.
                    }
                  }
                })
              })

            })
          }
        }
      })
    })
  })


  // 添加团队
  $('.saveTeammate').click(function(){
    var section_id = $(this).attr('section_id');
    var first= $('#first-name').val();
    var last= $('#last_name').val();
    var email= $('#email').val();
    var role= $('#role').val();

      $.ajax({
        url:'index.php?route=account/project/addProjectCustomer',
        data:{project_id:project_id,first_name:first,last_name:last,role_id:role,email:email},
        type: 'post',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          if(data.status==1){
            toastr.success("删除成功");
            window.location.reload()//刷新当前页面.
          }
        }
      })

  })
  //下载content_url
  // function download() {
  //    let xhr = new XMLHttpRequest();
  //    xhr.open('get','catalog/view/theme/pav_silkshop/template/account/content_url.json',true);
  //    xhr.send();
  //
  // }

  $.ajax({
    url:'catalog/view/theme/pav_silkshop/template/account/content_url.json',
    type: 'get',
    dataType: 'json',  // 请求方式为jsonp
    success:function(data){
      console.log(123)
      console.log(data)
      var result = data.pages

    }
  })

  //添加页数
  $(".img-box1").on("click", function (res) {
    let index = res.currentTarget.dataset.index;

    if ($('.Add-action-panle-p' + index + '').is(':hidden')) {
      $('.Add-action-panle-p' + index + '').show();
    }
    else {
      $('.Add-action-panle-p' + index + '').hide();
    }
  });
  $('.AddPagesProject').click(function(){
    var section_id = $(this).attr('section_id');
    console.log(section_id)
    $('#vo-optFourteen-modal').show();

    $('.AddPagesProjects').click(function() {
      var pages = $('#pages').val();
      console.log(pages)
      $('.ui-sortable-'+ section_id + '').append('<div class="img-box1"></div>');

      $.ajax({
        url:'catalog/view/theme/pav_silkshop/template/account/content_url.json',
        type: 'get',
        dataType: 'json',  // 请求方式为jsonp
        success:function(data){
          console.log(123)
          if(data.status==1){
            toastr.success("修改成功");
          }
        }
      })

    })
  })
  $(function() {
      createUp5(251);
  });



  $('.left-nav-wrap .item-nav').on('click',function(ele,index) {
      section_id = $(this).attr('data-index');
      $('#file_upload').uploadifive('destroy');
      createUp5(section_id);
  })
  var is_cancle ="";
  var file_type ="";
  var multi = false;
  var is_image_type = "";
  var image_type = ".gif, .jpg, .png, .jpeg";
  function createUp5(section_id)
  {
      $('#file_upload').uploadifive({
          'uploadScript'     : 'index.php?route=common/filemanager/sectionImageUpload&section_id='+section_id,
          'queueID'          : 'queue',
          "fileType": image_type,
          "auto": true,
          "multi": true,
          "dnd":false,
          "height": 34,
          "width": 100,
          "fileSizeLimit": "50MB",
          "uploadLimit": 10000,
          "buttonText": "选择文件",
          'removeCompleted' : false,
          'onUpload' : function(){
              //$("#upload_select").remove();

          },
          onUploadComplete : function(file, data,index) {
              var objc = JSON.parse(data);
              if(objc.status==1){
                  var images = objc.data.thumb
                 var img = image_server + images ;
                $.ajax({
                  url:'index.php?route=account/section/images',
                  data:{section_id:section_id},
                  type: 'get',
                  dataType: 'json',  // 请求方式为jsonp
                  success:function(data){

                    var result = data.data
                    if(data.status==0){
                      var arr = '';
                      for(var i = 0; i < result.length; i++){
                        arr += '<div class="item ">\n' +
                                '<div class="img-box flex1-img-box-select img-box-select_active_1 tooltip-show"\n' +
                                '                     data-placement="bottom" data-toggle="tooltip" title="IMG_20180505.jpg"\n' +
                                '                     data-index="1" data-layout="line" id="uploadifive-file_upload-file">\n' +
                                '                  <img src="'+image_server+''+ result[i].thumb+'" alt="" style="width:130px;height:150px;">\n' +
                                '                  <div class="img-box-active-icon">\n' +
                                '                    <img src="" alt="">\n' +
                                '                  </div>\n' +
                                '                  <div class="img-edit-tool-box flex1-img-edit-tool_t1" style="display:block;"data-index="+ result[i].id+">\n' +
                                '                    <div class="img-edit-tool flexHV">\n' +
                                '                                                <span class="tool-item tool-apm" data-index="0" data-name="tool-apm">\n' +
                                '                                                    <img src="catalog/view/javascript/assets/f-max.png" alt="">\n' +
                                '                                                </span>\n' +
                                '                      <span class="tool-item tool-delect mr5 ml5" data-index="+ result[i].id+"\n' +
                                '                            data-name="tool-delect">\n' +
                                '                                                    <img src="catalog/view/javascript/assets/delect.png" alt="">\n' +
                                '                                                </span>\n' +
                                '                      <span class="tool-item tool-editor" data-index="+ result[i].id+"\n' +
                                '                            data-name="tool-editor">\n' +
                                '                                                    <img src="catalog/view/javascript/assets/editor.png" alt="">\n' +
                                '                                                </span>\n' +
                                '                    </div>\n' +
                                '                  </div>\n' +
                                '                </div>\n' +
                                '                <p class="f12 c6">\n' +
                                '                  <span class="c028">'+result[i].filename+'</span>\n' +
                                '                </p>\n' +
                                '              </div>'
                        '                    </div>'


                      }
                      $(".manage-nav-contentitem").html(arr);
                    }
                  }
                })
                  // var html =  "<input type='hidden'  name='res[]' value='"+data+"'>";
                  $("#uploadifive-file_upload-file-"+index).append(html);
              }

              else
              {
                  alert('上传失败');
              }
          },
          onError :function (e,errorType,file) {
              console.log(errorType);
          },
          onCancel : function(file) {

          },
          onFallback : function() {
              alert("该浏览器无法使用!");
          },
      });
  }
  //添加图片

  $("#bar").sortable();
  $("#bar").disableSelection();

  //拖动
  function getCurrentTagSort()
  {
    var sorts1 = [];
    var count = $('#bar li').length;
    $('#bar li').each(function(index, element) {
      var cate = {};
      var _this = $(this);
      cate.id=_this.attr("data-id");
      cate.sort = count-index;
      sorts1[index] = cate;
    });
    return  JSON.stringify(sorts1);
  }
  var ori_tag_sorts = '';
  var drag_image = false;
  $(document).ready(function(){
    $('#bar li').each(function(b, a) {
      var c =  $(this).attr('data-id');
      ori_tag_sorts = getCurrentTagSort();
    })
  });
  // 标签拖动
  $('#bar').bind('sortstop', function(event, ui){
    if(drag_image)
    {
      drag_image =false;
      return;
    }
    var new_sorts2 = getCurrentTagSort(); // 获取调整后的排序
    if(new_sorts2==ori_tag_sorts)
    {
      return;
    }
    var postData = {};
    postData['data'] = new_sorts2;
    $.ajax({
      url: 'index.php?route=account/section/uploadDesign',
      type: 'post',
      cache: false,
      data: postData,
      dataType: "json",
      error: function(result) {
        //process for error
      }, //服务器响应格式  status为1表示正常 数据在data里面 为0表示错误 消息在msg里面
      success: function(result) {
        if(!result){
          // alert('server error');
        }
        else{
          if(result.status == 1) {
            // alert('操作成功');

            ori_tag_sorts = new_sorts2;
            // location.reload();
          } else {
            // alert(result.msg);
          }
        }
      }
    });
  } );

  $("#barpage").sortable();
  $("#barpage").disableSelection();
  function getCurrentTagSort()
  {
    var sorts1 = [];
    var count = $('#barpage li').length;
    $('#barpage li').each(function(index, element) {
      var cate = {};
      var _this = $(this);
      cate.id=_this.attr("data-id");
      cate.sort = count-index;
      sorts1[index] = cate;
    });
    return  JSON.stringify(sorts1);
  }
  var ori_tag_sorts = '';
  var drag_image = false;
  $(document).ready(function(){
    $('#barpage li').each(function(b, a) {
      var c =  $(this).attr('data-id');
      ori_tag_sorts = getCurrentTagSort();
    })
  });
  // 标签拖动
  $('#barpage').bind('sortstop', function(event, ui){
    if(drag_image)
    {
      drag_image =false;
      return;
    }
    var new_sorts2 = getCurrentTagSort(); // 获取调整后的排序
    if(new_sorts2==ori_tag_sorts)
    {
      return;
    }
    var postData = {};
    postData['data'] = new_sorts2;
    $.ajax({
      url: 'index.php?route=account/section/uploadDesign',
      type: 'post',
      cache: false,
      data: postData,
      dataType: "json",
      error: function(result) {
        //process for error
      }, //服务器响应格式  status为1表示正常 数据在data里面 为0表示错误 消息在msg里面
      success: function(result) {
        if(!result){
          // alert('server error');
        }
        else{
          if(result.status == 1) {
            // alert('操作成功');

            ori_tag_sorts = new_sorts2;
            // location.reload();
          } else {
            // alert(result.msg);
          }
        }
      }
    });
  } );
</script>
</body>

</html>