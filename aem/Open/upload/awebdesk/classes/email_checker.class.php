<?php
if(basename($_SERVER['REQUEST_URI']) == basename(__FILE__)) die('hacking attempt.');   
  
class emailChecker{

	/**
	* @author jspicher
	* @copyright 2013
	* @url http://jspicher.com/
	* @version 1.0.2
	*/
	public $filter_domains = true; //reject domains in $blacklist_domains array
	public $filter_dea = true; //reject known DEA (disposible email address) providers/domains
	public $filter_unsubscribers = true; //reject efilter_google_deaxact match email addresses of your unsubscribers
	public $filter_google_dea = true; //remove . and +'s from google and gmail addresses (simple dea's)
	public $fix_typos = true; //correct commond email TLD typos using algorythm
	public $fix_levenshtein_typos = true; //use Levenshtein algorithm to correct typos
	public $auto_correct_typos = true; //only show typo correction suggestions (instead of actually fixing them)
	public $check_mx = true; //check the mx server of the email TLD. requires checkdnsrr() function.
	public $smtp_test = true; //Connect to MX server and simulate mail send with SMTP
	public $smtp_port = 25; //If usering smtp_test, this is the SMTP port your server uses
	public $supress_output = false; //supress output, and just return true/false
	public $email = null;

	//array of blacklisted domains (known spammers)
	public $blacklist_domains = array(
									'spammerdomain.com'
									);
	
