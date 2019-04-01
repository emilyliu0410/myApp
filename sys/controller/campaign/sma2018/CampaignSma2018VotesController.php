<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
require_once(dirname(__FILE__).'/includes.php');
class CampaignSma2018VotesController extends UController
{
	public $useMasterDb = true;
	
	private $ans_options_title = array(
		"「我最喜愛商場」 20強候選名單",
		"「我最喜愛商場活動」 50強候選名單",
		"澳門之選10強候選名單",
		"「最佳數碼社交媒體 - 最佳商場Facebook專頁」",
		"「最佳商場應用程式」",
	);
	private $ans_options = array(
		array(
			"apm",
			"ELEMENTS圓方",
			"ifc mall",
			"K11",
			"MCP 新都城中心",
			"Mira Place",
			"MOKO 新世紀廣場",
			"T.O.P This Is Our Place",
			"The ONE",
			"YOHO MALL 形點",
			"又一城",
			"太古城中心",
			"太古廣場",
			"屯門市廣場",
			"利園區 ",
			"時代廣場",
			"朗豪坊",
			"海港城",
			"奧海城",
			"新城市廣場",
		),
		array(
			"Jolly Rockin' Xmas 巨型木馬裝置",
			"1881 Heritage",
			"apm 6000呎巨型俄羅斯足球館",
			"apm",
			"荃新天地 愛家愛到飛起",
			"Citywalk 荃新天地",
			"家+品味$1秒殺大放送",
			"D • PARK 愉景新城",
			"「香港の秋祭り」",
			"D2 Place",
			"The Snowman and The Snowdog",
			"ELEMENTS 圓方",
			"Fashion Walk彩獅「King」城慶新春",
			"Fashion Walk",
			"HomeSquare第9屆「香港家居折」",
			"HomeSquare",
			"MEET THE T.REX: 探索暴龍世界",
			"ifc mall",
			"My Melody春日花園",
			"iSQUARE 國際廣場",
			"THE UNIVERSE IN ME: A CHRISTMAS VOYAGE",
			"K11",
			"我們的啟德機場",
			"KCP 九龍城廣場",
			"MCP CENTRAL X Anna Lomax「玩轉日夜『迷』城」",
			"MCP 新都城中心",
			"MegaBox x PAW Patrol 喜氣洋洋慶新春",
			"MegaBox",
			"DOO Something This Christmas",
			"Mira Place",
			"MOKO 新世紀廣場 • Shiny Golden Christmas 閃亮聖誕",
			"MOKO 新世紀廣場",
			"MOSTown新港城中心Rebranding Campaign",
			"MOSTown",
			"Jungle All The Way: 走杯GO CUP x PMQ 零捨好市集Christmas Zero Mart",
			"PMQ元創方",
			"PopCorn xNAMCO ぱぱぱ Party 玩！玩！玩！體驗館",
			"PopCorn",
			"SML Expo",
			"T.O.P This Is Our Place",
			"「The ONE x Sailor Moon 月光傳說」",
			"The ONE",
			"V city x To-Fu Oyako聖誕玩樂派對",
			"V City",
			"The Coolest Christmas",
			"YOHO MALL 形點",
			"Marvel Studios 10周年及《復仇者聯盟3：無限之戰》展覽",
			"又一城",
			"花漾 ‧ 紙藝萬花園Pop-Up展覽",
			"上水廣場",
			"Afro Ken™爆炸狗™百變聖誕Party",
			"大埔超級城",
			"「復活節星空巡遊」",
			"山頂廣場",
			"MTR Malls x 《加菲貓》G40",
			"ELEMENTS 圓方，青衣城，綠楊坊",
			"太古城中心「Delightful Christmas聖誕光影樂園」",
			"太古城中心",
			"「TOMICA奇妙夢車場」",
			"屯門市廣場",
			"Sino Malls Goal Together 信和集團商場Goal Together全城開波",
			"屯門市廣場 • 奧海城 • 荃新天地",
			"月兔• 秋燈",
			"利東街",
			"All is Joyful, All is Bright: Christmas at LEE GARDENS",
			"利園區",
			"第十二屆新春花展會「瑞犬報喜迎戊戌」",
			"東港城",
			"【皇室堡 x Rilakkuma輕鬆小熊 夢幻雪國】",
			"皇室堡",
			"時代廣場「GUNDAM DOCKS AT HONG KONG III」",
			"時代廣埸",
			"朗豪坊Gudetama梳乎遊記",
			"朗豪坊",
			"海港城「情．尋朱古力」2018",
			"海港城",
			"亞洲玩具展TOYSOUL 2018 – Junior版",
			"荃新天地",
			"荃灣千色匯及元朗千色匯「萌爆哥基夢新春」",
			"荃灣千色匯 及 元朗千色匯",
			"Fly Me to the Moon 星月幻想曲",
			"荃灣廣場",
			"FUN TO INFINITY 運動無限",
			"淘大商場",
			"台灣滷肉飯節@荷里活廣場",
			"荷里活廣場",
			"Let's Tune in to X'mas",
			"雅蘭中心 • 荷李活商業中心 • 家樂坊",
			"反斗親子聖誕嘉年華",
			"黃金海岸商場",
			"黃埔WOW WOW狗賀新歲",
			"黃埔天地",
			"奧海城OC STEM Lab X UGEARS STEM尋寶之旅",
			"奧海城",
			"Rosy Christmas @ Starlight Garden 紅粉夢樂園",
			"新城市廣場",
			"芬蘭聖誕市集",
			"領展赤柱廣場",
			"「高迪築 ‧ 跡」",
			"德福廣場",
		),
		array(
			"澳門銀河「時尚匯」購物中心",
			"巴黎人購物中心",
			"四季‧名店",
			"永利名店購物區",
			"威尼斯人購物中心",
			"美獅美高梅",
			"新濠影滙購物大道",
			"摩珀斯酒店",
			"澳門金沙城中心",
			"澳門壹號廣塲",
		),
		array(
			"apm",
			"Citywalk 荃新天地",
			"D • PARK愉景新城",
			"HarbourCity",
			"HK ifc mall 香港國際金融中心商場",
			"Hong Kong Times Square",
			"K11",
			"Langham Place 朗豪坊",
			"Lee Gardens",
			"MegaBox",
			"Mira Place",
			"MOKO 新世紀廣場",
			"New Town Plaza 新城市廣場",
			"The ONE",
			"tmtplaza 屯門市廣場",
			"WTC 世貿中心",
			"YOHO MALL 形點",
			"上水廣場 Landmark North",
			"大埔超級城",
			"奧海城 Olympian City",
		),
		array(
			"apm",
			"Citywalk 荃新天地",
			"D • Park",
			"Elements Mall",
			"Festival Walk 又一城",
			"HomeSquare",
			"Hong Kong Times Square",
			"ifc mall (Hong Kong)",
			"Lee Gardens",
			"MCP CENTRAL & MCP DISCOVERY",
			"Mira Place",
			"MOKO 新世紀廣場",
			"Olympian City - 奧海城",
			"T.O.P This Is Our Place",
			"Tai Po Mega Mall 大埔超級城",
			"The ONE",
			"tmtplaza 屯門市廣場",
			"V city HK",
			"Windsor",
			"YOHO MALL 形點",
			"朗豪坊",
			"黃埔天地",
			"新城市廣場",
			"新鴻基地產商場 SHKP Malls",
			"領展泊食易",
		),
	);
	private $ans_options_img = array(
		array(),
		array(),
		array(),
		array(),
		array(
			"1_apm.png",
			"2_citywalk.png",
			"3_D_Park.png",
			"4_Elements.png",
			"5_Festival_Walk.png",
			"6_HomeSquare.png",
			"7_Times_Square.png",
			"8_IFC.png",
			"9_Lee_Gardens.png",
			"10_MCP_App.png",
			"11_Mira_Place.png",
			"12_MOKO.png",
			"13_OlympianCity.png",
			"14_TOP.png",
			"15_TPMM.png",
			"16_The_ONE.png",
			"17_tmtplaza.png",
			"18_VCity.png",
			"19_Windsor.png",
			"20_YOHO.png",
			"21_LanghamPlace.png",
			"22_Whampoa.png",
			"23_NewTownPlaza.png",
			"24_SHKPMalls.png",
			"25_Park_n_Dine.png",
			),
	);
	
