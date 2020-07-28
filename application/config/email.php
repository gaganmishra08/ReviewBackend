<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
| -------------------------------------------------------------------
| Email Settings
| -------------------------------------------------------------------
| Configuration of outgoing mail server.
| */

$config['protocol'] = 'mail';
$config['smtp_host'] = 'mail.reviewit.site';
$config['smtp_port'] = '465';
$config['smtp_timeout'] = '30';
$config['smtp_user'] = 'noreply@reviewit.site';
$config['smtp_pass'] = 'Password#123';
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = true;
$config['newline'] = "\r\n";