	//black list of DEA (disposiable email address) domains.
	//these are all on the same link because the list is so long
	public $blacklist_dea = array('0-mail.com', '0sg.net', '0wnd.net', '0wnd.org', '1chuan.com', '1zhuan.com', '2prong.com', '3d-painting.com', '3g.ua', '4warding.com', '4warding.net', '4warding.org', '6url.com', '9ox.net', '10minutemail', '10minutemail.com', '12hourmail.com', '20minutemail.com', '21cn.com', '24hourmail.com', '50e.info', '75hosting.com', '75hosting.net', '75hosting.org', '675hosting.com', '675hosting.net', '675hosting.org', '0815.ru', '0815.ru0clickemail.com', '3126.com', 'dacoolest.com', 'maileater.com', 'tempemail.net', 'a-bc.net', 'abwesend.de', 'addcom.de', 'afrobacon.com', 'ag.us.to', 'agnitumhost.net', 'ajaxapp.net', 'akapost.com', 'alpenjodel.de', 'alphafrau.de', 'amilegit.com', 'amiri.net', 'amiriindustries.com', 'amorki.pl', 'anonbox.net', 'anonymail.dk', 'anonymbox', 'anonymbox.com', 'anonymousmail.org', 'anonymousspeech.com', 'antichef.com', 'antichef.net', 'antispam.de', 'antispam24.de', 'autosfromus.com', 'baldmama.de', 'baldpapa.de', 'ballyfinance.com', 'baxomale.ht.cx', 'beefmilk.com', 'beep.com', 'beerolympics', 'betriebsdirektor.de', 'bigmir.net', 'bigstring.com', 'bin-wieder-da.de', 'binkmail.com', 'bio-muesli.info', 'bio-muesli.net', 'bleib-bei-mir.de', 'blockfilter.com', 'blogmyway.org', 'bluebottle.com', 'bobmail.info', 'bodhi.lawlita.com', 'bofthew.com', 'bonbon.net', 'brefmail.com', 'briefemail.com', 'brokenvalve.com', 'brokenvalve.org', 'bsnow.net', 'bspamfree.org', 'buerotiger.de', 'bugmenot.com', 'bumpymail.com', 'buy-24h.net.ru', 'buyusedlibrarybooks.org', 'cashette.com', 'casualdx.com', 'center-mail.de', 'centermail.at', 'centermail.ch', 'centermail.com', 'centermail.de', 'centermail.info', 'centermail.net', 'cghost.s-a-d.de', 'chammy.info', 'chogmail.com', 'choicemail1.com', 'chongsoft.org', 'cool.fr.nf', 'coole-files.de', 'correo.blogos.net', 'cosmorph.com', 'courriel.fr.nf', 'courrieltemporaire', 'courrieltemporaire.com', 'curryworld.de', 'cust.in', 'cyber-matrix.com', 'dacoolest.com', 'dandikmail.com', 'dating4best.net', 'deadaddress', 'deadaddress.com', 'deadspam.com', 'despam.it', 'despammed.com', 'devnullmail.com', 'dfgh.net', 'die-besten-bilder.de', 'die-genossen.de', 'die-optimisten.de', 'die-optimisten.net', 'diemailbox.de', 'digital-filestore.de', 'digitalsanctuary.com', 'directbox.com', 'discardmail.com', 'discardmail.de', 'discartmail.com', 'disposableaddress.com', 'disposeamail.com', 'disposemail.com', 'dispostable', 'dispostable.com', 'dm.w3internet.co.uk', 'dm.w3internet.co.uk example.com', 'docmail.cz', 'dodgeit.com', 'dodgit.com', 'dodgit.org', 'dogit.com', 'dontreg.com', 'dontsendmespam.de', 'dontsentmespam.de', 'dotmsg.com', 'download-privat.de', 'droplister.com', 'dudmail.com', 'dump-email.info', 'dumpandjunk.com', 'dumpmail.com', 'dumpmail.de', 'dumpyemail.com', 'dyndns.org', 'e-mail.com', 'e-mail.org', 'e4ward', 'e4ward.com', 'eintagsmail.de', 'email.org', 'email4u.info', 'email60.com', 'emaildienst.de', 'emailias.com', 'emailinfive.com', 'emaillime.com', 'emailmiser.com', 'emailtaxi.de', 'emailtemporario.com.br', 'emailto.de', 'emailwarden.com', 'emailxfer.com', 'empinbox.com', 'emz.net', 'enterto.com', 'ephemail.net', 'etranquil.com', 'etranquil.net', 'etranquil.org', 'example.com', 'explodemail.com', 'fahr-zur-hoelle.org', 'fakeinbox.com', 'fakeinformation.com', 'fakemailgenerator.com', 'fakemailz.com', 'falseaddress.com', 'fansworldwide.de', 'fantasymail.de', 'farifluset.mailexpire.com', 'fastacura.com', 'fastchevy.com', 'fastchrysler.com', 'fastermail.com', 'fastkawasaki.com', 'fastmazda.com', 'fastmitsubishi.com', 'fastnissan.com', 'fastsubaru.com', 'fastsuzuki.com', 'fasttoyota.com', 'fastyamaha.com', 'feinripptraeger.de', 'fettabernett.de', 'filzmail', 'filzmail.com', 'fishfuse.com', 'fixmail.tk', 'fizmail.com', 'footard.com', 'forgetmail.com', 'frapmail.com', 'freemeilaadressforall.net', 'freudenkinder.de', 'fromru.com', 'front14.org', 'fux0ringduh.com', 'garliclife.com', 'gelitik.in', 'gentlemansclub.de', 'get1mail.com', 'get2mail', 'getairmail.com', 'getmails.eu', 'getonemail.com', 'getonemail.net', 'ghosttexter.de', 'girlsundertheinfluence.com', 'gishpuppy', 'gishpuppy.com', 'goemailgo.com', 'gold-profits.info', 'goldtoolbox.com', 'golfilla.info', 'gorillaswithdirtyarmpits.com', 'gowikibooks.com', 'gowikicampus.com', 'gowikicars.com', 'gowikifilms.com', 'gowikigames.com', 'gowikimusic.com', 'gowikinetwork.com', 'gowikitravel.com', 'gowikitv.com', 'great-host.in', 'greensloth.com', 'gsrv.co.uk', 'guerillamail.biz', 'guerillamail.com', 'guerillamail.net', 'guerillamail.org', 'guerrillamail.biz', 'guerrillamail.com', 'guerrillamail.de', 'guerrillamail.info', 'guerrillamail.net', 'guerrillamail.org', 'guerrillamailblock.com', 'h8s.org', 'hab-verschlafen.de', 'habmalnefrage.de', 'haltospam.com', 'hatespam.org', 'herr-der-mails.de', 'hidemail.de', 'hidzz.com', 'hmamail.com', 'home.de', 'hooohush.ai', 'hotpop.com', 'huajiachem.cn', 'hulapla.de', 'hush.com', 'hushmail.com', 'i.ua', 'i2pmail.org', 'ich-bin-verrueckt-nach-dir.de', 'ich-will-net.de', 'ieatspam.eu', 'ieatspam.info', 'ihateyoualot.info', 'iheartspam.org', 'imails.info', 'imstations.com', 'inbox.ru', 'inbox2.info', 'inboxclean.com', 'inboxclean.org', 'incognitomail', 'incognitomail.com', 'incognitomail.net', 'inerted.com', 'inet.ua', 'inmail24.com', 'instant-mail.de', 'ipoo.org', 'irish2me.com', 'ist-allein.info', 'ist-einmalig.de', 'ist-ganz-allein.de', 'ist-willig.de', 'iwi.net', 'izmail.net', 'jetable', 'jetable.com', 'jetable.de', 'jetable.fr.nf', 'jetable.net', 'jetable.org', 'jetfix.ee', 'jetzt-bin-ich-dran.com', 'jn-club.de', 'jnxjn.com', 'joliekemulder', 'junk.', 'junk1e.com', 'junkmail.com', 'k2-herbal-incenses.com', 'kaffeeschluerfer.com', 'kaffeeschluerfer.de', 'kasmail.com', 'kaspop.com', 'killmail.com', 'killmail.net', 'kinglibrary.net', 'klassmaster.com', 'klassmaster.net', 'klzlk.com', 'kommespaeter.de', 'krim.ws', 'kuh.mu', 'kulturbetrieb.info', 'kurzepost.de', 'lass-es-geschehen.de', 'liebt-dich.info', 'lifebeginsatconception', 'lifebyfood.com', 'link2mail', 'link2mail.net', 'listomail.com', 'litedrop.com', 'lookugly.com', 'lopl.co.cc', 'lortemail.dk', 'lovemeleaveme.com', 'loveyouforever.de', 'lr78.com', 'lroid.com', 'maboard.com', 'maennerversteherin.com', 'maennerversteherin.de', 'mail-temporaire', 'mail.by', 'mail.com', 'mail.htl22.at', 'mail.mezimages.net', 'mail.misterpinball.de', 'mail.ru', 'mail.svenz.eu', 'mail2rss.org', 'mail4days.com', 'mail4trash.com', 'mail4u.info', 'mail15.com', 'mail333.com', 'mailbidon.com', 'mailblocks.com', 'mailbucket.org', 'mailcatch.com', 'maileater.com', 'mailexpire.com', 'mailfreeonline.com', 'mailin8r.com', 'mailinater.com', 'mailinator', 'mailinator.com', 'mailinator.net', 'mailinator2.com', 'mailinblack.com', 'mailincubator.com', 'mailme.lv', 'mailmetrash.com', 'mailmoat.com', 'mailnator.com', 'mailnesia.com', 'mailnull', 'mailnull.com', 'mailquack.com', 'mailshell.com', 'mailsiphon.com', 'mailslapping.com', 'mailtrash.net', 'mailueberfall.de', 'mailzilla.com', 'mailzilla.org', 'makemetheking.com', 'mamber.net', 'mbx.cc', 'mega.zik.dj', 'meine-dateien.info', 'meine-diashow.de', 'meine-fotos.info', 'meine-urlaubsfotos.de', 'meinspamschutz.de', 'meltmail', 'meltmail.com', 'messagebeamer.de', 'metaping.com', 'mierdamail.com', 'mintemail', 'mintemail.com', 'mjukglass.nu', 'mmmmail.com', 'mns.ru', 'mobi.web.id', 'moburl.com', 'moncourrier.fr.nf', 'monemail.fr.nf', 'monmail.fr.nf', 'ms9.mailslite.com', 'msg.mailslite.com', 'mt2009.com', 'mufmail.com', 'muskelshirt.de', 'mx0.wwwnew.eu', 'my-mail.ch', 'myadult.info', 'mycleaninbox.net', 'mymail-in.net', 'mypacks.net', 'myspaceinc.com', 'myspaceinc.net', 'myspaceinc.org', 'myspacepimpedup.com', 'myspamless.com', 'mytempemail', 'mytempemail.com', 'mythrashmail.net', 'mytop-in.net', 'mytrashmail.com', 'mytrashmail.compookmail.com', 'neomailbox.com', 'nepwk.com', 'nervmich.net', 'nervtmich.net', 'netmails.com', 'netmails.net', 'netterchef.de', 'netzidiot.de', 'neue-dateien.de', 'neverbox.com', 'nm.ru', 'no-spam.ws', 'nobulk.com', 'noclickemail.com', 'nogmailspam.info', 'nomail.xl.cx', 'nomail2me.com', 'nospam.ze.tc', 'nospam4.us', 'nospamfor.us', 'nospamforus', 'nospammail.net', 'notsharingmy.info', 'nowmymail.com', 'nullbox.info', 'nur-fuer-spam.de', 'nurfuerspam.de', 'nwldx.com', 'nybella.com', 'objectmail.com', 'obobbo.com', 'odaymail.com', 'office-dateien.de', 'oikrach.com', 'oneoffemail.com', 'oneoffmail.com', 'onewaymail.com', 'oopi.org', 'opayq.com', 'open.by', 'orangatango.com', 'ordinaryamerican.net', 'ourklips.com', 'outlawspam.com', 'owlpic.com', 'pancakemail.com', 'partybombe.de', 'partyheld.de', 'phreaker.net', 'pimpedupmyspace.com', 'pisem.net', 'pleasedontsendmespam.de', 'polizisten-duzer.de', 'poofy.org', 'pookmail.com', 'pornobilder-mal-gratis.com', 'portsaid.cc', 'postfach.cc', 'privacy.net', 'privymail.de', 'proxymail.eu', 'prydirect.info', 'pryworld.info', 'public-files.de', 'punkass.com', 'put2.net', 'putthisinyourspamdatabase.com', 'q00.', 'quantentunnel.de', 'quickinbox.com', 'qv7.info', 'ralib.com', 'raubtierbaendiger.de', 'rcpt.at', 'receiveee.com', 'recode.me', 'record.me', 'recursor.net', 'recyclemail.dk', 'regbypass.comsafe-mail.net', 'rejectmail.com', 'rklips.com', 'rmqkr.net', 'rootprompt.org', 'saeuferleber.de', 'safe-mail.net', 'safersignup.de', 'safetymail.info', 'sags-per-mail.de', 'sandelf.de', 'satka.net', 'saynotospams.com', 'schmusemail.de', 'schreib-doch-mal-wieder.de', 'secure-mail.biz', 'selfdestructingmail.com', 'sendspamhere.com', 'senseless-entertainment.com', 'shared-files.de', 'sharklasers.com', 'shieldedmail.com', 'shiftmail.com', 'shinedyoureyes.com', 'shortmail.net', 'sibmail.com', 'siria.cc', 'skeefmail.com', 'skeefmail.net', 'slaskpost.se', 'slopsbox.com', 'slushmail.com', 'smaakt.naar.gravel', 'smellfear.com', 'sms.at', 'snakemail.com', 'sneakemail.com', 'sofimail', 'sofort-mail.de', 'sofortmail.de', 'sogetthis.com', 'sonnenkinder.org', 'soodo', 'soodonims.com', 'spam', 'spam.la', 'spamavert', 'spamavert.com', 'spambob.com', 'spambob.net', 'spambob.org', 'spambog', 'spambog.com', 'spambog.de', 'spambog.ru', 'spambox.info', 'spambox.us', 'spamcannon.com', 'spamcannon.net', 'spamcero.com', 'spamcon.org', 'spamcorptastic.com', 'spamcowboy.com', 'spamcowboy.net', 'spamcowboy.org', 'spamday.com', 'spameater.com', 'spameater.org', 'spamevader', 'spamex.com', 'spamfree.eu', 'spamfree24', 'spamfree24.com', 'spamfree24.de', 'spamfree24.eu', 'spamfree24.info', 'spamfree24.net', 'spamfree24.org', 'spamgourmet', 'spamgourmet.com', 'spamgourmet.net', 'spamgourmet.org', 'spamgrube.net', 'spamherelots.com', 'spamhereplease.com', 'spamhole.com', 'spamify.com', 'spaminator.de', 'spamkill.info', 'spaml.com', 'spaml.de', 'spammote.com', 'spammotel', 'spammotel.com', 'spammuffel.de', 'spamobox', 'spamobox.com', 'spamoff.de', 'spamreturn.com', 'spamslicer.com', 'spamspot.com', 'spamthis.co.uk', 'spamthisplease.com', 'spamtrail.com', 'speed.1s.fr', 'sperke.net', 'sriaus.com', 'streber24.de', 'super-auswahl.de', 'supergreatmail.com', 'suremail.info', 'sweetville.net', 'tagesmail.eu', 'teewars.org', 'teleworm.us', 'temp-mail.com', 'temp-mail.org', 'tempalias.com', 'tempe-mail.com', 'tempemail', 'tempemail.biz', 'tempemail.com', 'tempemail.net', 'tempinbox.co.uk', 'tempinbox.com', 'tempmail', 'tempomail.fr', 'temporarily.de', 'temporaryemail.net', 'temporaryforwarding.com', 'temporaryinbox.com', 'tempymail.com', 'terminverpennt.de', 'test.com', 'test.de', 'thankyou2010.com', 'thepryam.info', 'thisisnotmyrealemail.com', 'throwawayemailaddress.com', 'tilien.com', 'tmailinator.com', 'topmail-files.de', 'tormail.net', 'tormail.org', 'tortenboxer.de', 'totalmail.de', 'tradermail.info', 'trash-amil.com', 'trash-mail.at', 'trash-mail.com', 'trash-mail.de', 'trash2009.com', 'trashbox.eu', 'trashdevil.com', 'trashdevil.de', 'trashmail.at', 'trashmail.com', 'trashmail.de', 'trashmail.me', 'trashmail.net', 'trashmail.org', 'trashmailer.com', 'trashymail.com', 'trashymail.net', 'trillianpro.com', 'trimix.cn', 'turboprinz.de', 'turboprinzessin.de', 'turual.com', 'tut.by', 'twinmail.de', 'tyldd.com', 'ua.fm', 'uggsrock.com', 'uk2.net', 'ukr.net', 'unterderbruecke.de', 'upliftnow.com', 'uplipht.com', 'uroid', 'venompen.com', 'verlass-mich-nicht.de', 'veryrealemail.com', 'viditag.com', 'viewcastmedia.com', 'viewcastmedia.net', 'viewcastmedia.org', 'vinbazar.com', 'vistomail.com', 'vollbio.de', 'volloeko.de', 'vorsicht-bissig.de', 'vorsicht-scharf.de', 'walala.org', 'war-im-urlaub.de', 'wbb3.de', 'webmail4u.eu', 'wegwerfadresse.de', 'wegwerfemail.com', 'wegwerfemail.de', 'wegwerfmail.de', 'wegwerfmail.net', 'wegwerfmail.org', 'weibsvolk.de', 'weibsvolk.org', 'weinenvorglueck.de', 'wetrainbayarea.com', 'wetrainbayarea.org', 'wh4f.org', 'whopy.com', 'whyspam.me', 'wilemail.com', 'will-hier-weg.de', 'willhackforfood.biz', 'willselfdestruct.com', 'winemaven.info', 'wir-haben-nachwuchs.de', 'wir-sind-cool.org', 'wirsindcool.de', 'wolke7.net', 'women-at-work.org', 'wormseo.cn', 'wp.pl', 'wronghead.com', 'wuzup.net', 'wuzupmail.net', 'wwwnew.eu', 'xagloo.com', 'xemaps.com', 'xents.com', 'xmail.com', 'xmaily.com', 'xoxy.net', 'xsecurity.org', 'xxtreamcam.com', 'yep.it', 'yesey.net', 'yogamaven.com', 'yopmail', 'yopmail.com', 'yopmail.fr', 'yopmail.net', 'yopweb.com', 'youmailr.com', 'ystea.org', 'yuurok.com', 'yzbid.com', 'z1p.biz', 'zehnminutenmail.de', 'zippymail.info', 'zoemail.com', 'zoemail.net', 'zoemail.org', 'zweb.in');
	
