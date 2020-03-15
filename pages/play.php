<!-- PHP Handler -->
<?php
	if(!defined('INITIALIZED')) {
		exit;
	}
	
	$player = new Player();
	$player->loadByName(htmlspecialchars($_REQUEST['name']));
	if($player->isLoaded()) {
		$account = array(
			'sessionKey' => Website::generateSessionKey()
		);
		
		$server = array(
			'WorldID' => 1, // Keep since there are plans to add multi-worlds to TFS 1.x
			'IP' => Website::getServerConfig()->getValue('ip'),
			'port' => 7172
		);
		
		$accountCharacters = "";
		$selected_character = "";
		foreach($account_logged->getPlayersList() as $character) {
			if(strtolower($player->getName()) == strtolower($character->getName())) {
				$selected_character = "<character name='" . htmlspecialchars($character->getName()) . "' worldid='" . $server['WorldID'] . "' selected='true' />";
			} else {
				$accountCharacters .= "<character name='" . htmlspecialchars($character->getName()) . "' worldid='" . $server['WorldID'] . "' selected='false' />";
			}
		}
		
		$accountCharacters .= $selected_character;
		$SQL->query("UPDATE `accounts` SET `flash_token` = '" . $account['sessionKey'] . "' WHERE `name` = '" . $account_logged->getName() . "'");
	}
?>

