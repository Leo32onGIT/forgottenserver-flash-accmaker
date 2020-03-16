<?php
if(!defined('INITIALIZED'))
	exit;

if(!$logged)
	if($action == "logout")
		{
		$main_content .= '<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Logout</h3>
		</div>
		<div class="panel-body">
			<p>You have successfully logged out.</p>
			<div class="text-center">
				<a href="?view=account" class="btn btn-default">Back</a>
			</div>
		</div>
		</div>';
		}
	else
	{
		if(isset($isTryingToLogin))
		{
			switch(Visitor::getLoginState())
			{
				case Visitor::LOGINSTATE_NO_ACCOUNT:
					$main_content .= '<div class="alert alert-danger">An account with that name does not exist.</div>';
					break;
				case Visitor::LOGINSTATE_WRONG_PASSWORD:
					$main_content .= '<div class="alert alert-danger">The password you have entered is incorrect.</div>';
					break;
			}
		}
		$main_content .= '
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Login</h3>
			</div>
			<div class="panel-body">
				<p style="margin-bottom: 20px">Please enter your account name and your password.<br />
				<a href="?view=register" >Create an account</a> if you do not have one yet.</p>

				<form class="form-horizontal" role="form" action="?view=account" method="post">
					<fieldset>

						<div class="form-group">
							<label for="account_login"$voc class="col-lg-2 control-label">Account Name</label>
							<div class="col-lg-10">
								<input class="form-control" id="account_login" name="account_login" placeholder="" maxlength="30" required>
							</div>
						</div>

						<div class="form-group">
							<label for="password_login" class="col-lg-2 control-label">Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" id="password_login" name="password_login" placeholder="" maxlength="50" required>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a class="btn btn-danger" href="?view=lostaccount">Account Lost?</a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>';
	}