	//list of unsubscribed users -- prevent additioanl signup(s)
	public $blacklist_unsubscribers = array(
											'user@mail.com', 
											);
											
	//array of common .com typos made by users						
	public $common_com_typos = array(
									'.ocm',
									'.con',
									'.cmo',
									'.copm',
									'.xom',
									'.vom',
									'.comn',
									'.co',
									'.comj',
									'.coim',
									'.cpm',
									'.colm',
									'.conm',
									'.coom'
									);
	
	public $common_esp = array( //weighted common esp's (don't change the order unless you know what you're doing!)
								"yahoo.com", 
								"google.com", 
								"hotmail.com", 
								"gmail.com", 
								"me.com", 
								"aol.com", 
								"mac.com", 
								"live.com", 
								"comcast.net", 
								"googlemail.com", 
								"msn.com", 
								"hotmail.co.uk", 
								"yahoo.co.uk",  
								"facebook.com",
								"ymail.co.uk", 
								"ymail.com", 
								"verizon.net", 
								"netzero.com", 
								"sbcglobal.net", 
								"att.net",
								"mail.com",	
								"gmx.com"
								);
	
	//core check email functionality
	public function check($email){
	
		$email = trim(strtolower($email));
		list($user, $domain) = explode('@', $email);

		$output['result'] = array();
		$output['result']['query'] = $email;
		$output['result']['success'] = 1;
		
		//before validating email, fix common typos
		if($this->fix_typos){
			$filter = $this->fix_typos_function($email);
			if(trim($email) != trim($filter)){ 
				if(!$this->auto_correct_typos){
					$suggestion['result']['suggestion'] = $filter;
					$output = array_merge_recursive((array)$output, (array)$suggestion);
				}else{
					$email = $filter;
				}
				
			}
			
			//levenshtein advanced typo correction
			if(!in_array($domain, $this->common_esp) && $this->fix_levenshtein_typos){
				$levenshtein_email = $this->fix_levenshtein_typo_function($domain, $this->common_esp);
				if($levenshtein_email && $levenshtein_email != $domain){
					if(!$this->auto_correct_typos){
						$output['result']['suggestion'] = $user . '@' . $levenshtein_email;
					}else{
						$email = $user . '@' . $levenshtein_email;
					}
				}
			}
			
		}

		//validate the email address
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		
			if(!$this->supress_output){
				$output['result']['errors']['validate_email'] = 'Email addess is not valid';
			}
			
			$output['result']['success'] = 0;
			
			//no sense in going forward, just return output now.
			return $output;
		}else{
			$output['result']['report']['validate_email'] = 'Email address is valid.';
		}
		