	private $ans_options_title_vote = array(
		"「我最喜愛商場」",	
		"「我最喜愛商場活動」",
		"「澳門之選10強候選名單」",
		"「最佳數碼社交媒體 - 最佳商場Facebook專頁」",
		"「最佳數碼社交媒體 - 最佳商場應用程式」",
	);
	private $ans_options_title_desc = array(
		"請於以下20個候選單位中（按筆劃排序），選出10個 「我最喜愛商場」:",
		"請於以下50個候選單位中選出 25 個 「我最喜愛商場活動」:",
		"請於以下10個候選單位中（按筆劃排序），選出1個 「澳門之選10強候選名單」:",
		"請於以下20個候選單位中（按筆劃排序），選出1個 「最佳數碼社交媒體 - 最佳商場Facebook專頁」:",
		"請於以下25個候選單位中（按筆劃排序），選出1個 「最佳數碼社交媒體 - 最佳商場應用程式」:",
	);
	
	var $page_name = 'list';
	var $page_title = '候選名單';
	function actionList(){
		$this->assign('ans_options_title',$this->ans_options_title);
		$this->assign('ans_options',$this->ans_options);
		$this->assign('ans_options_img',$this->ans_options_img);

		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir= UAPP_BASE_URL.'/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/vote');
		$this->view=CAMPAIGN_LAYOUT;
		$this->layout='campaign';
		$this->assign('view',$this->page_name);
		$this->assign('header_active',array('index'=>1,'tnc'=>0));
		