<html>
	<head>
		<title><?php echo htmlspecialchars($config['server']['serverName']); ?> Flash Client</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<meta http-equiv="content-language" content="en" />
		<link rel="shortcut icon" href="flash-regular-bin/images/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="flash-regular-bin/images/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="flash-regular-bin/common/style.css" />
		
		<script type="text/javascript" src="flash-regular-bin/js/jquery.js"></script>
		<script type="text/javascript" src="flash-regular-bin/js/flashclienthelper.js"></script>
		<script type="text/javascript" src="flash-regular-bin/js/swfobject.js"></script>
		<script type="text/javascript">
			window.onresize = function(e) {
				var w = e.currentTarget.innerWidth, h = e.currentTarget.innerHeight;
				if (w > 1.25 * h) {
					$('#BackgroundContainer').css('background-size', 'auto 100%');
				} else {
					$('#BackgroundContainer').css('background-size', '100% auto');
				}
			}
			
			function ShowSWFLoadingError() {
				document.getElementById("placeholder2").style.display = "block";
				document.getElementById("placeholder2").style.visibility = "visible";
				if (document.getElementById('placeholder1')) {
					document.getElementById("placeholder1").style.display = "none";
					document.getElementById("placeholder1").style.visibility = "hidden";
				}
			}
			
			function SWFStatusAction(e) {
				if (e.success == false) {
					ShowSWFLoadingError();
				}
			}
		</script>
		<style type="text/css" media="screen">#placeholder1 {visibility:hidden}</style>
	</head>
	
	<body>
	<script>
	function requestFlashPermission() {
		var iframe = document.createElement('iframe');
		iframe.src = 'https://get.adobe.com/flashplayer';
		iframe.sandbox = '';
		document.body.appendChild(iframe);
		document.body.removeChild(iframe);
	}


	var isNewEdge = (navigator.userAgent.match(/Edge\/(\d+)/) || [])[1] > 14;
	var isNewSafari = (navigator.userAgent.match(/OS X (\d+)/) || [])[1] > 9;
	var isNewChrome = (navigator.userAgent.match(/Chrom(e|ium)\/(\d+)/) || [])[2] > 56	&& !/Mobile/i.test(navigator.userAgent);
	var canRequestPermission = isNewEdge || isNewSafari || isNewChrome;

	if (!swfobject.hasFlashPlayerVersion('10') && canRequestPermission) {
		// in case, when flash is not available, try to prompt user to enable it
		requestFlashPermission();
		// Chrome requires user's click in order to allow iframe embeding
		$(window).one('click', requestFlashPermission);
	}
	</script>
		<?php
			if(!$logged) {
				echo '
					<div id="BackgroundContainer" style="background-image: url(flash-regular-bin/images/play-background_artwork.png);">
						<div id="FlashClientBackgroundOverlay"></div>
						<div id="FlashClientErrorBox" class="FlashClientBox" style="background-image: url(flash-regular-bin/images/flashclient_error_box.png);">
							<div class="FlashClientBoxHeadline">Error</div>
							<div id="FlashClientErrorBoxText">You are not logged into your account.<br/>Please log in at the website first.<br/><br/>(The authenticator login was not successful!)</div>
							<a href="index.php?view=account">
								<div id="FlashClientErrorBoxButton">Close</div>
							</a>
						</div>
					</div>
				';
				return;
			}
		?>
		
		<div id="BackgroundContainer" style="background-image: url(flash-regular-bin/images/play-background_artwork.png);">
			<div id="placeholder1">
				<div id="FlashClientBackgroundOverlay"></div>
				<div id="FlashClientErrorBox" class="FlashClientBox" style="background-image: url(flash-regular-bin/images/flashclient_error_box.png);">
					<div class="FlashClientBoxHeadline">Error</div>
					<div id="FlashClientErrorBoxText">Adobe Flash Player is missing.<br/><br/>You need the Adobe Flash Player (version 11.2.0 or higher) installed to use the Flash client.</div>
					<a href="?view=account">
						<div id="FlashClientErrorBoxButton">Close</div>
					</a>
				</div>
			</div>
			
			<div id="placeholder2">
				<div id="FlashClientBackgroundOverlay"></div>
				<div id="FlashClientErrorBox" class="FlashClientBox" style="background-image: url(flash-regular-bin/images/flashclient_error_box.png);">
					<div class="FlashClientBoxHeadline">Error</div>
					<div id="FlashClientErrorBoxText" style="text-align: center"><img src="img/flashplayer.png" alt="" width="60" height="60"><br>Flash has not been enabled for this site!<br>
						Click on the screen and you should be prompted to allow flash.
					</div>
					<a href="?view=account">
						<div id="FlashClientErrorBoxButton">Cancel</div>
					</a>
				</div>
			</div>
		</div>
		
		<script>
			var l_Request = false;
			if (window.XMLHttpRequest) {
				l_Request = new XMLHttpRequest;
			} else if (window.ActiveXObject) {
				l_Request = new ActiveXObject("Microsoft.XMLHttp");
			}
			
			try {
				l_Request.open("HEAD", "flash-regular-bin/preloader.swf", true);
				l_Request.onreadystatechange=function() {
					if (l_Request.readyState == 4) {
						if (l_Request.status == 200 || l_Request.status == 304) {
							swfobject.embedSWF(
								"flash-regular-bin/preloader.swf",
								"placeholder1",
								"100%",
								"100%",
								"11.3.0",
								null,
								{
									tutorial: false,
									sessionKey: "<?php echo $account['sessionKey']; ?>",
									sessionRefreshURL: "?view=refresh",
									accountData: "<accountData><world id='<?php echo $server['WorldID']; ?>' name='<?php echo htmlspecialchars($config['server']['serverName']); ?>' address='<?php echo $server['IP']; ?>' port='<?php echo $server['port']; ?>' previewstate='0' /><?php echo $accountCharacters; ?></accountData>",
									backgroundColor: 0x051122,
									backgroundImage: "flash-regular-bin/images/play-background_artwork.png",
									closeClientURL: "?view=account"
								},
								{
									allowscriptaccess: "always",
									wmode: "direct"
								},
								{
									id: "FlashClient",
									name: "FlashClient"
								},
								SWFStatusAction);
						} else {
							ShowSWFLoadingError();
						}
					}
				}
				l_Request.send(null);
			} catch (er) {
				ShowSWFLoadingError();
			}
		</script>
	</body>
</html>