		//break up email address parts
		list($user, $domain) = explode('@', $email);


		if($this->filter_google_dea){
			//strip out +'s from email name
			//strip out .'s from gmail.com and googlemail.com and google.com
			$filter = $this->check_google_dea($user, $domain);
			$email = $filter['result']['query'];
			$output = array_merge_recursive((array)$output, (array)$filter);
		}
		
		//filter DEA address
		if($this->filter_dea){
			$filter = $this->check_dea($domain);
			$output = array_merge_recursive((array)$output, (array)$filter);
			$output['result']['success'] = $filter['result']['success'];
		}
		
		//filter blacklist address
		if($this->filter_domains){
			$filter = $this->check_blacklist_domains($domain);
			$output = array_merge_recursive($output, $filter);
			$output['result']['success'] = $filter['result']['success'];
		}

		//filter unsubscribers
		if($this->filter_unsubscribers){
			$filter = $this->check_unsubscribers($email);
			$output = array_merge_recursive($output, $filter);
			$output['result']['success'] = $filter['result']['success'];
		}

		//check mx record for domain
		if($this->check_mx){
			$check_mx = $this->check_mx($domain);
			if(!$check_mx){
				$output['result']['errors']['check_mx'] = 'Domain failed MX check.';
				$output['result']['success'] = 0;
			}else{
				$output['result']['report']['check_mx'] = 'Domain passed MX check.';
			}
		}
		
