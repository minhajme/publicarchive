<?php
function PageMain() {
	global $TMPL, $LNG, $CONF, $db, $loggedIn, $settings;
	
	if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {	
		$verify = $loggedIn->verify();
		
		if(empty($verify['username'])) {
			// If fake cookies are set, or they are set wrong, delete everything and redirect to home-page
			$loggedIn->logOut();
			header("Location: ".$CONF['url']."/index.php?a=welcome");
		} else {
			// Start displaying the Feed
			
			$feed = new feed();
			$feed->db = $db;
			$feed->url = $CONF['url'];
			$feed->username = $verify['username'];
			$feed->id = $verify['idu'];
			$feed->per_page = $settings['perpage'];
			$feed->time = $settings['time'];
			$feed->censor = $settings['censor'];
			$feed->smiles = $settings['smiles'];
			$feed->c_per_page = $settings['cperpage'];
			$feed->c_start = 0;
			$feed->subscriptionsList = $feed->getSubs($verify['idu'], 0);
			$feed->subscribersList = $feed->getSubs($verify['idu'], 1);
			// $feed->image = $verify['image'];
			$TMPL['uid'] = $verify['idu'];
			
			$TMPL_old = $TMPL; $TMPL = array();
			$skin = new skin('shared/rows'); $rows = '';
			
			if(empty($_GET['filter'])) {
				$_GET['filter'] = '';
			}
			// Allowed types
			if($_GET['filter'] == 'likes') {
				$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, 1, null, null, null, null);
			} elseif($_GET['filter'] == 'comments') {
				$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, null, 1, null, null, null);
			} elseif($_GET['filter'] == 'shared') {
				$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, null, null, 1, null, null);
			} elseif($_GET['filter'] == 'friendships') {
				$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, null, null, null, 1, null);
			} elseif($_GET['filter'] == 'chats') {
				$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, null, null, null, null, 1);
			} else {
				$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, 1, 1, 1, 1, 1);
			}
			$TMPL['messages'] = '<div class="message-container"><div class="message-content" id="notifications-page"><div style="margin-top:-1px;">'.$x.'</div></div></div>';
			
			$rows = $skin->make();
			
			$skin = new skin('feed/sidebar'); $sidebar = '';
			
			$TMPL['editprofile'] = $feed->fetchProfileWidget($verify['username'], realName($verify['username'], $verify['first_name'], $verify['last_name']), $verify['image']);
			$TMPL['sidebar'] = $feed->sidebarNotifications($_GET['filter'], 'feed');
			$TMPL['subscriptions'] = $feed->sidebarSubs(0, 0);
			$TMPL['subscribers'] = $feed->sidebarSubs(1, 0);
			$TMPL['ad3'] = generateAd($settings['ad3']);
			
			$sidebar = $skin->make();
			
			$TMPL = $TMPL_old; unset($TMPL_old);
			$TMPL['rows'] = $rows;
			$TMPL['sidebar'] = $sidebar;
		}
	} else {
		// If the session or cookies are not set, redirect to home-page
		header("Location: ".$CONF['url']."/index.php?a=welcome");
	}
	
	if(isset($_GET['logout']) == 1) {
		$loggedIn->logOut();
		header("Location: ".$CONF['url']."/index.php?a=welcome");
	}

	$TMPL['url'] = $CONF['url'];
	$TMPL['title'] = $LNG['title_notifications'].' - '.$settings['title'];

	$skin = new skin('shared/timeline');
	return $skin->make();
}
?>