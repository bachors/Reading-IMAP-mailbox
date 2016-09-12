<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>IMAP mailbox - iBacor</title>
		<!-- Custom style -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css">
		<style>
			pre {
				white-space: pre;
				white-space: pre-wrap;
				word-wrap: break-word;
			}
			.panel-heading .read {
				background: #fafafa;
				border-color: #f5f5f5
			}
			sup{
				background: #FF6C60;
				color: #fff;
				padding: 3px;
				border-radius: 50%
			}
		</style>
	</head>
	<body>
		<div class="alert alert-info alert-dismissable">Tutorial: <a style="color:#333;text-decoration:underline" href="http://ibacor.com/labs/reading-imap-mailbox" target="_BLANK">Reading emails from a IMAP mailbox - PHP</a></div>
		<?php
		// error_reporting(0);

		// Multiple email account
		$emails = array(
			array(
				'no'		=> '1',
				'label' 	=> 'Inbox Email 1',
				'host' 		=> '{mail.domain.com:143/notls}INBOX',
				'username' 	=> 'mail1@domain.com',
				'password' 	=> 'xxxxxxxxxx'
			),
			array(
				'no'		=> '2',
				'label' 	=> 'Inbox Email 2',
				'host' 		=> '{mail.domain.net:143/notls}INBOX',
				'username' 	=> 'mail2@domain.net',
				'password' 	=> 'xxxxxxxxxx'
			)
			// bla bla bla ...
		);
				
		foreach ($emails as $email) {

			$read = imap_open($email['host'],$email['username'],$email['password']) or die('<div class="alert alert-danger alert-dismissable">Cannot connect to yourdomain.com: ' . imap_last_error().'</div>');

			$result = imap_search($read,'ALL');

			$html = '';

			if($result) {

				rsort($result);
					
				$jumlah = count($result);

				$html.= '<div class="panel panel-default">
							<div class="panel-heading">
								<h3>'.$email['label'].' <sup>'.$jumlah.'</sup></h3>
							</div>
							<div class="panel-body">
								<div class="panel-group" id="accordion">';
				
				for ($i = 0; $i < $jumlah; $i++) {

					$overview = imap_fetch_overview($read,$result[$i],0);																		
					$message = imap_body($read,$result[$i],0);																		
					$header = imap_headerinfo($read,$result[$i],0);																		
					$reply = $header->from[0]->mailbox.'@'.$header->from[0]->host;
			
					if($overview[0]->seen){
						$seen = 'read';
					}else{
						$seen = 'unread';
					}
					
					$msg = '';
									
############################################### THIS CODE IS AWESOME :3 \^o^/ ####################################################
					if(preg_match('/Content-Type/', $message)){
						$message = strip_tags($message);
						$ctp = explode('Content-Type: ', $message);
						foreach ($ctp as $ct){
							if(preg_match('/base64/', $ct)){
								$bse = explode('base64', $ct);
								$bs = explode('=', $bse[1]);
								if(preg_match('/text/', $ct)){
									$msg = base64_decode($bs[0]);
								}else if(preg_match('/image/', $ct)){
									$nme = explode('name="', $ct);
									$nm = explode('"', $nme[1]);
									$img = explode('.', $nm[0]);
									$cc = str_replace($nm[0], "", $bs[1]);
									$msg .= '<img alt="image" src="data:image/'.$img[1].';base64,'.$cc.'" style="width:300px"/>';
								}
							}else{
								$msg .= $ct;
							}
						}
					}else{
						$msg = $message;
					}
												
					if(preg_match('/plain; charset=UTF-8/', $msg)){
						$pln = explode('plain; charset=UTF-8', $msg);
						$pl = explode('--', $pln[1]);
						$msg = $pl[0];
					}
												
					if(preg_match('/Content-Transfer-Encoding: 7bit/', $msg)){
						$bit = explode('Content-Transfer-Encoding: 7bit', $msg);
						$msg = $bit[1];
					}
#####################################################################################################################################
																		
					$html.= '	<div class="panel panel-default">
									<div class="panel-heading '.$seen.'">
										<h4 class="panel-title">
											<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#'.$email['no'].$result[$i].'">
												<span class="label label-info subject">'.substr(strip_tags($msg),10,60).'.. </span>
												<span class="label label-warning from">'.(preg_match("/=/", $overview[0]->from) ? $reply : $overview[0]->from).'</span>
												<span class="label label-success date">'.$overview[0]->date.'</span>
											</a>
										</h4>
									</div>
									<div id="'.$email['no'].$result[$i].'" class="panel-collapse collapse">
										<div class="panel-body">
											<pre>'.preg_replace('/--(.*)/i', '', $msg).'<hr>From: <a href="mailto:'.$reply.'">'.$reply.'</a></pre>
										</div>
									</div>
								</div>';												

				}
								
				$html.= '</div>
					</div>
				</div>';

				echo $html;

			}

			imap_close($read);
						
		}

		?>

		<!-- Javascript -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	</body>
</html>