		//simulate message via SMTP
		if($this->smtp_test){
			$smtp_validator = new SMTP_Validate_Email($email, 'send' . rand(11, 99) . '@' . $_SERVER['HTTP_HOST']);
			$smtp_validator->connect_port = $this->smtp_port;
			$smtp_results = $smtp_validator->validate();
			
			//recast array
			$smtp_results = array_values($smtp_results);
			if($smtp_results[0]){
				$output['result']['report']['smtp_test'] = 'Email passed SMTP simulated send.';
			}else{
				$output['result']['errors']['smtp_test'] = 'Email failed SMTP simulated send.';
				$output['result']['success'] = 0;
			}
			
		}
		
		//output result
		if($this->supress_output){
			$spressed_output['result']['query'] = $email;
			$spressed_output['result']['success'] = $output['result']['success'];
			
			if($output['result']['suggestion']){ 
				$spressed_output['result']['suggestion'] = $output['result']['suggestion'];
			}
			
			return $spressed_output;
		}else{
			$output['result']['query'] = $email;
			return $output;
		}
		
	}//end function
	
	
	//check against dea list
	private function check_dea($domain){
		if(in_array(trim(strtolower($domain)), $this->blacklist_dea)){
			$output['result']['errors']['filter_dea'] = 'Email address is a DEA (disposable email address).';
			$output['result']['success'] = 0;
		}else{
			$output['result']['report']['filter_dea'] = 'Email address is not a DEA (disposable email address).';
			$output['result']['success'] = 1;
		}
		return $output;
	}

	//check google DEAs (simple filtering)
	private function check_google_dea($user, $domain){
		$output = array();
		//strip out +'s from email name
		if(strstr($user, '+')){
			$user = preg_replace('/\+.*$/', '', $user);
			$output['result']['report']['filter_google_dea'] = 'Gmail DEA "+" filtering completed. ';
		}else{
			$output['result']['report']['filter_google_dea'] = 'Complete. ';
		}
		
		//strip out .'s from gmail.com and googlemail.com and google.com
		if(strstr($user, '.') && ($domain == 'googlemail.com' || $domain == 'gmail.com')){
			$user = str_replace('.', '', $user);
			$output['result']['report']['filter_google_dea'] .= 'Gmail DEA "." filtering completed.';
		}
		
		//filering comlete, rebuild email var
		$output['result']['query'] = $user . '@' . $domain;
		return $output;
	}
	
	
	//check against blacklist domains
	private function check_blacklist_domains($domain){
		if(in_array(trim(strtolower($domain)), $this->blacklist_domains)){
			$output['result']['errors']['filter_domains'] = 'Email domain is blacklisted.';
			$output['result']['success'] = 0;
		}else{
			$output['result']['report']['filter_domains'] = 'Email domain is not blacklisted.';
			$output['result']['success'] = 1;

		}
		return $output;
	}

	//check against blacklist emails/unsubs
	private function check_unsubscribers($email){
		if(in_array($email, $this->blacklist_unsubscribers)){
			$output['result']['errors']['filter_unsubscribers'] = 'Email address found in unsubscriber list.';
			$output['result']['success'] = 0;
		}else{
			$output['result']['report']['filter_unsubscribers'] = 'Email address not found in unsubscriber list.';
			$output['result']['success'] = 1;
		}
		return $output;
	}	
	
	//check mx record of email TLD
	private function check_mx($domain){
		
		//check if domain is in list of known common ESPs
		if(in_array($domain, $this->common_esp)){
			return true;
		}
	
		//make sure checkdnsrr() is a valid function on this server
		if(function_exists('checkdnsrr')){
			return checkdnsrr($domain, 'MX');
		}else{
			//checkdnsrr() not available on this server... fatal error
			die('<b>Fatal Error:</b> function <a href="http://php.net/manual/en/function.checkdnsrr.php">checkdnsrr()</a> does not exist! Please upgrade PHP or disable $check_mx.');
		}

	}
	
	//basic typo correction function
	private function fix_typos_function($email){
		list($user, $domain) = explode('@', $email);
		
		$f = array(',', '/', "'");
		$email = str_replace($f, '.', $email);
		list($user, $domain) = explode('@', $email);
		$tld = strrchr($domain, "." );
		$tld = substr($tld, 1 );

		//loop through common TLD typos
		foreach((array)$this->common_esp as $esp){
			
			$typo = str_replace('.', '', $esp);
			$typo_len = strlen($typo);
			
			if(substr($email, -$typo_len) == trim($typo)){
				$email = str_replace($typo, $esp, $email);
				break;
			}
			
			//try missing tld's
			$typo_tld = strrchr($esp, "." );
			$typo_tld = substr($typo_tld, 1 );
			$typo_tld_len = strlen($typo_tld);

			$typo_sld = substr($esp, 0, ($typo_len - $typo_tld_len));
			if(trim($domain) == trim($typo_sld)){
				$email .= '.' . $typo_tld;
				break;
			}
		}
	
		//fix common .com typos
		foreach((array)$this->common_com_typos as $typo){
			$length = strlen($typo);
			$end = substr($email, -$length);
			$email_length = strlen($email);
			if($end == $typo){
				//typo found, correct it!
				$email = substr($email, 0, ($email_length - $length)) . '.com';
				break;
			}
		}
	
		//try to correct common @ symbol typos
		 $hash_count = substr_count($email , '#');
		 $point_count = substr_count($email , '!');
		 $at_count = substr_count($email , '@');
		 if(!$at_count && $hash_count == 1){
			$email = str_replace('#', '@', $email);
		 }else if(!$at_count && $point_count == 1){
			$email = str_replace('!', '@', $email);
		 }
		
		return $email;
	}
	
