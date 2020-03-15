<?php
if(!defined('INITIALIZED'))
	exit;

if($config['site']['send_emails'])
{
	if($action == '')
	{
		if (isset($_SESSION['errors'])) {
			foreach ($_SESSION['errors'] as $error) {
				$main_content .= '<div class="alert alert-danger">'.$error.'</div>';
			}

			unset($_SESSION['errors']);
		}

		$main_content .= '
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Lost Account Interface</h3>
				</div>
				<div class="panel-body">
					<p style="margin-bottom: 20px">Please enter your character name and how you would like to recover your account.</p>
					<form class="form-horizontal" role="form" action="?view=lostaccount&action=step1" method="post">
						<input type="hidden" name="character" value="">
						<fieldset>

							<div class="form-group">
								<label for="nick" class="col-lg-3 control-label">Character Name</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="nick" name="nick" placeholder="2 to 30 characters" maxlength="30" required>
								</div>
							</div>

							<div class="form-group">
								<label for="select" class="col-lg-3 control-label">Option</label>
								<div class="col-lg-9">
									<select class="form-control" name="action_type">
										<option value="email">Use email address to receive account name and a new password</option>
										<option value="reckey">Use recovery key to change your email and password</option>
									</select>
								</div>
							</div>

							<div class="text-center">
								<button type="submit" class="btn btn-primary">Submit</button>
								<a href="?view=account" class="btn btn-default">Back</a>
							</div>

						</fieldset>
					</form>
				</div>
			</div>
		';
	}
	elseif($action == 'step1' && $_REQUEST['action_type'] == 'email')
	{
		session_start();
		$nick = $_REQUEST['nick'];
		if (!check_name($nick)) {
			$errors[] = 'Invalid player name format. If you have other characters on account try with other name.';
		}

		$player = new Player();
		$account = new Account();
		$player->find($nick);
		if ($player->isLoaded()) {
			$account = $player->getAccount();
			if ($account->isLoaded()) {
				if ($account->getCustomField('email_new') > time()) {
					$insec = $account->getCustomField('email_new') - time();
					$minutesleft = floor($insec / 60);
					$secondsleft = $insec - ($minutesleft * 60);
					$timeleft = $minutesleft.' minutes '.$secondsleft.' seconds';
					$errors[] = 'Account of selected character (<b>'.htmlspecialchars($nick).'</b>) received e-mail in last '.ceil($config['site']['email_lai_sec_interval'] / 60).' minutes. You must wait '.$timeleft.' before you can use Lost Account Interface again.';
				}
			} else {
				$errors[] = 'Account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
			}
		} else {
			$errors[] = 'Player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}


		if (!empty($errors)) {
			$_SESSION['errors'] = $errors;
			header('Location: ?view=lostaccount');
			return;
		}

		if (isset($_SESSION['errors'])) {
			foreach ($_SESSION['errors'] as $error) {
				$main_content .= '<div class="alert alert-danger">'.$error.'</div>';
			}

			unset($_SESSION['errors']);
		}

		$main_content .= '
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Lost Account Interface</h3>
				</div>
				<div class="panel-body">
					<p>Please enter e-mail to account with this character.</p><br>

					<form class="form-horizontal" role="form" action="?view=lostaccount&action=sendcode" method="post">
						<input type="hidden" name="character" value="">
						<fieldset>

							<div class="form-group">
								<label for="nick" class="col-lg-3 control-label">Character</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="nick" name="nick" value="'.htmlspecialchars($nick).'" placeholder="" maxlength="30" readonly="readonly">
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-lg-3 control-label">E-mail Address</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="email" name="email" placeholder="3 to 255 characters" maxlength="255" required>
								</div>
							</div>

							<div class="text-center">
								<button type="submit" class="btn btn-primary">Submit</button>
								<a class="btn btn-default" href="?view=lostaccount">Back</a>
							</div>

						</fieldset>
					</form>
				</div>
			</div>
		';
	}
	elseif($action == 'sendcode')
	{
		session_start();
		$email = $_REQUEST['email'];
		$nick = $_REQUEST['nick'];
		if (!check_name($nick)) {
			$errors[] = 'Invalid player name format. If you have other characters on account try with other name.';
		}

		$acceptedChars = '123456789zxcvbnmasdfghjklqwertyuiop';
		$newcode = NULL;
		for ($i = 0; $i < 30; $i++) {
			$cnum[$i] = $acceptedChars{mt_rand(0, 33)};
			$newcode .= $cnum[$i];
		}

		$player = new Player();
		$account = new Account();
		$player->find($nick);
		if ($player->isLoaded()) {
			$account = $player->getAccount();
			if ($account->isLoaded()) {
				if ($account->getCustomField('email_new') < time()) {
					if ($account->getEMail() == $email) {

						$mailBody = '<html>
						<body>
						<h3>Your account name and password!</h3>
						<p>You or someone else requested new password for  your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a> with this e-mail.</p>
						<p>Account name: '.htmlspecialchars($account->getName()).'</p>
						<p>Password: <i>You will set new password when you press on link.</i></p>
						<br />
						<p>Press on link to set new password. This link will work until next >new password request< in Lost Account Interface.</p>
						<p><a href="'.$config['server']['url'].'/?view=lostaccount&action=checkcode&code='.urlencode($newcode).'&character='.urlencode($nick).'">'.$config['server']['url'].'/?view=lostaccount&action=checkcode&code='.urlencode($newcode).'&character='.urlencode($nick).'</a></p>
						<p>or open page: <i>'.$config['server']['url'].'/?view=lostaccount&action=checkcode</i> and in field "code" write <b>'.htmlspecialchars($newcode).'</b></p>
						<br /><p>If you don\'t want to change password to your account just delete this e-mail.
						<p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
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
						} else {
							$mail->IsMail();
						}

						$mail->IsHTML(true);
						$mail->From = $config['site']['mail_address'];
						$mail->AddAddress($account->getCustomField('email'));
						$mail->Subject = $config['server']['serverName']." - Link to >set new password to account<";
						$mail->Body = $mailBody;

						if (!$mail->Send()) {
							$account->set('email_new', (time() + 0));
							$account->save();
							$errors[] = 'An error occured while sending email! Try again or contact with admin.';
						}

					} else {
						$errors[] = 'Invalid e-mail to account of character <b>'.htmlspecialchars($nick).'</b>. Try again.';
					}
				} else {
					$insec = $account->getCustomField('email_new') - time();
					$minutesleft = floor($insec / 60);
					$secondsleft = $insec - ($minutesleft * 60);
					$timeleft = $minutesleft.' minutes '.$secondsleft.' seconds';
					$errors[] = 'Account of selected character (<b>'.htmlspecialchars($nick).'</b>) received e-mail in last '.ceil($config['site']['email_lai_sec_interval'] / 60).' minutes. You must wait '.$timeleft.' before you can use Lost Account Interface again.';
				}

			} else {
				$errors[] = 'Account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
			}
		} else {
			$errors[] = 'Player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}

		if (!empty($errors)) {
			$_SESSION['errors'] = $errors;
			header('Location: ?view=lostaccount&action=step1&action_type=email&nick='.urlencode($nick));
			return;
		}

		$account->set('email_code', $newcode);
		$account->set('email_new', (time() + 0));
		$account->save();

		$main_content .= '
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">E-mail Sent!</h3>
				</div>
				<div class="panel-body">
					<p>Link with informations needed to set new password has been sent to account e-mail address. You should receive this e-mail within 15 minutes. Please check your inbox/spam directory.</p><br>

					<div class="text-center">
						<a class="btn btn-default" href="'.$config['site']['url'].'">Back</a>
					</div>
				</div>
			</div>
		';
	}
	elseif($action == 'step1' && $_REQUEST['action_type'] == 'reckey')
	{
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					$main_content .= '
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Lost Account Interface</h3>
							</div>
							<div class="panel-body">
								<p>If you enter right recovery key you will see form to set new e-mail and password to account. To this e-mail will be send your new password and account name.</p><br>

								<form class="form-horizontal" role="form" action="?view=lostaccount&action=step2" method="post">
									<fieldset>

										<div class="form-group">
											<label for="nick" class="col-lg-3 control-label">Character Name</label>
											<div class="col-lg-9">
												<input type="text" class="form-control" id="nick" name="nick" value="'.htmlspecialchars($nick).'" placeholder="" maxlength="30" readonly="readonly">
											</div>
										</div>

										<div class="form-group">
											<label for="email" class="col-lg-3 control-label">Recovery Key</label>
											<div class="col-lg-9">
												<input type="text" class="form-control" id="key" name="key" placeholder="" maxlength="255" required>
											</div>
										</div>

										<div class="text-center">
											<button type="submit" class="btn btn-primary">Submit</button>
											<a class="btn btn-default" href="?view=lostaccount">Back</a>
										</div>

									</fieldset>
								</form>
							</div>
						</div>
					';
				}
				else
					$main_content .= '<div class="alert alert-danger">Account for this character has no recovery key!';
					$main_content .= '<div class="text-center" style="margin-top: 10px">
											<a class="btn btn-default" href="?view=lostaccount">Back</a>
										</div></div>';
			}
			else
				$main_content .= '<div class="alert alert-danger">Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist. <a class="alert-link" href="?view=lostaccount">Click here</a> to go back.</div>';
		}
		else
			$main_content .= '<div class="alert alert-danger">Invalid player name format. If you have other characters on account try with other name. <a class="alert-link" href="?view=lostaccount">Click here</a> to go back.</div>';
	}
	elseif($action == 'step2')
	{
		$rec_key = trim($_REQUEST['key']);
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					if($account_key == $rec_key)
					{
						$main_content .= '<script type="text/javascript">
						function validate_required(field,alerttxt)
						{
						with (field)
						{
						if (value==null||value==""||value==" ")
						  {alert(alerttxt);return false;}
						else {return true}
						}
						}
						function validate_email(field,alerttxt)
						{
						with (field)
						{
						apos=value.indexOf("@");
						dotpos=value.lastIndexOf(".");
						if (apos<1||dotpos-apos<2)
						  {alert(alerttxt);return false;}
						else {return true;}
						}
						}
						function validate_form(thisform)
						{
						with (thisform)
						{
						if (validate_required(email,"Please enter your e-mail!")==false)
						  {email.focus();return false;}
						if (validate_email(email,"Invalid e-mail format!")==false)
						  {email.focus();return false;}
						if (validate_required(passor,"Please enter password!")==false)
						  {passor.focus();return false;}
						if (validate_required(passor2,"Please repeat password!")==false)
						  {passor2.focus();return false;}
						if (passor2.value!=passor.value)
						  {alert(\'Repeated password is not equal to password!\');return false;}
						}
						}
						</script>';


						$main_content .= '
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Lost Account Interface</h3>
								</div>
								<div class="panel-body">
									<p>Set new password and e-mail to your account..</p><br>

									<form class="form-horizontal" role="form" action="?view=lostaccount&action=step3" method="post" onsubmit="return validate_form(this)">
										<input type="hidden" name="character" value="">
										<input type="hidden" name="key" value="'.htmlspecialchars($rec_key).'">
										<fieldset>

											<div class="form-group">
												<label for="nick" class="col-lg-3 control-label">Character Name</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="nick" name="nick" value="'.htmlspecialchars($nick).'" placeholder="" maxlength="30" readonly="readonly">
												</div>
											</div>

											<div class="form-group">
												<label for="password" class="col-lg-3 control-label">New Password</label>
												<div class="col-lg-9">
													<input type="password" class="form-control" id="passor" name="passor" placeholder="" maxlength="50" required>
												</div>
											</div>

											<div class="form-group">
												<label for="password2" class="col-lg-3 control-label">Repeat Password</label>
												<div class="col-lg-9">
													<input type="password" class="form-control" id="passor2" name="passor2" placeholder="" maxlength="50" required>
												</div>
											</div>

											<div class="form-group">
												<label for="email" class="col-lg-3 control-label">New Email Address</label>
												<div class="col-lg-9">
													<input type="email" class="form-control" id="email" name="email" placeholder="" maxlength="255" required>
												</div>
											</div>

											<div class="text-center">
												<button type="submit" class="btn btn-primary">Submit</button>
												<a class="btn btn-default" href="?view=lostaccount">Back</a>
											</div>

										</fieldset>
									</form>
								</div>
							</div>
						';
					}
					else
						$main_content .= '<div class="alert alert-danger">Wrong recovery key! <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
				}
				else
					$main_content .= '<div class="alert alert-danger">Account of this character has no recovery key! <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
			}
			else
				$main_content .= '<div class="alert alert-danger">Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist. <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
		}
		else
			$main_content .= '<div class="alert alert-danger">Invalid player name format. If you have other characters on account try with other name. <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
	}
	elseif($action == 'step3')
	{
		$rec_key = trim($_REQUEST['key']);
		$nick = $_REQUEST['nick'];
		$new_pass = trim($_REQUEST['passor']);
		$new_email = trim($_REQUEST['email']);
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					if($account_key == $rec_key)
					{
						if(check_password($new_pass))
						{
							if(check_mail($new_email))
							{
								$account->setEMail($new_email);
								$account->setPassword($new_pass);
								$account->save();
								$main_content .= '
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">Lost Account Interface</h3>
										</div>
										<div class="panel-body">
											<p>Here is your new account information.</p>
											<form class="form-horizontal" role="form" action="" method="post">
												<fieldset>
													<div class="form-group">
														<label for="name" class="col-lg-2 control-label">Account Name</label>
														<div class="col-lg-10">
													  		<p class="form-control-static">'.htmlspecialchars($account->getName()).'</p>
														</div>
													</div>

													<div class="form-group">
														<label for="sex" class="col-lg-2 control-label">Password</label>
														<div class="col-lg-10">
													  		<p class="form-control-static">'.htmlspecialchars($new_pass).'</p>
														</div>
													</div>

													<div class="form-group">
														<label for="sex" class="col-lg-2 control-label">Email Address</label>
														<div class="col-lg-10">
													  		<p class="form-control-static">'.htmlspecialchars($new_email).'</p>
														</div>
													</div>

													<div class="text-center">
														<a class="btn btn-default" href="index.php?view=account">Back</a>
													</div>

												</fieldset>
											</form>
										</div>
									</div>
								';
								/*$main_content .= 'Your account name, new password and new e-mail.<BR>
								<FORM ACTION="?view=accountmanagement" onsubmit="return validate_form(this)" METHOD=post>
								<INPUT TYPE=hidden NAME="character" VALUE="">
								<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
								<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Your account name, new password and new e-mail</B></TD></TR>
								<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
								Account name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.htmlspecialchars($account->getName()).'</b><BR>
								New password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.htmlspecialchars($new_pass).'</b><BR>
								New e-mail address:&nbsp;<b>'.htmlspecialchars($new_email).'</b><BR>';*/
								if($account->getCustomField('email_new') < time())
								{
									$mailBody = '<html>
									<body>
									<h3>Your account name and new password!</h3>
									<p>Changed password and e-mail to your account in Lost Account Interface on server <a href="'.$config['server']['url'].'"><b>'.$config['server']['serverName'].'</b></a></p>
									<p>Account name: <b>'.htmlspecialchars($account->getName()).'</b></p>
									<p>New password: <b>'.htmlspecialchars($new_pass).'</b></p>
									<p>E-mail: <b>'.htmlspecialchars($new_email).'</b> (this e-mail)</p>
									<br />
									<p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
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
									$mail->AddAddress($account->getCustomField('email'));
									$mail->Subject = $config['server']['serverName']." - New password to your account";
									$mail->Body = $mailBody;
									$mail->Send();
									/*if($mail->Send())
									{
										$main_content .= '<br /><small>Sent e-mail with your account name and password to new e-mail. You should receive this e-mail in 15 minutes. You can login now with new password!';
									}*/
								}
							}
							else
								$main_content .= '<div class="alert alert-danger">Wrong e-mail format. <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
						}
						else
							$main_content .= '<div class="alert alert-danger">Wrong password format. Use only a-Z, A-Z, 0-9 <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
					}
					else
						$main_content .= '<div class="alert alert-danger">Wrong recovery key! <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
				}
				else
					$main_content .= '<div class="alert alert-danger">Account of this character has no recovery key! <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
			}
			else
				$main_content .= '<div class="alert alert-danger">Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist. <a class="alert-link" href="?view=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'">Click here</a> to go back.</div>';
		}
		else
			$main_content .= '<div class="alert alert-danger">Invalid player name format. If you have other characters on account try with other name.';
	}
	elseif($action == 'checkcode')
	{
		$code = trim($_REQUEST['code']);
		$character = trim($_REQUEST['character']);
		if(empty($code) || empty($character))
			$main_content .= '
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Lost Account Interface</h3>
					</div>
					<div class="panel-body">
						<p>Please enter the code you received in the email, and the name of one of your characters.</p>
						<form class="form-horizontal" role="form" action="?view=lostaccount&action=checkcode" method="post">
							<fieldset>

								<div class="form-group">
									<label for="nick" class="col-lg-3 control-label">Character Name</label>
									<div class="col-lg-9">
										<input type="text" class="form-control" id="character" name="character" value="" placeholder="" maxlength="30" required>
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="col-lg-3 control-label">Code</label>
									<div class="col-lg-9">
										<input type="text" class="form-control" id="code" name="code" placeholder="" maxlength="50" required>
									</div>
								</div>

								<div class="text-center">
									<button type="submit" class="btn btn-primary">Submit</button>
									<a class="btn btn-default" href="?view=lostaccount">Back</a>
								</div>

							</fieldset>
						</form>
					</div>
				</div>
			';
		else
		{
			$player = new Player();
			$account = new Account();
			$player->find($character);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('email_code') == $code)
				{
					$main_content .= '<script type="text/javascript">
					function validate_required(field,alerttxt)
					{
					with (field)
					{
					if (value==null||value==""||value==" ")
					  {alert(alerttxt);return false;}
					else {return true}
					}
					}

					function validate_form(thisform)
					{
					with (thisform)
					{
					if (validate_required(passor,"Please enter password!")==false)
					  {passor.focus();return false;}
					if (validate_required(passor2,"Please repeat password!")==false)
					  {passor2.focus();return false;}
					if (passor2.value!=passor.value)
					  {alert(\'Repeated password is not equal to password!\');return false;}
					}
					}
					</script>';

					$main_content .= '
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Lost Account Interface</h3>
							</div>
							<div class="panel-body">
								<p>Please enter a new password your account, and make sure to remember it this time.</p>
								<form class="form-horizontal" role="form" action="?view=lostaccount&action=setnewpassword" onsubmit="return validate_form(this)" method="post">
									<input type="hidden" name="character" value="'.htmlspecialchars($character).'">
									<input type="hidden" name="code" value="'.htmlspecialchars($code).'">
									<fieldset>

										<div class="form-group">
											<label for="nick" class="col-lg-3 control-label">New Password</label>
											<div class="col-lg-9">
												<input type="password" class="form-control" id="passor" name="passor" value="" placeholder="" maxlength="50" required>
											</div>
										</div>

										<div class="form-group">
											<label for="email" class="col-lg-3 control-label">Repeat Password</label>
											<div class="col-lg-9">
												<input type="password" class="form-control" id="passor2" name="passor2" placeholder="" maxlength="50" required>
											</div>
										</div>

										<div class="text-center">
											<button type="submit" class="btn btn-primary">Submit</button>
											<a class="btn btn-default" href="?view=lostaccount">Back</a>
										</div>

									</fieldset>
								</form>
							</div>
						</div>
					';
				}
				else
					$error= 'Wrong code to change password.';
			}
			else
				$error = 'Account of this character or this character doesn\'t exist.';
		}
		if(!empty($error))
			$main_content .= '
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Lost Account Interface</h3>
					</div>
					<div class="panel-body">
						<p>Please enter the code you received in the email, and the name of one of your characters.</p>
						<form class="form-horizontal" role="form" action="?view=lostaccount&action=checkcode" method="post">
							<fieldset>

								<div class="form-group">
									<label for="nick" class="col-lg-3 control-label">Character Name</label>
									<div class="col-lg-9">
										<input type="text" class="form-control" id="character" name="character" value="" placeholder="" maxlength="30" required>
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="col-lg-3 control-label">Code</label>
									<div class="col-lg-9">
										<input type="text" class="form-control" id="code" name="code" placeholder="" maxlength="50" required>
									</div>
								</div>

								<div class="text-center">
									<button type="submit" class="btn btn-primary">Submit</button>
									<a class="btn btn-default" href="?view=lostaccount">Back</a>
								</div>

							</fieldset>
						</form>
					</div>
				</div>
			';
	}
	elseif($action == 'setnewpassword')
	{
		$newpassword = $_REQUEST['passor'];
		$code = $_REQUEST['code'];
		$character = $_REQUEST['character'];
		$main_content .= '';
		if(empty($code) || empty($character) || empty($newpassword))
			$main_content .= '<div class="alert alert-danger">An error has occured, please try again. <a class="alert-link" href="?view=lostaccount&action=checkcode">Click here</a> to go back.</div>';
		else
		{
			$player = new Player();
			$account = new Account();
			$player->find($character);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('email_code') == $code)
				{
					if(check_password($newpassword))
					{
						$account->setPassword($newpassword);
						$account->set('email_code', '');
						$account->save();
						$main_content .= '
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Lost Account Interface</h3>
								</div>
								<div class="panel-body">
									<p>Here is your new account information.</p>
									<form class="form-horizontal" role="form" action="" method="post">
										<fieldset>
											<div class="form-group">
												<label for="name" class="col-lg-2 control-label">Account Name</label>
												<div class="col-lg-10">
											  		<p class="form-control-static">'.htmlspecialchars($account->getName()).'</p>
												</div>
											</div>

											<div class="form-group">
												<label for="sex" class="col-lg-2 control-label">Password</label>
												<div class="col-lg-10">
											  		<p class="form-control-static">'.htmlspecialchars($newpassword).'</p>
												</div>
											</div>

											<div class="text-center">
												<a class="btn btn-default" href="'.$config['site']['url'].'">Back</a>
											</div>

										</fieldset>
									</form>
								</div>
							</div>
						';
						/*$main_content .= 'New password to your account is below. Now you can login.<BR>
						<INPUT TYPE="hidden" NAME="character" VALUE="'.htmlspecialchars($character).'">
						<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
						<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Changed password</B></TD></TR>
						<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
						New password:&nbsp;<b>'.htmlspecialchars($newpassword).'</b><BR />
						Account name:&nbsp;&nbsp;&nbsp;<i>(Already on your e-mail)</i><BR />';*/
						$mailBody = '<html>
						<body>
						<h3>Your account name and password!</h3>
						<p>Changed password to your account in Lost Account Interface on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a></p>
						<p>Account name: <b>'.htmlspecialchars($account->getName()).'</b></p>
						<p>New password: <b>'.htmlspecialchars($newpassword).'</b></p>
						<br />
						<p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
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
						$mail->AddAddress($account->getCustomField('email'));
						$mail->Subject = $config['server']['serverName']." - New password to your account";
						$mail->Body = $mailBody;
						$mail->Send();
					}
					else
						$error= 'Wrong password format. Use only a-z, A-Z, 0-9.';
				}
				else
					$error= 'Wrong code to change password.';
			}
			else
				$error = 'Account of this character or this character doesn\'t exist.';
		}
		if(!empty($error))
				$main_content .= '<div class="alert alert-danger">'.$error.' <a class="alert-link" href="?view=lostaccount&action=checkcode">Click here</a> to go back.</div>';
	}
}
else
	$main_content .= '<b>Account maker is not configured to send e-mails, you can\'t use Lost Account Interface. Contact with admin to get help.</b>';