		array_push($this->cssFiles, $imgDir.'css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->jsFiles, $imgDir.'js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);

		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = $this->page_title.' | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
		$this->display();
		
	}
	
	function actionIndex(){
		$this->page_name = 'votes';
		$this->page_title = '公眾投票';
		
		$this->assign('ans_options_title_vote',$this->ans_options_title_vote);
		$this->assign('ans_options_title_desc',$this->ans_options_title_desc);
		$this->assign('ans_options',$this->ans_options);
		$this->assign('ans_options_img',$this->ans_options_img);
		
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir= UAPP_BASE_URL.'/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('htmlDir',$htmlDir);

//		$user_id = $this->user->user_id;
//		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
//		$useremail = uDb()->findOne('SELECT email FROM tbl_users WHERE user_id="'.$user_id.'"')->email;
//		$this->assign('user_id',$user_id);
//		$this->assign('username',$username);
//		$this->assign('useremail',$useremail);
		
		$isLogin = $user_id > 0  ? true:false;
		// $isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$isVoted = false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/'.$this->page_name);
		$this->view=CAMPAIGN_LAYOUT;
		$this->layout='campaign';
		$this->assign('view',$this->page_name);
		$this->assign('header_active',array('index'=>1,'tnc'=>0));
		
		array_push($this->cssFiles, $imgDir.'css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->jsFiles, $imgDir.'js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		array_push($this->jsFiles, $imgDir.'js/jquery.form.js?v='.CAMPAIGN_CACHE);
		array_push($this->jsFiles, $imgDir.'js/campaign.js?v='.CAMPAIGN_CACHE);

		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = $this->page_title.' | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
        $this->display();
	}
	
	function actionValidation(){
		if(isset($_POST['form_action'])){
			$GLOBALS['user_id'] = $this->user->user_id;
			$action = safe_input($_POST['form_action']);
			
			$response = new stdClass();
			$response->error = 0;
			
			$model = new CampaignAnswer();
			$valid = $model->validPost();

			if($valid->error){
				$response->error = $valid->error;
			}else{
				if(!$model->store()) 
					$response->error = 'Data store failed!';
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
}
?>