private function fix_levenshtein_typo_function($email_domain, $common_esp) {
		$threshold = 3; // minimum distance a typo can be from our default list
		$lowest_dist = 99; //default out the lowest distance (will be replaced in loop if match found)
		$closest_suggestion = false; //default suggestion value
		$esp_domain_array = array(); //initiate $esp_domain_array

		foreach($common_esp as $domain){
		
			$dist = levenshtein($email_domain, $domain);
			
			//is the distance of this email within our threshold according to levinshtein
			if($dist <= $threshold){
				$esp_domain_array[$domain] = $dist;
			}
			
			//is this distance our new lowest distance? 
			if($dist < $lowest_dist){
				$closest_suggestion = $domain;
				$lowest_dist = $dist;
			}
			
		
		}//end foreach
		
		//check if closest distance is too far off
		if($lowest_dist >= $threshold){
			// it is, see if the TLD is broken.
			$common_esp_tlds = array("co.uk", "com", "net", "org", "info", "edu", "gov", "mil");
			$closest_suggestion = false; //recast suggestion value
			
			//fetch TLD from supplied domain
			$email_tld = explode('.', $email_domain, 2);
			$email_sld = $email_tld[0];
			$email_tld = $email_tld[1];

			$lowest_dist = 99;
			
			// initiate variables
			$closest_tld_suggestion = '';
			$tld_array = array();
			
			//loop through common_esp_tlds and find which is closest
			foreach($common_esp_tlds as $tld){
		

				$dist = levenshtein($email_tld, $tld);
				
				//is the distance of this email within our threshold according to levinshtein
				if($dist <= $threshold){
					$tld_array[$tld] = $dist;
				}
				
				//is this distance our new lowest distance? 
				if($dist < $lowest_dist){
					$closest_tld_suggestion = $tld;
					$lowest_dist = $dist;
				}
				

			}//end foreach
			
			//see if we found a suggestion for the TLD
			if($lowest_dist <= $threshold){
			
				//build new email domain
				$email_domain = $email_sld . '.' . $closest_tld_suggestion;
				
				//now we have a new $email_domain, lets take another look
				$lowest_dist = 99; //default out the lowest distance (will be replaced in loop if match found)
				$closest_suggestion = false; //default suggestion value
				$esp_domain_array = array(); //initiate $esp_domain_array

				foreach($common_esp as $domain){
				
					$dist = levenshtein($email_domain, $domain);

					//is the distance of this email within our threshold according to levinshtein
					if($dist <= $threshold){
						$esp_domain_array[$domain] = $dist;
					}
					
					//is this distance our new lowest distance? 
					if($dist < $lowest_dist){
						$closest_suggestion = $domain;
						$lowest_dist = $dist;
					}
					
				}//end foreach
			
				if($lowest_dist >= $threshold){
					$closest_suggestion = ''; //still not anythign close enough
				}

			}
		}
		return $closest_suggestion;
	}
	
}

// SMTP_Validate_Email Exceptions
class SMTP_Validate_Email_Exception extends Exception {}
class SMTP_Validate_Email_Exception_Timeout extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_Unexpected_Response extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_No_Response extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_No_Connection extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_No_Helo extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_No_Mail_From extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_No_Timeout extends SMTP_Validate_Email_Exception {}
class SMTP_Validate_Email_Exception_No_TLS extends SMTP_Validate_Email_Exception {}

// SMTP validation class (simulates sending a message to the mail box)
class SMTP_Validate_Email {

    public $connect_port = 25; // default smtp port
	
	public $catchall_is_valid = true; // Are 'catch-all' accounts considered valid or not?
    public $no_comm_is_valid = false; // Being unable to communicate with the remote MTA could mean an address is invalid, but it might not true/false
    public $greylisted_considered_valid = true; // consider "greylisted" responses as valid or invalid addresses

	
	private $socket; // holds the socket connection resource
    private $domains; // holds all the domains we'll validate accounts on
    private $connect_timeout = 10; // connect timeout for each MTA attempted (seconds)
    private $from_user = 'user';  // default username of sender
    private $from_domain = 'localhost'; // default host of sender
    private $host = null; // the host we're currently connected to
    private $results = array(); // array of validation results
    private $state = array('helo' => false, 'mail' => false, 'rcpt' => false); // states we can be in

    protected $mx_query_ns = '';     // If on Windows (or other places that don't have getmxrr()), this is the nameserver that will be used for MX querying.
    // protected $mx_query_ns = 'dns1.t-com.hr';

    // Timeout values
    protected $command_timeouts = array(
        'ehlo' => 300,
        'helo' => 300,
        'tls'  => 180, // start tls
        'mail' => 300, // mail from
        'rcpt' => 300, // rcpt to,
        'rset' => 3,
        'quit' => 300,
        'noop' => 300
    );

    // some constants
    const CRLF = "\r\n";

    // some smtp response codes
    const SMTP_CONNECT_SUCCESS = 220;
    const SMTP_QUIT_SUCCESS = 221;
    const SMTP_GENERIC_SUCCESS = 250;
    const SMTP_USER_NOT_LOCAL = 251;
    const SMTP_CANNOT_VRFY = 252;
    const SMTP_SERVICE_UNAVAILABLE = 421;
    const SMTP_MAIL_ACTION_NOT_TAKEN = 450;
    const SMTP_MAIL_ACTION_ABORTED = 451;
    const SMTP_REQUESTED_ACTION_NOT_TAKEN = 452;
    const SMTP_MBOX_UNAVAILABLE = 550;
    const SMTP_TRANSACTION_FAILED = 554;

    // list of codes considered as "greylisted"
    private $greylisted = array(
        self::SMTP_MAIL_ACTION_NOT_TAKEN,
        self::SMTP_MAIL_ACTION_ABORTED,
        self::SMTP_REQUESTED_ACTION_NOT_TAKEN
    );

    // Main constructor.
    function __construct($emails = array(), $sender = '') {
        if (!empty($emails)) {
            $this->set_emails($emails);
        }
        if (!empty($sender)) {
            $this->set_sender($sender);
        }
    }

    // Disconnects from the SMTP server
    public function __destruct() {
        $this->disconnect(false);
    }

