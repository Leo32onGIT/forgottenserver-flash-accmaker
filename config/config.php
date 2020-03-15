<?PHP
# Account Maker Config
$config['site']['url'] = '';
$config['site']['serverPath'] = "";
$config['site']['useServerConfigCache'] = true;
$towns_list = array(1 => 'Rook');

# Create Account Options
$config['site']['one_email'] = false;
$config['site']['create_account_verify_mail'] = false;
$config['site']['verify_code'] = true;
$config['site']['email_days_to_change'] = 3;
$config['site']['newaccount_premdays'] = 999;
$config['site']['send_register_email'] = false;

# Create Character Options
$config['site']['newchar_vocations'] = array(1 => 'Blank', 2 => 'Blank', 3 => 'Blank', 4 => 'Blank'); // All newchar vocations use Blank as template
$config['site']['newchar_towns'] = array(1);
$config['site']['max_players_per_account'] = 25;

# Emails Config
$config['site']['send_emails'] = true;
$config['site']['mail_address'] = "email@example.com";
$config['site']['smtp_enabled'] = false;
$config['site']['smtp_host'] = "";
$config['site']['smtp_port'] = 465;
$config['site']['smtp_auth'] = true;
$config['site']['smtp_user'] = "email@example.com";
$config['site']['smtp_pass'] = "";

# PAGE: whoisonline.php
$config['site']['private-servlist.com_server_id'] = 0;
/*
Server id on 'private-servlist.com' to show Players Online Chart (whoisonline.php page), set 0 to disable Chart feature.
To use this feature you must register on 'private-servlist.com' and add your server.
Format: number, 0 [disable] or higher
*/

# PAGE: characters.php
$config['site']['quests'] = array();
$config['site']['show_skills_info'] = false;
$config['site']['show_vip_storage'] = 0;

# PAGE: account.php
$config['site']['send_mail_when_change_password'] = false;
$config['site']['send_mail_when_generate_reckey'] = false;
$config['site']['generate_new_reckey'] = false;
$config['site']['generate_new_reckey_price'] = 500;

# PAGE: lostaccount.php
$config['site']['email_lai_sec_interval'] = 180;

# Layout Config
$config['site']['vdarkborder'] = '#505050';
$config['site']['darkborder'] = '#D4C0A1';
$config['site']['lightborder'] = '#F1E0C6';
$config['site']['download_page'] = false;
$config['site']['serverinfo_page'] = true;
$config['site']['betakeys'] = ['A1B2C3D4', 'E5F6G7H8'];