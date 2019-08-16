<?php

//------------------------------------------------------
//Approval email when autoapprove
$_['text_approve_subject']      = '%s - Your Affiliate Account has been activated!';
$_['text_approve_welcome']      = 'Welcome and thank you for registering at %s!';
$_['text_approve_login']        = 'Your account has now been created and you can log in by using your email address and password by visiting our website or at the following URL:';
$_['text_approve_services']     = 'Upon logging in, you will be able to generate tracking codes, track commission payments and edit your account information.';
$_['text_approve_thanks']       = 'Thanks,';

//------------------------------------------------------
//Text for the link to Customer account in Affiliate account.
//Will appear under Logout.
//Set it to '' (i.e. leave blank) to avoid displaying this link.
$_['text_link_to_customer_in_affiliate'] = 'My Customer account';

//------------------------------------------------------
//Texts for the link to Affiliate account in Customer account.
//Will appear under Logout.
//In different situations different text will be used.
//You can set any of the texts to '' to avoid displaying the link in the particular situation.

//When affiliate account is created and active
$_['text_link_to_affiliate_in_customer'] = 'My Affiliate account';

//When affiliate account is not created yet - create it with signle click
$_['text_link_to_create_affiliate_in_customer'] = 'Create Affiliate account';

//When affiliate account is not approved yet
$_['text_link_to_affiliate_not_approved_in_customer'] = 'My Affiliate account (not approved yet)';

//When affiliate account is disabled (by admin)
$_['text_link_to_affiliate_disabled_in_customer'] = 'My Affiliate account (currently inactive)';

//------------------------------------------------------
//Texts to appear on intermediate page when the user tries to create affiliate account or
//access it while it is not yet approved or disabled.

//The title of the page before creating an affiliate account.
$_['heading_title_create_affiliate_account'] = 'Create Affiliate Account';

//This text will be displayed in the confirmation dialog 
//before creating an affiliate account.
//Set it to '' (i.e. leave blank) to skip the dialog - affiliate account will be created
//right away without asking for confirmation
$_['text_create_affiliate_account_confirm'] = 'Please confirm that you are going to create an Affiliate account';

//Account created but not yet approved
$_['heading_title_account_not_approved'] = 'Your Affiliate Account Has Been Created!';
$_['text_account_not_approved'] = '<p>Thank you for registering for an affiliate account with %s!</p><p>You will be notified by email once your account has been activated by the store owner.</p><p>If you have ANY questions about the operation of this affiliate system, please <a href="%s">contact the store owner</a>.</p>';

//Account disabled
$_['heading_title_account_disabled'] = 'Your Affiliate Account is currently inactive';
$_['text_account_disabled'] = '<p>Your affiliate account is currently inactive.</p><p>If you have ANY questions about the operation of this affiliate system, please <a href="%s">contact the store owner</a>.</p>';

//------------------------------------------------------
//Texts related to affiliate earnings transfer to customer account
$_['text_affiliate_transaction_description'] = 'Transfer To Customer Account';
$_['text_customer_transaction_description'] = 'Transferred From Affiliate Earnings';
$_['text_credit_amount_message'] = 'Your customer account will be credited with: <b>%s</b>';
$_['entry_funds_transfer_input'] = 'Transfer Affiliate Earnings to Customer Account:<br /><span class="help">You can transfer up to %s</span>';
$_['button_funds_transfer'] = 'Transfer Earnings Now';
$_['error_invalid_transfer_amount'] = 'Amount you are trying to transfer is not valid';

//------------------------------------------------------
//Texts for module/account_combine layout module, 
//which replaces module/account and module/affiliate
$_['text_account']  = 'Account';
$_['text_Store Credit'] = 'Store Credit';
$_['entry_affiliate_address'] = 'Affiliate Address:';
$_['text_customer'] = 'Customer';
$_['text_affiliate'] = 'Affiliate';

$_['text_affiliate_account'] = 'Create Affiliate Account';
$_['text_affiliate_info'] = 'Affiliate Information';
$_['text_affiliate_tracking'] = 'Affiliate Tracking';
$_['text_affiliate_transaction'] = 'Affiliate Store Credit';