    // Performs validation of specified email addresses.
    public function validate($emails = array(), $sender = '') {

        $this->results = array();

        if (!empty($emails)) {
            $this->set_emails($emails);
        }
        if (!empty($sender)) {
            $this->set_sender($sender);
        }

        if (!is_array($this->domains) || empty($this->domains)) {
            return $this->results;
        }

        // query the MTAs on each domain if we have them
        foreach ($this->domains as $domain => $users) {

            $mxs = array();

            // query the mx records for the current domain
            list($hosts, $weights) = $this->mx_query($domain);

            // sort out the MX priorities
            foreach ($hosts as $k => $host) {
                $mxs[$host] = $weights[$k];
            }
            asort($mxs);

            // add the hostname itself with 0 weight (RFC 2821)
            $mxs[$domain] = 0;


            // try each host
            while (list($host) = each($mxs)) {
                // try connecting to the remote host
                try {
                    $this->connect($host);
                    if ($this->connected()) {
                        break;
                    }
                } catch (SMTP_Validate_Email_Exception_No_Connection $e) {
                    // unable to connect to host, so these addresses are invalid?
                    $this->set_domain_results($users, $domain, false);
                }
            }

            // are we connected?
            if ($this->connected()) {

                // say helo, and continue if we can talk
                if ($this->helo()) {

                    // try issuing MAIL FROM
                    if (!($this->mail($this->from_user . '@' . $this->from_domain))) {
                        // MAIL FROM not accepted, we can't talk
                        $this->set_domain_results($users, $domain, $this->no_comm_is_valid);
                    }

                    // if we're still connected, proceed (cause we might get
                    if ($this->connected()) {

                        $this->noop();

                        // catch-all detection if requested
                        if (!($this->catchall_is_valid)) {
                            $garbage = 'catch-all-test-' . time();
                            $garbage_accepted = $this->rcpt($garbage . '@' . $domain);
                            if ($garbage_accepted) {
                                // success on a non-existing address is a "catch-all"
                                $this->set_domain_results($users, $domain, $this->catchall_is_valid);
                                // since catchall is not considered valid, disconnect
                                // and move to the next domain
                                $this->disconnect();
                                continue;
                            }
                            // take care of the case in which we get disconnected
                            // while trying to test catchall accounts
                            $this->noop();
                            if (!($this->connected())) {
                                $this->set_domain_results($users, $domain, $this->no_comm_is_valid);
                                continue;
                            }
                        }

                        // if we're still connected, try issuing rcpts
                        if ($this->connected()) {
                            $this->noop();
                            // rcpt to for each user
                            foreach ($users as $user) {
                                $address = $user . '@' . $domain;
                                $this->results[$address] = $this->rcpt($address);
                                $this->noop();
                            }
                        }

                        // saying buh-bye if we're still connected, cause we're done here
                        if ($this->connected()) {
                            // issue a rset for all the things we just made the MTA do
                            $this->rset();
                            // kiss it goodbye
                            $this->disconnect();
                        }

                    }

                } else {

                    // we didn't get a good response to helo and should be disconnected already
                    $this->set_domain_results($users, $domain, $this->no_comm_is_valid);
                }
            }
        }
        return $this->results;

    }

    // Sets results for all the users on a domain to a specific value
    private function set_domain_results($users, $domain, $val) {
        if (!is_array($users)) {
            $users = (array) $users;
        }
        foreach ($users as $user) {
            $this->results[$user . '@' . $domain] = $val;
        }
    }

    // Returns true if we're connected to an MTA
    protected function connected() {
        return is_resource($this->socket);
    }

    // Tries to connect to the specified host on the pre-configured port.
    protected function connect($host) {
        $remote_socket = $host . ':' . $this->connect_port;
        $errnum = 0;
        $errstr = '';
        $this->host = $remote_socket;
        // open connection
        $this->socket = @stream_socket_client(
            $this->host,
            $errnum,
            $errstr,
            $this->connect_timeout,
            STREAM_CLIENT_CONNECT,
            stream_context_create(array())
        );
        // connected?
        if (!$this->connected()) {
            throw new SMTP_Validate_Email_Exception_No_Connection('Cannot ' .
            'open a connection to remote host (' . $this->host . ')');
        }
        $result = stream_set_timeout($this->socket, $this->connect_timeout);
        if (!$result) {
            throw new SMTP_Validate_Email_Exception_No_Timeout('Cannot set timeout');
        }
    }

    // Disconnects the connected MTA.
    protected function disconnect($quit = true) {
        if ($quit) {
            $this->quit();
        }
        if ($this->connected()) {
            fclose($this->socket);
        }
        $this->host = null;
        $this->reset_state();
    }

    // Resets internal state flags
    private function reset_state() {
        $this->state['helo'] = false;
        $this->state['mail'] = false;
        $this->state['rcpt'] = false;
    }

    //Sends a HELO/EHLO sequence
    protected function helo() {
        // don't try if it was already done
        if ($this->state['helo']) {
            return;
        }
        try {
            $this->expect(self::SMTP_CONNECT_SUCCESS, $this->command_timeouts['helo']);
            $this->ehlo();
            // session started
            $this->state['helo'] = true;
            // are we going for a TLS connection?
            return true;
        } catch (SMTP_Validate_Email_Exception_Unexpected_Response $e) {
            // connected, but recieved an unexpected response, so disconnect
            $this->disconnect(false);
            return false;
        }
    }

    //Send EHLO or HELO, depending on what's supported by the remote host.
    protected function ehlo() {
        try {
            // modern, timeout 5 minutes
            $this->send('EHLO ' . $this->from_domain);
            $this->expect(self::SMTP_GENERIC_SUCCESS, $this->command_timeouts['ehlo']);
        } catch (SMTP_Validate_Email_Exception_Unexpected_Response $e) {
            // legacy, timeout 5 minutes
            $this->send('HELO ' . $this->from_domain);
            $this->expect(self::SMTP_GENERIC_SUCCESS, $this->command_timeouts['helo']);
        }
    }

    //Sends a MAIL FROM command to indicate the sender.
    protected function mail($from) {
        if (!$this->state['helo']) {
            throw new SMTP_Validate_Email_Exception_No_Helo('Need HELO before MAIL FROM');
        }
        // issue MAIL FROM, 5 minute timeout
        $this->send('MAIL FROM:<' . $from . '>');
        try {
            $this->expect(self::SMTP_GENERIC_SUCCESS, $this->command_timeouts['mail']);
            // set state flags
            $this->state['mail'] = true;
            $this->state['rcpt'] = false;
            return true;
        } catch (SMTP_Validate_Email_Exception_Unexpected_Response $e) {
            // got something unexpected in response to MAIL FROM
            // hotmail is know to do this, and is closing the connection
            // forcibly on their end, so I'm killing the socket here too
            $this->disconnect(false);
            return false;
        }
    }