else
{
	if($action == "")
	{
		$main_content .= '<div class="panel panel-default"><div class="panel-heading">
		<h3 class="panel-title">Account Management</h3></div>
		<div class="panel-body">';
		$account_name = $account_logged->getName();
		$account_created = $account_logged->getCreateDate();
		$account_email = $account_logged->getEMail();
		$rec_key = $account_logged->getCustomField("key");
		$main_content .= '<div class="panel panel-default">
			<div class="panel-heading" style="font-weight: bold">General Information</div>
			<table class="table table-striped table-condensed table-bordered">
				<tr>
					<td width="50%" style="font-weight: bold">Account Name</td>
					<td style="text-align: left; padding-left: 10%">
						<span id="DisplayAccount">'.$account_name.'</span>
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold">E-mail Address</td>
					<td style="text-align: left; padding-left: 10%">
						<span id="DisplayEmail">'.$account_email.'</span>
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold">Creation Date</td>
					<td style="text-align: left; padding-left: 10%">'.date("j F Y, g:i a", $account_created).'</td>
				</tr>
				<!--<tr>
					<td style="font-weight: bold">Last Login</td>
					<td>' . (($account_logged->getLastLogin() > 0) ? date("j F Y, g:i a", $account_logged->getLastLogin()) : 'Never logged in.') . '</td>
				</tr>-->
			</table>
		</div>
		<div class="panel-default">
			<a href="?view=account&action=changepassword" class="btn btn-primary btn-sm"><i class="fa fa-lock"></i> Change Password</a>
			<a href="?view=account&action=changeemail" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"></i> Change Email</a>
			'.(!$rec_key ? '<a href="?view=account&action=generatekey" class="btn btn-primary btn-sm"><i class="fa fa-key"></i> Generate Recovery Key</a>' : '').'
			<a class="btn btn-sm btn-success" href="?view=account&action=createcharacter"><i class="fa fa-plus"></i> Create a new character</a>
			<a class="btn btn-sm btn-danger" href="?view=account&action=deletecharacter"><i class="fa fa-trash"></i> Delete a character</a>
		</div></br>';

		$main_content .= '<div class="panel panel-default"><div class="panel-heading" style="font-weight: bold">
		Characters</div><table class="table table-condensed table-content table-striped"><tbody>';
		$player_number_counter = 0;
		$account_players = $account_logged->getPlayersList();
		foreach ($account_players as $account_player) {
			$player_number_counter++;
			$main_content .= '<tr><td style="vertical-align: middle">'.$player_number_counter.'.</td><td style="text-align: left; vertical-align: middle">';
			if ($account_player->isDeleted()) {
				$main_content .= '<strike>'.htmlspecialchars($account_player->getName()).'</strike>';
			} else {
				$main_content .= '<strong>'.htmlspecialchars($account_player->getName()).'</strong>';
			}
			
			$vocationname = "None";
			if ($account_player->getVocation() != 0) {
				$vocationname = Website::getVocationName($account_player->getVocation());
			}
			if ($account_player->getGroupID() == 2) {
				$vocationname = "Gamemaster";
			} elseif ($account_player->getGroupID() >= 3) {
				$vocationname = "Administrator";
			}
			
			$main_content .= '<td style="text-align: left; font-size: 12px; color: darkgray; vertical-align: middle">'.htmlspecialchars($vocationname).'</td>';
			$main_content .= '<td style="text-align: right">';
			
			if ($account_player->isDeleted()) {
				$main_content .= '<a class="btn btn-sm btn-warning" href="?view=account&action=undelete&name='.urlencode($account_player->getName()).'"><i class="fa fa-undo"></i> Undelete</a> ';
			}
			$main_content .= '<a class="btn btn-sm btn-success" href="?view=play&name='.urlencode($account_player->getName()).'"><i class="fa fa-gamepad"></i> Play</a> ';
			$main_content .= '<a class="btn btn-sm btn-primary" href="?view=account&action=editcharacter&name='.urlencode($account_player->getName()).'"><i class="fa fa-pencil"></i> Edit</a></td></td></tr>';
		}
		if ($player_number_counter == 0) {
			$main_content .= '</br><div>You don\'t have any characters yet.</div></br>';
		}
		$main_content .= '</tbody></table></div>';
		$main_content .= '<a class="btn btn-danger" href="?view=account&action=logout"><i class="fa fa-sign-out"></i> Logout</a>
						  </div></div>';

	}
//########### CHANGE PASSWORD ##########
	if ($action == "changepassword") {
		$new_password = trim($_POST['newpassword']);
		$new_password2 = trim($_POST['newpassword2']);
		$old_password = trim($_POST['oldpassword']);
		if (empty($new_password) && empty($new_password2) && empty($old_password)) {
			$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Change Password</h3></div><div class="panel-body">
				<p style="margin-bottom: 20px">Please enter your current password and choose a new password.</p>
				<form class="form-horizontal" role="form" action="?view=account&action=changepassword" method="post">
					<fieldset>

						<div class="form-group">
							<label for="oldpassword" class="col-lg-3 control-label">Current Password</label>
							<div class="col-lg-9">
								<input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="form-group">
							<label for="newpassword" class="col-lg-3 control-label">New Password</label>
							<div class="col-lg-9">
								<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="form-group">
							<label for="newpassword2" class="col-lg-3 control-label">Repeat Password</label>
							<div class="col-lg-9">
								<input type="password" class="form-control" id="newpassword2" name="newpassword2" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form>

			</div></div>';

		} else {
			if (empty($new_password) || empty($new_password2) || empty($old_password)) {
				$show_msgs[] = "Please fill in form.";
			}

			if ($new_password != $new_password2) {
				$show_msgs[] = "The new passwords do not match!";
			}

			if (empty($show_msgs)) {
				if (!check_password($new_password)) {
					$show_msgs[] = "New password contains illegal chars (a-z, A-Z and 0-9 only!) or length.";
				}

				if (!$account_logged->isValidPassword($old_password)) {
					$show_msgs[] = "Current password is incorrect!";
				}

				if ($new_password == $old_password) {
					$show_msgs[] = "You already have this as your password. Please pick a new one.";
				}

				if (strlen($new_password) < 6 || strlen($new_password) > 50)
					$show_msgs[] = 'The password must have at least 6 but no more than 50 characters!';
			}

			if (!empty($show_msgs)) {
				//show errors
				foreach ($show_msgs as $show_msg) {
					$main_content .= '<div class="alert alert-danger">'.$show_msg.'</div>';
				}

				//show form
				$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Change Password</h3></div><div class="panel-body">
				<p>Please enter your current password and a new password. For your security, please enter the new password twice.</p>
				<form class="form-horizontal" role="form" action="?view=account&action=changepassword" method="post">
					<fieldset>

						<div class="form-group">
							<label for="oldpassword" class="col-lg-3 control-label">Current Password</label>
							<div class="col-lg-9">
								<input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="form-group">
							<label for="newpassword" class="col-lg-3 control-label">New Password</label>
							<div class="col-lg-9">
								<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="form-group">
							<label for="newpassword2" class="col-lg-3 control-label">Repeat Password</label>
							<div class="col-lg-9">
								<input type="password" class="form-control" id="newpassword2" name="newpassword2" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form>

			</div></div>';
			} else {
				$org_pass = $new_password;
				$account_logged->setPassword($new_password);
				$account_logged->save();
				$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Password Changed</h3></div><div class="panel-body"><p>Your password has been changed successfully.</p><p><a href="?view=account" class="btn btn-default">Back</a></p></div></div>';
				if ($config['site']['send_emails'] && $config['site']['send_mail_when_change_password']) {
					$mailBody = '<html>
					<body>
					<h3>Password to account changed!</h3>
					<p>You or someone else changed password to your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a>.</p>
					<p>New password: <b>'.htmlspecialchars($org_pass).'</b></p>
					</body>
					</html>';
					$mail = new PHPMailer();
					if ($config['site']['smtp_enabled']) {
						$mail->IsSMTP();
						$mail->Host = $config['site']['smtp_host'];
						$mail->Port = (int)$config['site']['smtp_port'];
						$mail->SMTPAuth = $config['site']['smtp_auth'];
						$mail->Username = $config['site']['smtp_user'];
						$mail->Password = $config['site']['smtp_pass'];
					}
					else
						$mail->IsMail();
					$mail->IsHTML(true);
					$mail->From = $config['site']['mail_address'];
					$mail->AddAddress($account_logged->getEMail());
					$mail->Subject = $config['server']['serverName']." - Changed password";
					$mail->Body = $mailBody;
					if($mail->Send())
						$main_content .= '<br /><small>Your new password were send on email address <b>'.htmlspecialchars($account_logged->getEMail()).'</b>.</small>';
					else
						$main_content .= '<br /><small>An error occorred while sending email with password!</small>';
				}
				$_SESSION['password'] = $new_password;
			}
		}
	}

//############# CHANGE E-MAIL ###################
	if($action == "changeemail")
	{
		$account_email_new_time = $account_logged->getCustomField("email_new_time");
		if($account_email_new_time > 10) {$account_email_new = $account_logged->getCustomField("email_new"); }
		if($account_email_new_time < 10)
		{
			if($_POST['changeemailsave'] == 1)
			{
				$account_email_new = trim($_POST['new_email']);
				$post_password = trim($_POST['password']);
				if(empty($account_email_new))
				{
					$change_email_errors[] = "Please enter your new email address.";
				}
				else
				{
					if(!check_mail($account_email_new))
					{
						$change_email_errors[] = "E-mail address is not correct.";
					}
				}
				if(empty($post_password))
				{
					$change_email_errors[] = "Please enter password to your account.";
				}
				else
				{
					if(!$account_logged->isValidPassword($post_password))
					{
						$change_email_errors[] = "Wrong password to account.";
					}
				}
				if(empty($change_email_errors))
				{
					$account_email_new_time = time() + $config['site']['email_days_to_change'] * 24 * 3600;
					$account_logged->set("email_new", $account_email_new);
					$account_logged->set("email_new_time", $account_email_new_time);
					$account_logged->save();

					$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">E-mail Address Change Requested</h3></div><div class="panel-body"><p>You have requested to change your email address to <b>'.htmlspecialchars($account_email_new).'</b>. The actual change will take place after <b>'.date("j F Y, G:i:s", $account_email_new_time).'</b>, during which you can cancel the request at any time.</p><div class="text-center"><a href="?view=account" class="btn btn-primary">Back</a></div></div></div>';
				}
				else
				{
					//show errors
					foreach($change_email_errors as $change_email_error)
					{
						$main_content .= '<div class="alert alert-danger">'.$change_email_error.'</div>';
					}
					//show form


					$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Change E-mail Address</h3></div><div class="panel-body"><p>Please enter your password and a new email address. <b>For security reasons, the actual change will be finalised after a waiting period of '.$config['site']['email_days_to_change'].' days.</b></p>';
					$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=changeemail" method="post">
						<fieldset>

							<div class="form-group">
								<label for="email" class="col-lg-2 control-label">E-mail Address</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" id="new_email" name="new_email" value="'.htmlspecialchars($_POST['new_email']).'" placeholder="3 to 255 characters" maxlength="255" required>
								</div>
							</div>

							<div class="form-group">
								<label for="password" class="col-lg-2 control-label">Password</label>
								<div class="col-lg-10">
									<input type="password" class="form-control" id="password" name="password" placeholder="6 to 50 characters" maxlength="50" required>
								</div>
							</div>

							<div class="text-center">
								<input type="hidden" name="changeemailsave" value="1">
								<button type="submit" class="btn btn-primary">Submit</button>
								<a href="?view=account" class="btn btn-default">Back</a>
							</div>
							
						</fieldset>
					</form>';

					$main_content .= '</div></div>';
				}
			}
			else
			{
				$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Change E-mail Address</h3></div><div class="panel-body"><p style="margin-bottom: 20px">Please enter your password and a new email address.<br>For security reasons, the change will occur after a waiting period of '.$config['site']['email_days_to_change'].' days.</p>';
				$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=changeemail" method="post">
					<fieldset>

						<div class="form-group">
							<label for="email" class="col-lg-2 control-label">E-mail Address</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="new_email" name="new_email" value="'.htmlspecialchars($_POST['new_email']).'" placeholder="3 to 255 characters" maxlength="255" required>
							</div>
						</div>

						<div class="form-group">
							<label for="password" class="col-lg-2 control-label">Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" id="password" name="password" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="text-center">
							<input type="hidden" name="changeemailsave" value="1">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form>';

				$main_content .= '</div></div>';
			}
		}
		else
		{
			if($account_email_new_time < time())
			{
				if($_POST['changeemailsave'] == 1)
				{
					$account_logged->set("email_new", "");
					$account_logged->set("email_new_time", 0);
					$account_logged->setEmail($account_email_new);
					$account_logged->save();
					$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Email Address Changed</h3></div><div class="panel-body"><p>You have accepted <b>'.htmlspecialchars($account_logged->getEmail()).'</b> as your new email address.</p><div class="text-center"><a href="?view=account" class="btn btn-primary">Back</a></div></div></div>';
				}
				else
				{
					$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Change Email Address</h3></div><div class="panel-body"><p>Do you accept <b>'.htmlspecialchars($account_email_new).'</b> as your new email address?</p>';
					$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=changeemail" method="post">
					<fieldset>
						<div class="text-center">
							<input type="hidden" name="changeemailsave" value="1">
							<button type="submit" class="btn btn-success">Accept</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
					</form></div></div>';
				}
			}
			else
			{
				$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Change of Email Address</h3></div><div class="panel-body"><p>A request has been submitted to change the email address of this account to <b>'.htmlspecialchars($account_email_new).'</b>.</p><p>The actual change will take place on <b>'.date("j F Y, G:i:s", $account_email_new_time).'</b>.</p><p>If you do not want to change your email address, please click on "Cancel".</p>';
				$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=changeemail" method="post">
					<fieldset>

						<div class="text-center">
							<input type="hidden" name="emailchangecancel" value="1">
							<button type="submit" class="btn btn-danger">Cancel</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form></div></div>';
			}
		}
		if($_POST['emailchangecancel'] == 1)
		{
			$account_logged->set("email_new", "");
			$account_logged->set("email_new_time", 0);
			$account_logged->save();
			$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Email Address Change Cancelled</h3></div><div class="panel-body"><p>Your request to change the email address of your account has been cancelled. The email address will not be changed.</p><div class="text-center"><a href="?view=account" class="btn btn-primary">Back</a></div></div></div>';
		}
	}

//############## GENERATE RECOVERY KEY ###########
	if($action == "generatekey")
	{
		$reg_password = trim($_POST['reg_password']);
		$old_key = $account_logged->getCustomField("key");
		if($_POST['generatekeysave'] == "1")
		{
			if($account_logged->isValidPassword($reg_password))
			{
				if(empty($old_key))
				{
					$dontshowtableagain = 1;
					$acceptedChars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
					$max = strlen($acceptedChars)-1;
					$new_rec_key = NULL;
					// 10 = number of chars in generated key
					for($i=0; $i < 10; $i++) {
						$cnum[$i] = $acceptedChars{mt_rand(0, $max)};
						$new_rec_key .= $cnum[$i];
					}
					$account_logged->set("key", $new_rec_key);
					$account_logged->save();

					$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Recovery Key Generated</h3></div><div class="panel-body"><p>Thank you for registering your account! You can now recover your account if you have lost access to the assigned email address by using the following</p><font size="5"><b>Recovery Key: '.htmlspecialchars($new_rec_key).'</b></font></p><br/><b>Important:</b><p>Please write down the recovery key and store it at a safe place!</p><div class="text-center"><a href="?view=account" class="btn btn-primary">Back</a></div></div></div>';
					if($config['site']['send_emails'] && $config['site']['send_mail_when_generate_reckey'])
					{
						$mailBody = '<html>
						<body>
						<h3>New recovery key!</h3>
						<p>You or someone else generated recovery key to your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a>.</p>
						<p>Recovery key: <b>'.htmlspecialchars($new_rec_key).'</b></p>
						</body>
						</html>';
						$mail = new PHPMailer();
						if ($config['site']['smtp_enabled'])
						{
							$mail->IsSMTP();
							$mail->Host = $config['site']['smtp_host'];
							$mail->Port = (int)$config['site']['smtp_port'];
							$mail->SMTPAuth = $config['site']['smtp_auth'];
							$mail->Username = $config['site']['smtp_user'];
							$mail->Password = $config['site']['smtp_pass'];
						}
						else
							$mail->IsMail();
						$mail->IsHTML(true);
						$mail->From = $config['site']['mail_address'];
						$mail->AddAddress($account_logged->getEMail());
						$mail->Subject = $config['server']['serverName']." - recovery key";
						$mail->Body = $mailBody;
						if($mail->Send())
							$main_content .= '<br /><small>Your recovery key were send on email address <b>'.htmlspecialchars($account_logged->getEMail()).'</b>.</small>';
						else
							$main_content .= '<br /><small>An error occorred while sending email with recovery key! You will not receive e-mail with this key.</small>';
					}
				}
				else
					$reg_errors[] = 'Your account is already registred.';
			}
			else
				$reg_errors[] = 'Wrong password to account.';
		}
		if($dontshowtableagain != 1)
		{
			//show errors if not empty
			if(!empty($reg_errors))
			{
				foreach($reg_errors as $reg_error)
					$main_content .= '<div class="alert alert-danger">'.$reg_error.'</div>';
			}
			//show form
			$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Generate Recovery Key</h3></div><div class="panel-body"><p>To generate recovery key for your account please enter your password.</p>';
			$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=generatekey" method="post"><input type="hidden" name="generatekeysave" value="1">
					<fieldset>
						<div class="form-group">
							<label for="reg_password" class="col-lg-2 control-label">Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" id="reg_password" name="reg_password" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form></div></div>';

			}
	}

//###### CHANGE CHARACTER COMMENT ######
	if($action == "editcharacter")
	{
		$player_name = $_REQUEST['name'];
		$new_comment = htmlspecialchars(substr(trim($_POST['comment']),0,2000));
		$new_hideacc = (int) $_POST['accountvisible'];
		if(check_name($player_name))
		{
			$player = new Player();
			$player->find($player_name);
			if($player->isLoaded())
			{
				$player_account = $player->getAccount();
				if($account_logged->getId() == $player_account->getId())
				{
					if($_POST['editcharactersave'] == 1)
					{
						$player->set("hide_char", $new_hideacc);
						$player->set("comment", $new_comment);
						$player->save();

						$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Character Information Changed</h3></div><div class="panel-body"><p>The character information has been changed.</p><div class="text-center"><a href="?view=account" class="btn btn-primary">Back</a></div></div></div>';
					} else {
						$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Edit Character Information</h3></div><div class="panel-body">Here you can see and edit the information about your character.</br>If you do not want to specify a certain field, just leave it blank.';
						//$main_content .= '</br>';
						$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=editcharacter" method="post">
							<input type="hidden" name="name" value="'.htmlspecialchars($player->getName()).'">
							<input type="hidden" name="editcharactersave" value="1">
							<fieldset>
								<div class="form-group">
									<label for="name" class="col-lg-2 control-label">Name</label>
									<div class="col-lg-2">
								  		<p class="control-label">'.htmlspecialchars($player->getName()).'</p>
									</div>
								</div>

								<div class="form-group">
									<label for="sex" class="col-lg-2 control-label">Sex</label>
									<div class="col-lg-2">
								  		<p class="control-label">'.htmlspecialchars((($player->getSex() == 0) ? 'female' : 'male')).'</p>
									</div>
								</div>

								<div class="form-group">
									<label for="hide" class="col-lg-2 control-label">Hide Account</label>
									<div class="col-lg-10">
										<label class="checkbox-inline"><input type="checkbox" name="accountvisible" value="1" '.($player->getCustomField("hide_char") == 1 ? 'checked="checked"' : '').'>Check to hide your account information</label>
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="col-lg-2 control-label">Comment</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="3" id="comment" name="comment">'.$player->getCustomField("comment").'</textarea>
									</div>
								</div>

								<div class="text-center">
									<button type="submit" class="btn btn-primary">Submit</button>
									<a href="?view=account" class="btn btn-default">Back</a>
								</div>
							</fieldset>
						</form>';

						$main_content .= '</div></div>';
					}
				}
				else
				{
					$main_content .= "<div class='alert alert-danger'>Character <b>".htmlspecialchars($player_name)."</b> is not on your account.</div>";
				}
			}
			else
			{
				$main_content .= "<div class='alert alert-danger'>Character with this name doesn't exist.</div>";
			}
		}
		else
		{
			$main_content .= "<div class='alert alert-danger'>Name contain illegal characters.</div>";
		}
	}

//### DELETE character from account ###
	if($action == "deletecharacter")
	{
		$player_name = trim($_POST['delete_name']);
		$password_verify = trim($_POST['delete_password']);
		if($_POST['deletecharactersave'] == 1)
		{
			if(!empty($player_name) && !empty($password_verify))
			{
				if(check_name($player_name))
				{
					$player = new Player();
					$player->find($player_name);
					if($player->isLoaded())
					{
						$player_account = $player->getAccount();
						if($account_logged->getId() == $player_account->getId())
						{
							if($account_logged->isValidPassword($password_verify))
							{
								if(!$player->isOnline())
								{
									//dont show table "delete character" again
									$dontshowtableagain = 1;
									//delete player
									$player->set('deletion', 1);
									$player->set('deleted', 1);
									$player->save();
									$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Character Deleted</h3></div><div class="panel-body"><p>The character <strong>'.htmlspecialchars($player_name).'</strong> has been deleted.</p><div class="text-center"><a class="btn btn-primary" href="?view=account">Back</a></div></div></div>';
								}
								else
									$delete_errors[] = 'This character is online.';
							}
							else
							{
								$delete_errors[] = 'Wrong password to account.';
							}
						}
						else
						{
							$delete_errors[] = 'Character <b>'.htmlspecialchars($player_name).'</b> is not on your account.';
						}
					}
					else
					{
						$delete_errors[] = 'Character with this name doesn\'t exist.';
					}
				}
				else
				{
					$delete_errors[] = 'Name contain illegal characters.';
				}
			}
			else
			{
			$delete_errors[] = 'Character name or/and password is empty. Please fill in form.';
			}
		}
		if($dontshowtableagain != 1)
		{
			if(!empty($delete_errors))
			{
				foreach($delete_errors as $delete_error) {
					$main_content .= '<div class="alert alert-danger">'.$delete_error.'</div>';
				}
			}
			$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Delete Character</h3></div><div class="panel-body"><p style="margin-bottom: 20px">To delete a character enter the name of the character and your password.</p>';
			$main_content .= '<form class="form-horizontal" role="form" action="?view=account&action=deletecharacter" method="post"><input type="hidden" name="deletecharactersave" value="1">
					<fieldset>

						<div class="form-group">
							<label for="delete_name" class="col-lg-2 control-label">Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="delete_name" name="delete_name" placeholder="4 to 30 characters" maxlength="30" required>
							</div>
						</div>

						<div class="form-group">
							<label for="delete_password" class="col-lg-2 control-label">Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" id="delete_password" name="delete_password" placeholder="6 to 50 characters" maxlength="50" required>
							</div>
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form>';
			$main_content .= '</div></div>';
		}
	}


//### UNDELETE character from account ###
	if($action == "undelete")
	{
		$player_name = trim($_GET['name']);
		if(!empty($player_name))
		{
			if(check_name($player_name))
			{
				$player = new Player();
				$player->find($player_name);
				if($player->isLoaded())
				{
					$player_account = $player->getAccount();
					if($account_logged->getId() == $player_account->getId())
					{
						if(!$player->isOnline())
						{
							$player->set('deleted', 0);
							$player->set('deletion', 0);
							$player->save();
							$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Character Undeleted</h3></div><div class="panel-body"><p>The character <b>'.htmlspecialchars($player_name).'</b> has been undeleted.</p><div class="text-center"><a href="?view=account" class="btn btn-primary">Back</a></div></div></div>';
						}
						else
							$delete_errors[] = 'This character is online.';
					}
					else
						$delete_errors[] = 'Character <b>'.htmlspecialchars($player_name).'</b> is not on your account.';
				}
				else
					$delete_errors[] = 'Character with this name doesn\'t exist.';
			}
			else
				$delete_errors[] = 'Name contain illegal characters.';
		}
	}

	//## CREATE CHARACTER on account ###
	if ($action == "createcharacter")
	{
		if (isset($_POST['step']) && $_POST['step'] == 'create') {
			$newchar_name = ucwords(strtolower(trim($_POST['newcharname'])));
			$newchar_sex = $_POST['newcharsex'];
			$newchar_vocation = $_POST['newcharvocation'];

			if (empty($newchar_name)) {
				$newchar_errors[] = 'Please enter a name for your character!';
			}

			if (empty($newchar_sex) && $newchar_sex != "0") {
				$newchar_errors[] = 'Please select the sex for your character!';
			}

			if (count($config['site']['newchar_vocations']) > 1) {
				if (empty($newchar_vocation)) {
					$newchar_errors[] = 'Please select a vocation for your character.';
				}
			} else {
				$newchar_vocation = $config['site']['newchar_vocations'][0];
			}

			if (empty($newchar_errors)) {
				if (!check_name_new_char($newchar_name))
					$newchar_errors[] = 'This name contains invalid letters, words or format. Please use only a-Z, - , \' and space.';
				if ($newchar_sex != 1 && $newchar_sex != "0")
					$newchar_errors[] = 'Sex must be equal <b>0 (female)</b> or <b>1 (male)</b>.';
				if (count($config['site']['newchar_vocations']) > 1)
				{
					$newchar_vocation_check = FALSE;
					foreach($config['site']['newchar_vocations'] as $char_vocation_key => $sample_char)
						if($newchar_vocation == $char_vocation_key)
							$newchar_vocation_check = TRUE;
					if(!$newchar_vocation_check)
						$newchar_errors[] = 'Unknown vocation. Please fill in form again.';
				}
				else
					$newchar_vocation = 0;
			}

			if (empty($newchar_errors)) {
				$check_name_in_database = new Player();
				$check_name_in_database->find($newchar_name);
				if ($check_name_in_database->isLoaded())
					$newchar_errors[] .= 'This name is already used. Please choose another name!';
				$number_of_players_on_account = $account_logged->getPlayersList()->count();
				if ($number_of_players_on_account >= $config['site']['max_players_per_account'])
					$newchar_errors[] .= 'You have too many characters on your account <b>('.$number_of_players_on_account.'/'.$config['site']['max_players_per_account'].')</b>!';
			}

			if (empty($newchar_errors)) {
				$char_to_copy_name = $config['site']['newchar_vocations'][$newchar_vocation];
				$char_to_copy = new Player();
				$char_to_copy->find($char_to_copy_name);
				if (!$char_to_copy->isLoaded())
					$newchar_errors[] .= 'Wrong characters configuration. Try again or contact with admin. ADMIN: Edit file config/config.php and set valid characters to copy names. Character to copy <b>'.htmlspecialchars($char_to_copy_name).'</b> doesn\'t exist.';
			}
			
				$headcolour = array(78, 2, 58, 131, 39, 115, 56, 95, 37, 1, 20);
				$feetcolour = array(20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37);
				$bodycolour = array(rand(77,94), rand(96,113), rand(115,132));
				$malemagelooktype = array(130, 132, 133, 145, 432, 634);
				$malepaladinlooktype = array(129, 328, 684, 152);
				$maleknightlooktype = array(131, 134, 516, 541, 633);
				$femalemagelooktype = array(138, 141, 149, 279, 433, 635);
				$femalepaladinlooktype = array(137, 150, 156, 514, 683, 575);
				$femaleknightlooktype = array(139, 142, 269, 366, 513, 542, 632, 698);
			
			if (empty($newchar_errors)) {
			
				// load items and skills of player before we change ID
				$char_to_copy->getItems()->load();
				
				if($newchar_vocation == "1")
				{
					$char_to_copy->setHealthMax(645);
					$char_to_copy->setHealth(645);
					$char_to_copy->setManaMax(2850);
					$char_to_copy->setMana(2850);
					$char_to_copy->setCapacity(1390);
					// $char_to_copy->setSkill(5,30);
					// $char_to_copy->setMagLevel(100);
						if($newchar_sex == "1")
						$char_to_copy->setLookType(634);
						if($newchar_sex == "0")
						$char_to_copy->setLookType(433);
				}
				
				if($newchar_vocation == "2")
				{
					$char_to_copy->setHealthMax(1105);
					$char_to_copy->setHealth(1105);
					$char_to_copy->setManaMax(1470);
					$char_to_copy->setMana(1470);
					$char_to_copy->setCapacity(2310);
					// $char_to_copy->setSkill(4,100);
					// $char_to_copy->setSkill(5,70);
						if($newchar_sex == "1")
						$char_to_copy->setLookType(684);
						if($newchar_sex == "0")
						$char_to_copy->setLookType(514);
				}
				
				if($newchar_vocation == "3")
				{
					$char_to_copy->setHealthMax(1565);
					$char_to_copy->setHealth(1565);
					$char_to_copy->setManaMax(550);
					$char_to_copy->setMana(550);
					$char_to_copy->setCapacity(2770);
					// $char_to_copy->setSkill(1,100);
					// $char_to_copy->setSkill(2,100);
					// $char_to_copy->setSkill(3,100);
					// $char_to_copy->setSkill(5,100);
						if($newchar_sex == "1")
						$char_to_copy->setLookType(633);
						if($newchar_sex == "0")
						$char_to_copy->setLookType(698);
				}
				
				$char_to_copy->setLookHead($headcolour[rand(1,count($headcolour))]);
				$char_to_copy->setLookBody(rand(77,132));
				$char_to_copy->setLookLegs(95);
				$char_to_copy->setLookFeet($feetcolour[rand(1,count($feetcolour))]);
				$char_to_copy->setID(null); // save as new character
				$char_to_copy->setLastIP(0);
				$char_to_copy->setLastLogin(0);
				$char_to_copy->setLastLogout(0);
			    $char_to_copy->setName($newchar_name);
			    $char_to_copy->setAccount($account_logged);
			    $char_to_copy->setSex($newchar_sex);
				$char_to_copy->setVocation($newchar_vocation);
				$char_to_copy->setLevel(100);
			    $char_to_copy->setTown(1);
				$char_to_copy->setPosX(0);
				$char_to_copy->setPosY(0);
				$char_to_copy->setPosZ(0);
				$char_to_copy->setCreateIP(Visitor::getIP());
				$char_to_copy->setCreateDate(time());
				// $char_to_copy->setSave(); // make character saveable
				$char_to_copy->save(); // now it will load 'id' of new player
				if($char_to_copy->isLoaded()) {
					$char_to_copy->saveItems();
					$main_content .= '<div class="alert alert-success"><p>The character <strong>'.htmlspecialchars($newchar_name).'</strong> has been created. See you on '.$config['server']['serverName'].'!</p></div>';
				}
				else 
				{
					echo "Error. Can\'t create character. Probably problem with database. Try again or contact with admin.";
					exit;
				}
			}

			if (!empty($newchar_errors)) {
				foreach ($newchar_errors as $newchar_error) {
					$main_content .= '<div class="alert alert-danger">'.$newchar_error.'</div>';
				}
			}
		} else $_POST['step'] = '';
		
			
			
			$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Create Character</h3></div><div class="panel-body">
				<p style="margin-bottom: 20px">Please choose a name, vocation and gender for your character.</p>
				<form class="form-horizontal" role="form" action="?view=account&action=createcharacter" method="post">
					<fieldset>

						<div class="form-group">
							<label for="newcharname" class="col-lg-2 control-label">Name</label>
							<div class="col-lg-9">
								<input type="text" class="form-control" id="newcharname" name="newcharname" placeholder="2 to 30 characters" onkeyup="checkName();" value="'.htmlspecialchars($newchar_name).'" maxlength="30" required>
								</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Vocation</label>
							<div class="col-lg-9" style="text-align: left">
								<select style="padding:5px" class="form-control" name="newcharvocation">';
								for ($vocation = 1; $vocation <= count(Website::getVocations()); $vocation++) {
									if ($vocation <= 4) { // Ignore promoted vocs
										$main_content .= '<option name="newcharvocation" value="'.$vocation.'">'.Website::getVocationName($vocation).'</option>';
									}
								}
			$main_content .= '</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Gender</label>
							<div class="col-lg-6" style="text-align: left">
								<label class="radio-inline"><input type="radio" name="newcharsex" value="1" checked="checked">Male</label>
								<label class="radio-inline"><input type="radio" name="newcharsex" value="0">Female</label>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" name="step" value="create" class="btn btn-primary">Submit</button>
							<a href="?view=account" class="btn btn-default">Back</a>
						</div>

					</fieldset>
				</form>

			</div></div>';
	}
}
