<?php

//------------------------------------------------------
//Approval email when autoapprove
$_['text_approve_subject']      = '%s - 你的推广奖励账户已经激活！马上邀请朋友吧！';
$_['text_approve_welcome']      = '欢迎参加我们的推广奖励计划 %s！';
$_['text_approve_login']        = '你的推广奖励账户已经创建，请访问我们的网站或者点击下面的链接登录：';
$_['text_approve_services']     = '登录后，你可以生成推广奖励代码，选择奖励支付方式，以及更改账户信息。';
$_['text_approve_thanks']       = '谢谢！';

//------------------------------------------------------
//Text for the link to Customer account in Affiliate account.
//Will appear under Logout.
//Set it to '' (i.e. leave blank) to avoid displaying this link.
$_['text_link_to_customer_in_affiliate'] = '我的会员账户';

//------------------------------------------------------
//Texts for the link to Affiliate account in Customer account.
//Will appear under Logout.
//In different situations different text will be used.
//You can set any of the texts to '' to avoid displaying the link in the particular situation.

//When affiliate account is created and active
$_['text_link_to_affiliate_in_customer'] = '我的推广奖励账户';

//When affiliate account is not created yet - create it with signle click
$_['text_link_to_create_affiliate_in_customer'] = '邀请好友，得奖励！';

//When affiliate account is not approved yet
$_['text_link_to_affiliate_not_approved_in_customer'] = '我的推广奖励账户（审核中）';

//When affiliate account is disabled (by admin)
$_['text_link_to_affiliate_disabled_in_customer'] = '我的推广奖励账户（未激活）';

//------------------------------------------------------
//Texts to appear on intermediate page when the user tries to create affiliate account or
//access it while it is not yet approved or disabled.

//The title of the page before creating an affiliate account.
$_['heading_title_create_affiliate_account'] = '邀请好友，得奖励！';

//This text will be displayed in the confirmation dialog 
//before creating an affiliate account.
//Set it to '' (i.e. leave blank) to skip the dialog - affiliate account will be created
//right away without asking for confirmation
$_['text_create_affiliate_account_confirm'] = '我要参加“邀好友，得奖励”计划';

//Account created but not yet approved
$_['heading_title_account_not_approved'] = '你的推广奖励账户已经创建！';
$_['text_account_not_approved'] = '<p>感谢你参加“邀请好友，得奖励！”推广奖励计划！</p><p>你将会收到一封确认电子邮件。</p><p>如果你对“邀请好友，得奖励！”推广奖励计划有任何问题，欢迎 <a href="%s">联系我们</a>.</p>';

//Account disabled
$_['heading_title_account_disabled'] = '你的推广奖励账户尚未激活';
$_['text_account_disabled'] = '<p>你的推广奖励账户尚未激活。</p><p>如果你对“邀请好友，得奖励！”推广奖励计划有任何问题，欢迎<a href="%s">联系我们</a>.</p>';

//------------------------------------------------------
//Texts related to affiliate earnings transfer to customer account
$_['text_affiliate_transaction_description'] = '转账到会员账户';
$_['text_customer_transaction_description'] = '来自推广奖励账户的转账';
$_['text_credit_amount_message'] = '你的会员账户收支：<b>%s</b>';
$_['entry_funds_transfer_input'] = '从推广奖励账户转入会员账户：<br /><span class="help">可转账限额 %s</span>';
$_['button_funds_transfer'] = '马上转账';
$_['error_invalid_transfer_amount'] = '无效金额';

//------------------------------------------------------
//Texts for module/account_combine layout module, 
//which replaces module/account and module/affiliate
$_['text_account']  = '账户';
$_['text_transactions'] = '推广交易记录';
$_['entry_affiliate_address'] = '地址：';
$_['text_customer'] = '会员';
$_['text_affiliate'] = '推广奖励';

$_['text_affiliate_account'] = '加盟';
$_['text_affiliate_info'] = '推广奖励信息';
$_['text_affiliate_tracking'] = '推广代码';
$_['text_affiliate_transaction'] = '推广交易记录';