    // Sends a RCPT TO command to indicate a recipient.
    protected function rcpt($to) {
        // need to have issued MAIL FROM first
        if (!$this->state['mail']) {
            throw new SMTP_Validate_Email_Exception_No_Mail_From('Need MAIL FROM before RCPT TO');
        }
        $is_valid = false;
        $expected_codes = array(
            self::SMTP_GENERIC_SUCCESS,
            self::SMTP_USER_NOT_LOCAL
        );
        if ($this->greylisted_considered_valid) {
            $expected_codes += $this->greylisted;
        }
        // issue RCPT TO, 5 minute timeout
        try {
            $this->send('RCPT TO:<' . $to . '>');
            // process the response
            try {
                $this->expect($expected_codes, $this->command_timeouts['rcpt']);
                $this->state['rcpt'] = true;
                $is_valid = true;
            } catch (SMTP_Validate_Email_Exception_Unexpected_Response $e) {
            }
        } catch (SMTP_Validate_Email_Exception $e) {
        }
        return $is_valid;
    }

    // Sends a RSET command and resets our internal state.
    protected function rset() {
        $this->send('RSET');
        // MS ESMTP doesn't follow RFC according to ZF tracker, see [ZF-1377]
        $expected = array(
            self::SMTP_GENERIC_SUCCESS,
            self::SMTP_CONNECT_SUCCESS,
            // hotmail returns this o_O
            self::SMTP_TRANSACTION_FAILED
        );
        $this->expect($expected, $this->command_timeouts['rset']);
        $this->state['mail'] = false;
        $this->state['rcpt'] = false;
    }

    // Sends a QUIT command.
    protected function quit() {
        // although RFC says QUIT can be issued at any time, we won't
        if ($this->state['helo']) {
            // [TODO] might need a try/catch here to cover some edge cases...
            $this->send('QUIT');
            $this->expect(self::SMTP_QUIT_SUCCESS, $this->command_timeouts['quit']);
        }
    }

    // Sends a NOOP command.
    protected function noop() {
        $this->send('NOOP');
        $this->expect(self::SMTP_GENERIC_SUCCESS, $this->command_timeouts['noop']);
    }

    // Sends a command to the remote host.
    protected function send($cmd) {
        // must be connected
        if (!$this->connected()) {
            throw new SMTP_Validate_Email_Exception_No_Connection('No connection');
        }
        // write the cmd to the connection stream
        $result = fwrite($this->socket, $cmd . self::CRLF);
        // did the send work?
        if ($result === false) {
            throw new SMTP_Validate_Email_Exception_Send_Failed('Send failed ' .
            'on: ' . $this->host);
        }
        return $result;
    }

    // Receives a response line from the remote host.
    protected function recv($timeout = null) {
        if (!$this->connected()) {
            throw new SMTP_Validate_Email_Exception_No_Connection('No connection');
        }
        // timeout specified?
        if ($timeout !== null) {
            stream_set_timeout($this->socket, $timeout);
        }
        // retrieve response
        $line = fgets($this->socket, 1024);
        // have we timed out?
        $info = stream_get_meta_data($this->socket);
        if (!empty($info['timed_out'])) {
            throw new SMTP_Validate_Email_Exception_Timeout('Timed out in recv');
        }
        // did we actually receive anything?
        if ($line === false) {
            throw new SMTP_Validate_Email_Exception_No_Response('No response in recv');
        }
        return $line;
    }

    // Receives lines from the remote host and looks for expected response codes.
    protected function expect($codes, $timeout = null) {
        if (!is_array($codes)) {
            $codes = (array) $codes;
        }
        $code = null;
        $text = '';
        try {

            $text = $line = $this->recv($timeout);
            while (preg_match("#^[0-9]+-#", $line)) {
                $line = $this->recv($timeout);
                $text .= $line;
            }
            sscanf($line, '%d%s', $code, $text);
            if ($code === null || !in_array($code, $codes)) {
                throw new SMTP_Validate_Email_Exception_Unexpected_Response($line);
            }

        } catch (SMTP_Validate_Email_Exception_No_Response $e) {

            // no response in expect() probably means that the
            // remote server forcibly closed the connection so
            // lets clean up on our end as well?
            $this->disconnect(false);

        }
        return $text;
    }

    // Parses an email string into respective user and domain parts and
    protected function parse_email($email) {
        $parts = explode('@', $email);
        $domain = array_pop($parts);
        $user= implode('@', $parts);
        return array($user, $domain);
    }

    // Sets the email addresses that should be validated.
    public function set_emails($emails) {
        if (!is_array($emails)) {
            $emails = (array) $emails;
        }
        $this->domains = array();
        foreach ($emails as $email) {
            list($user, $domain) = $this->parse_email($email);
            if (!isset($this->domains[$domain])) {
                $this->domains[$domain] = array();
            }
            $this->domains[$domain][] = $user;
        }
    }

    // Sets the email address to use as the sender/validator.
    public function set_sender($email) {
        $parts = $this->parse_email($email);
        $this->from_user = $parts[0];
        $this->from_domain = $parts[1];
    }

    // Queries the DNS server for MX entries of a certain domain.
    protected function mx_query($domain) {
        $hosts = array();
        $weight = array();
        if (function_exists('getmxrr')) {
            getmxrr($domain, $hosts, $weight);
        } else {
            $this->getmxrr($domain, $hosts, $weight);
        }
        return array($hosts, $weight);
    }

    // Provides a windows replacement for the getmxrr function.
    protected function getmxrr($hostname, &$mxhosts, &$mxweights) {
        if (!is_array($mxhosts)) {
            $mxhosts = array();
        }
        if (!is_array($mxweights)) {
            $mxweights = array();
        }
        if (empty($hostname)) {
            return;
        }
        $cmd = 'nslookup -type=MX ' . escapeshellarg($hostname);
        if (!empty($this->mx_query_ns)) {
            $cmd .= ' ' . escapeshellarg($this->mx_query_ns);
        }
        exec($cmd, $output);
        if (empty($output)) {
            return;
        }
        $i = -1;
        foreach ($output as $line) {
            $i++;
            if (preg_match("/^$hostname\tMX preference = ([0-9]+), mail exchanger = (.+)$/i", $line, $parts)) {
                $mxweights[$i] = trim($parts[1]);
                $mxhosts[$i] = trim($parts[2]);
            }
            if (preg_match('/responsible mail addr = (.+)$/i', $line, $parts)) {
                $mxweights[$i] = $i;
                $mxhosts[$i] = trim($parts[1]);
            }
        }
        return ($i != -1);
    }
}

?>