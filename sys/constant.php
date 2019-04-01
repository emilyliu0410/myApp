<?php 
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

include_once(UFM_DIR.'/include/adzone.php');

define('CST_SITE_NAME', 	'港生活  - 尋找香港好去處');
define('MALL_SITE_NAME', 	'Mall 王');
define('CST_SESSION_EXPIRE_TIME', '3600'); // session_expire_time in seconds, 3600 = 60*60 = 60 mins
define('CST_COOKIES_PREFIX', 'uhk-');

define('UAPP_COOKIE_PREFIX', 'UHK_');

define('CMS_ADMIN_GROUP',25);

define('CMS_MAX_PHOTO_WIDTH', 250);
define('CMS_MAX_PHOTO_HEIGHT', 250);
define('CMS_MAX_FULL_PHOTO_WIDTH', 1024);
define('CMS_MAX_FULL_PHOTO_HEIGHT', 1024);
define('CMS_MAX_PREVIEW_PHOTO_WIDTH', 100);
define('CMS_MAX_PREVIEW_PHOTO_HEIGHT', 100);
define('CMS_MAX_FRONTPAGE_PHOTO_WIDTH', 150);
define('CMS_MAX_FRONTPAGE_PHOTO_HEIGHT', 120);
define('CMS_MAX_THUMBNAIL_PHOTO_WIDTH', 200);
define('CMS_MAX_THUMBNAIL_PHOTO_HEIGHT',200);
define('CMS_MAX_SEARCHPAGE_PHOTO_WIDTH', 188);
define('CMS_MAX_SEARCHPAGE_PHOTO_HEIGHT',168);
define('CMS_MAX_TINY_PHOTO_WIDTH',30);
define('CMS_MAX_TINY_PHOTO_HEIGHT',30);

//define('LOG_PATH',dirname(dirname(__FILE__).'..').'/log/');

/******************************
* Page Type
*******************************/	
define("PAGE_TYPE_EVENT","1");

/******************************
* Meta Data
*******************************/	
define("CST_DEFAULT_META_DESCRIPTION","U HK 港生活 即時緊貼HK Style，一網打盡全港生活資訊 - 演唱會、展覽、著數活動、香港好地方、各區周圍遊路線、即時最HIT熱話等，包你搵到消閒好去處!");
define("CST_DEFAULT_META_KEYWORDS","U HK, 港生活, 活動, 演唱會, 展覽, 著數, 快閃, 香港, 活動, 周末, 著數, 好地方, 好去處, 周圍遊, 景點, 熱話, 話題, 趣聞, 膠聞, Lifestyle 資訊, 消息, 座位表, 門票, 免費, 行山, 郊外, 交通");	

/******************************
* Email Related
*******************************/	
define('CST_ALERT_EMAIL_FROM', 'uhk@ulifestyle.com.hk');
define('CST_ALERT_EMAIL_FROM_NAME', CST_SITE_NAME);

define('CST_CACHE_BUST', '201703290940');
define('CST_CSS_CACHE_BUST', '201703290940');
define('CST_JS_CACHE_BUST', '201703290940');
define('ENABLE_COMPRESSION', true);

/* switch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)){
	case UAPP_BASE_URL."/index.html":
	case UAPP_BASE_URL."/":
		$uAdverts= array(
			"'/183518426/uhk_webdt_homepage_babybanner1', [300,100], 'div-gpt-ad-1429860755546-0'",
			"'/183518426/uhk_webdt_homepage_babybanner2', [300,100], 'div-gpt-ad-1429860755546-1'",
			"'/183518426/uhk_webdt_homepage_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429860755546-2'",
			"'/183518426/uhk_webdt_homepage_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429860755546-3'",
			"'/183518426/uhk_webdt_homepage_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860755546-4'",
			"'/183518426/uhk_webdt_homepage_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860755546-5'",
			"'/183518426/uhk_webdt_homepage_superbanner1', [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429860755546-6'",
			"'/183518426/uhk_webdt_homepage_superbanner2', [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429860755546-7'",
			"'/183518426/uhk_webdt_homepage_special', [1, 1], 'div-gpt-ad-1441340293099-6'"
		);
	break;
	case UAPP_BASE_URL."/activity/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_activity_lst_babybanner1', [300,100], 'div-gpt-ad-1429860922816-0'",
			"'/183518426/uhk_webdt_activity_lst_babybanner2', [300,100], 'div-gpt-ad-1429860922816-1'",
			"'/183518426/uhk_webdt_activity_lst_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429860922816-2'",
			"'/183518426/uhk_webdt_activity_lst_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429860922816-3'",
			"'/183518426/uhk_webdt_activity_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860922816-4'",
			"'/183518426/uhk_webdt_activity_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860922816-5'",
			"'/183518426/uhk_webdt_activity_lst_superbanner1',  [[970, 250], [970, 90], [970, 160], [728, 90]], 'div-gpt-ad-1429860922816-6'",
			"'/183518426/uhk_webdt_activity_lst_superbanner2',  [[970, 250], [970, 90], [970, 160], [728, 90]], 'div-gpt-ad-1429860922816-7'"
		);
	break;
	case UAPP_BASE_URL."/activity/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		);
	break;
	case UAPP_BASE_URL."/location/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		);
	break;
	case UAPP_BASE_URL."/location/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		);
	break;
	case UAPP_BASE_URL."/spot/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_spot_lst_babybanner1', [300,100], 'div-gpt-ad-1429860978363-0'",
			"'/183518426/uhk_webdt_spot_lst_babybanner2', [300,100], 'div-gpt-ad-1429860978363-1'",
			"'/183518426/uhk_webdt_spot_lst_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429860978363-2'",
			"'/183518426/uhk_webdt_spot_lst_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429860978363-3'",
			"'/183518426/uhk_webdt_spot_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860978363-4'",
			"'/183518426/uhk_webdt_spot_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860978363-5'",
			"'/183518426/uhk_webdt_spot_lst_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860978363-6'",
			"'/183518426/uhk_webdt_spot_lst_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860978363-7'"
		);
	break;
	case UAPP_BASE_URL."/spot/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_spot_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861006528-0'",
			"'/183518426/uhk_webdt_spot_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861006528-1'",
			"'/183518426/uhk_webdt_spot_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861006528-2'",
			"'/183518426/uhk_webdt_spot_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861006528-3'",
			"'/183518426/uhk_webdt_spot_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861006528-4'",
			"'/183518426/uhk_webdt_spot_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861006528-5'",
			"'/183518426/uhk_webdt_spot_dtl_superbanner1',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861006528-6'",
			"'/183518426/uhk_webdt_spot_dtl_superbanner2',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861006528-7'"
		);
	break;
	case UAPP_BASE_URL."/tour/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_tour_lst_babybanner1', [300,100], 'div-gpt-ad-1429861086244-0'",
			"'/183518426/uhk_webdt_tour_lst_babybanner2', [300,100], 'div-gpt-ad-1429861086244-1'",
			"'/183518426/uhk_webdt_tour_lst_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861086244-2'",
			"'/183518426/uhk_webdt_tour_lst_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861086244-3'",
			"'/183518426/uhk_webdt_tour_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861086244-4'",
			"'/183518426/uhk_webdt_tour_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861086244-5'",
			"'/183518426/uhk_webdt_tour_lst_superbanner1',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861086244-6'",
			"'/183518426/uhk_webdt_tour_lst_superbanner2',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861086244-7'"
		);
	break;
	case UAPP_BASE_URL."/tour/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_tour_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861124571-0'",
			"'/183518426/uhk_webdt_tour_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861124571-1'",
			"'/183518426/uhk_webdt_tour_dtl_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429861124571-2'",
			"'/183518426/uhk_webdt_tour_dtl_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429861124571-3'",
			"'/183518426/uhk_webdt_tour_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861124571-4'",
			"'/183518426/uhk_webdt_tour_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861124571-5'",
			"'/183518426/uhk_webdt_tour_dtl_superbanner1',  [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429861124571-6'",
			"'/183518426/uhk_webdt_tour_dtl_superbanner2',  [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429861124571-7'"
		);
	break;
	case UAPP_BASE_URL."/topic/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_topic_lst_babybanner1', [300,100], 'div-gpt-ad-1429861154740-0'",
			"'/183518426/uhk_webdt_topic_lst_babybanner2', [300,100], 'div-gpt-ad-1429861154740-1'",
			"'/183518426/uhk_webdt_topic_lst_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429861154740-2'",
			"'/183518426/uhk_webdt_topic_lst_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429861154740-3'",
			"'/183518426/uhk_webdt_topic_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861154740-4'",
			"'/183518426/uhk_webdt_topic_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861154740-5'",
			"'/183518426/uhk_webdt_topic_lst_superbanner1',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861154740-6'",
			"'/183518426/uhk_webdt_topic_lst_superbanner2',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861154740-7'"
		);
	break;
	case UAPP_BASE_URL."/topic/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_topic_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861175783-0'",
			"'/183518426/uhk_webdt_topic_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861175783-1'",
			"'/183518426/uhk_webdt_topic_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861175783-2'",
			"'/183518426/uhk_webdt_topic_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861175783-3'",
			"'/183518426/uhk_webdt_topic_dtl_skyscraper1',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861175783-4'",
			"'/183518426/uhk_webdt_topic_dtl_skyscraper2',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861175783-5'",
			"'/183518426/uhk_webdt_topic_dtl_superbanner1',  [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429861175783-6'",
			"'/183518426/uhk_webdt_topic_dtl_superbanner2',  [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429861175783-7'"
		);
	break;
	
	
	case UAPP_BASE_URL."/member/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_member_lst_babybanner1', [300,100], 'div-gpt-ad-1429861241664-0'",
			"'/183518426/uhk_webdt_member_lst_babybanner2', [300,100], 'div-gpt-ad-1429861241664-1'",
			"'/183518426/uhk_webdt_member_lst_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861241664-2'",
			"'/183518426/uhk_webdt_member_lst_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861241664-3'",
			"'/183518426/uhk_webdt_member_lst_skyscraper1',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861241664-4'",
			"'/183518426/uhk_webdt_member_lst_skyscraper2',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861241664-5'",
			"'/183518426/uhk_webdt_member_lst_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861241664-6'",
			"'/183518426/uhk_webdt_member_lst_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861241664-7'"
		);
	break;
	case UAPP_BASE_URL."/member/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webdt_member_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861266487-0'",
			"'/183518426/uhk_webdt_member_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861266487-1'",
			"'/183518426/uhk_webdt_member_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861266487-2'",
			"'/183518426/uhk_webdt_member_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861266487-3'",
			"'/183518426/uhk_webdt_member_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861266487-4'",
			"'/183518426/uhk_webdt_member_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861266487-5'",
			"'/183518426/uhk_webdt_member_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861266487-6'",
			"'/183518426/uhk_webdt_member_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861266487-7'"
		);
	break;
	
	
	case UAPP_BASE_URL."/m/activity/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_activity_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280504117-0'",
			"'/183518426/uhk_webmb_activity_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280504117-1'",
			"'/183518426/uhk_webmb_activity_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280504117-2'",
		);
	break;
	case UAPP_BASE_URL."/m/activity/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_activity_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280529457-0'",
		);
	break;
	case UAPP_BASE_URL."/m/spot/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_spot_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280553393-0'",
			"'/183518426/uhk_webmb_spot_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280553393-1'",
			"'/183518426/uhk_webmb_spot_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280553393-2'",
		);
	break;
	case UAPP_BASE_URL."/m/spot/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_spot_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280572744-0'",
		);
	break;
	case UAPP_BASE_URL."/m/tour/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_tour_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280595555-0'",
			"'/183518426/uhk_webmb_tour_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280595555-1'",
			"'/183518426/uhk_webmb_tour_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280595555-2'",
		);
	break;
	case UAPP_BASE_URL."/m/tour/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_tour_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280613112-0'",
		);
	break;
	case UAPP_BASE_URL."/m/topic/list.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_topic_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280637257-0'",
			"'/183518426/uhk_webmb_topic_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280637257-1'",
			"'/183518426/uhk_webmb_topic_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280637257-2'",
		);
	break;
	case UAPP_BASE_URL."/m/topic/detail.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_topic_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280656999-0'",
		);
	break;
	case UAPP_BASE_URL."/m/index.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_homepage_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1438336721133-0'",
			"'/183518426/uhk_webmb_homepage_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1438336721133-1'",
		);
	break;
	case UAPP_BASE_URL."/m/search/index.html":
		$uAdverts= array(
			"'/183518426/uhk_webmb_search_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1438336751114-0'",
			"'/183518426/uhk_webmb_search_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1438336751114-1'",
		);
	break;
	case UAPP_BASE_URL."/mall":
	case UAPP_BASE_URL."/mall/":
	case UAPP_BASE_URL."/mall/index.html":
	case UAPP_BASE_URL."/m/mall":
	case UAPP_BASE_URL."/m/mall/":
	case UAPP_BASE_URL."/m/mall/index.html":
		$uAdverts= array(
			"'/183518426/mallking_webdt_homepage_babybanner1', [300, 100], 'div-gpt-ad-1429862818447-0'",
			"'/183518426/mallking_webdt_homepage_babybanner2', [300, 100], 'div-gpt-ad-1429862818447-1'",
			"'/183518426/mallking_webdt_homepage_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429862818447-2'",
			"'/183518426/mallking_webdt_homepage_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429862818447-3'",
			"'/183518426/mallking_webdt_homepage_skyscraper1', [[160, 600], [120, 600]], 'div-gpt-ad-1429862818447-4'",
			"'/183518426/mallking_webdt_homepage_skyscraper2', [[160, 600], [120, 600]], 'div-gpt-ad-1429862818447-5'",
			"'/183518426/mallking_webdt_homepage_superbanner1', [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429862818447-6'",
			"'/183518426/mallking_webdt_homepage_superbanner2', [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429862818447-7'",
		);
	break;
	case UAPP_BASE_URL."/mall/list.html":
	case UAPP_BASE_URL."/m/mall/list.html":
		$uAdverts= array(
			"'/183518426/mallking_webdt_mall_lst_babybanner1', [300, 100], 'div-gpt-ad-1429862844154-0'",
			"'/183518426/mallking_webdt_mall_lst_babybanner2', [300, 100], 'div-gpt-ad-1429862844154-1'",
			"'/183518426/mallking_webdt_mall_lst_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429862844154-2'",
			"'/183518426/mallking_webdt_mall_lst_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429862844154-3'",
			"'/183518426/uhk_webdt_other_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-4'",
			"'/183518426/uhk_webdt_other_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-5'",
			"'/183518426/uhk_webdt_other_superbanner1',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861291959-6'"
		);
	break;
	case UAPP_BASE_URL."/mall/detail.html":
	case UAPP_BASE_URL."/m/mall/detail.html":
		$uAdverts= array(
			"'/183518426/mallking_webdt_mall_dtl_babybanner1', [300, 100], 'div-gpt-ad-1429862870755-0'",
			"'/183518426/mallking_webdt_mall_dtl_babybanner2', [300, 100], 'div-gpt-ad-1429862870755-1'",
			"'/183518426/mallking_webdt_mall_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429862870755-2'",
			"'/183518426/mallking_webdt_mall_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429862870755-3'",
			"'/183518426/mallking_webdt_mall_dtl_skyscraper1', [[120, 600], [160, 600]], 'div-gpt-ad-1429862870755-4'",
			"'/183518426/mallking_webdt_mall_dtl_skyscraper2', [[120, 600], [160, 600]], 'div-gpt-ad-1429862870755-5'",
			"'/183518426/mallking_webdt_mall_dtl_superbanner1', [[970, 160], [728, 90], [970, 90], [970, 250]], 'div-gpt-ad-1429862870755-6'",
			"'/183518426/mallking_webdt_mall_dtl_superbanner2', [[970, 160], [728, 90], [970, 90], [970, 250]], 'div-gpt-ad-1429862870755-7'",
		);
	break;
	default:
		$uAdverts= array(
			"'/183518426/uhk_webdt_other_babybanner1', [300,100], 'div-gpt-ad-1429861291959-0'",
			"'/183518426/uhk_webdt_other_babybanner2', [300,100], 'div-gpt-ad-1429861291959-1'",
			"'/183518426/uhk_webdt_other_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429861291959-2'",
			"'/183518426/uhk_webdt_other_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429861291959-3'",
			"'/183518426/uhk_webdt_other_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-4'",
			"'/183518426/uhk_webdt_other_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-5'",
			"'/183518426/uhk_webdt_other_superbanner1',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861291959-6'",
			"'/183518426/uhk_webdt_other_superbanner2',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861291959-7'"
		);
	break;
} */


$uAdverts_list= array(
		'/index'=>array(
			"'/183518426/uhk_webdt_homepage_babybanner1', [300,100], 'div-gpt-ad-1429860755546-0'",
			"'/183518426/uhk_webdt_homepage_babybanner2', [300,100], 'div-gpt-ad-1429860755546-1'",
			"'/183518426/uhk_webdt_homepage_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429860755546-2'",
			"'/183518426/uhk_webdt_homepage_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429860755546-3'",
			"'/183518426/uhk_webdt_homepage_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860755546-4'",
			"'/183518426/uhk_webdt_homepage_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860755546-5'",
			"'/183518426/uhk_webdt_homepage_superbanner1', [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429860755546-6'",
			"'/183518426/uhk_webdt_homepage_superbanner2', [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429860755546-7'",
			"'/183518426/uhk_webdt_homepage_special', [1, 1], 'div-gpt-ad-1441340293099-6'"
		),
		'/activity/list'=>array(
			"'/183518426/uhk_webdt_activity_lst_babybanner1', [300,100], 'div-gpt-ad-1429860922816-0'",
			"'/183518426/uhk_webdt_activity_lst_babybanner2', [300,100], 'div-gpt-ad-1429860922816-1'",
			"'/183518426/uhk_webdt_activity_lst_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429860922816-2'",
			"'/183518426/uhk_webdt_activity_lst_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429860922816-3'",
			"'/183518426/uhk_webdt_activity_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860922816-4'",
			"'/183518426/uhk_webdt_activity_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860922816-5'",
			"'/183518426/uhk_webdt_activity_lst_superbanner1',  [[970, 250], [970, 90], [970, 160], [728, 90]], 'div-gpt-ad-1429860922816-6'",
			"'/183518426/uhk_webdt_activity_lst_superbanner2',  [[970, 250], [970, 90], [970, 160], [728, 90]], 'div-gpt-ad-1429860922816-7'"
		),
	'/activity/detail'=>array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
		'/location/detail'=> array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
		'/location/list'=> array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
		'/member/followed'=> array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
	'/spot/list'=>array(
			"'/183518426/uhk_webdt_spot_lst_babybanner1', [300,100], 'div-gpt-ad-1429860978363-0'",
			"'/183518426/uhk_webdt_spot_lst_babybanner2', [300,100], 'div-gpt-ad-1429860978363-1'",
			"'/183518426/uhk_webdt_spot_lst_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429860978363-2'",
			"'/183518426/uhk_webdt_spot_lst_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429860978363-3'",
			"'/183518426/uhk_webdt_spot_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860978363-4'",
			"'/183518426/uhk_webdt_spot_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860978363-5'",
			"'/183518426/uhk_webdt_spot_lst_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860978363-6'",
			"'/183518426/uhk_webdt_spot_lst_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860978363-7'"
		),
		'/spot/detail'=>array(
			"'/183518426/uhk_webdt_spot_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861006528-0'",
			"'/183518426/uhk_webdt_spot_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861006528-1'",
			"'/183518426/uhk_webdt_spot_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861006528-2'",
			"'/183518426/uhk_webdt_spot_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861006528-3'",
			"'/183518426/uhk_webdt_spot_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861006528-4'",
			"'/183518426/uhk_webdt_spot_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861006528-5'",
			"'/183518426/uhk_webdt_spot_dtl_superbanner1',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861006528-6'",
			"'/183518426/uhk_webdt_spot_dtl_superbanner2',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861006528-7'"
		),
	'/tour/list'=>array(
			"'/183518426/uhk_webdt_tour_lst_babybanner1', [300,100], 'div-gpt-ad-1429861086244-0'",
			"'/183518426/uhk_webdt_tour_lst_babybanner2', [300,100], 'div-gpt-ad-1429861086244-1'",
			"'/183518426/uhk_webdt_tour_lst_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861086244-2'",
			"'/183518426/uhk_webdt_tour_lst_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861086244-3'",
			"'/183518426/uhk_webdt_tour_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861086244-4'",
			"'/183518426/uhk_webdt_tour_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861086244-5'",
			"'/183518426/uhk_webdt_tour_lst_superbanner1',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861086244-6'",
			"'/183518426/uhk_webdt_tour_lst_superbanner2',  [[970, 90], [970, 250], [970, 160], [728, 90]], 'div-gpt-ad-1429861086244-7'"
		),
	'/tour/detail'=>array(
			"'/183518426/uhk_webdt_tour_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861124571-0'",
			"'/183518426/uhk_webdt_tour_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861124571-1'",
			"'/183518426/uhk_webdt_tour_dtl_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429861124571-2'",
			"'/183518426/uhk_webdt_tour_dtl_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429861124571-3'",
			"'/183518426/uhk_webdt_tour_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861124571-4'",
			"'/183518426/uhk_webdt_tour_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861124571-5'",
			"'/183518426/uhk_webdt_tour_dtl_superbanner1',  [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429861124571-6'",
			"'/183518426/uhk_webdt_tour_dtl_superbanner2',  [[728, 90], [970, 90], [970, 160], [970, 250]], 'div-gpt-ad-1429861124571-7'"
		),
	'/topic/list'=>array(
			"'/183518426/uhk_webdt_topic_lst_babybanner1', [300,100], 'div-gpt-ad-1429861154740-0'",
			"'/183518426/uhk_webdt_topic_lst_babybanner2', [300,100], 'div-gpt-ad-1429861154740-1'",
			"'/183518426/uhk_webdt_topic_lst_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429861154740-2'",
			"'/183518426/uhk_webdt_topic_lst_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429861154740-3'",
			"'/183518426/uhk_webdt_topic_lst_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861154740-4'",
			"'/183518426/uhk_webdt_topic_lst_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861154740-5'",
			"'/183518426/uhk_webdt_topic_lst_superbanner1',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861154740-6'",
			"'/183518426/uhk_webdt_topic_lst_superbanner2',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861154740-7'"
		),
	'/topic/detail'=>array(
			"'/183518426/uhk_webdt_topic_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861175783-0'",
			"'/183518426/uhk_webdt_topic_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861175783-1'",
			"'/183518426/uhk_webdt_topic_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861175783-2'",
			"'/183518426/uhk_webdt_topic_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861175783-3'",
			"'/183518426/uhk_webdt_topic_dtl_skyscraper1',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861175783-4'",
			"'/183518426/uhk_webdt_topic_dtl_skyscraper2',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861175783-5'",
			"'/183518426/uhk_webdt_topic_dtl_superbanner1',  [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429861175783-6'",
			"'/183518426/uhk_webdt_topic_dtl_superbanner2',  [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429861175783-7'"
		),
	'/theme/detail'=>array(
			"'/183518426/uhk_webdt_topic_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861175783-0'",
			"'/183518426/uhk_webdt_topic_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861175783-1'",
			"'/183518426/uhk_webdt_topic_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861175783-2'",
			"'/183518426/uhk_webdt_topic_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861175783-3'",
			"'/183518426/uhk_webdt_topic_dtl_skyscraper1',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861175783-4'",
			"'/183518426/uhk_webdt_topic_dtl_skyscraper2',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861175783-5'",
			"'/183518426/uhk_webdt_topic_dtl_superbanner1',  [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429861175783-6'",
			"'/183518426/uhk_webdt_topic_dtl_superbanner2',  [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429861175783-7'"
		),
		
	'/theme/tag'=>array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
	),
	
	'/theme/cat/'=>array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
		
	'/theme/location'=>array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
	'/theme/index'=>array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
		
	'/theme/mall'=>array(
			"'/183518426/uhk_webdt_activity_dtl_babybanner1', [300,100], 'div-gpt-ad-1429860951715-0'",
			"'/183518426/uhk_webdt_activity_dtl_babybanner2', [300,100], 'div-gpt-ad-1429860951715-1'",
			"'/183518426/uhk_webdt_activity_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-2'",
			"'/183518426/uhk_webdt_activity_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429860951715-3'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-4'",
			"'/183518426/uhk_webdt_activity_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429860951715-5'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-6'",
			"'/183518426/uhk_webdt_activity_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429860951715-7'"
		),
	'/member/list'=> array(
			"'/183518426/uhk_webdt_member_lst_babybanner1', [300,100], 'div-gpt-ad-1429861241664-0'",
			"'/183518426/uhk_webdt_member_lst_babybanner2', [300,100], 'div-gpt-ad-1429861241664-1'",
			"'/183518426/uhk_webdt_member_lst_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861241664-2'",
			"'/183518426/uhk_webdt_member_lst_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861241664-3'",
			"'/183518426/uhk_webdt_member_lst_skyscraper1',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861241664-4'",
			"'/183518426/uhk_webdt_member_lst_skyscraper2',  [[160, 600], [120, 600]], 'div-gpt-ad-1429861241664-5'",
			"'/183518426/uhk_webdt_member_lst_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861241664-6'",
			"'/183518426/uhk_webdt_member_lst_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861241664-7'"
		),
	'/member/detail'=>array(
			"'/183518426/uhk_webdt_member_dtl_babybanner1', [300,100], 'div-gpt-ad-1429861266487-0'",
			"'/183518426/uhk_webdt_member_dtl_babybanner2', [300,100], 'div-gpt-ad-1429861266487-1'",
			"'/183518426/uhk_webdt_member_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429861266487-2'",
			"'/183518426/uhk_webdt_member_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429861266487-3'",
			"'/183518426/uhk_webdt_member_dtl_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861266487-4'",
			"'/183518426/uhk_webdt_member_dtl_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861266487-5'",
			"'/183518426/uhk_webdt_member_dtl_superbanner1',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861266487-6'",
			"'/183518426/uhk_webdt_member_dtl_superbanner2',  [[728, 90], [970, 90], [970, 250], [970, 160]], 'div-gpt-ad-1429861266487-7'"
		),
	'/m/activity/list'=>array(
			"'/183518426/uhk_webmb_activity_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280504117-0'",
			"'/183518426/uhk_webmb_activity_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280504117-1'",
			"'/183518426/uhk_webmb_activity_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280504117-2'",
		),
	'/m/activity/detail'=>array(
			"'/183518426/uhk_webmb_activity_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280529457-0'",
		),
	'/m/spot/list'=>array(
			"'/183518426/uhk_webmb_spot_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280553393-0'",
			"'/183518426/uhk_webmb_spot_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280553393-1'",
			"'/183518426/uhk_webmb_spot_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280553393-2'",
		),
	'/m/spot/detail'=>array(
			"'/183518426/uhk_webmb_spot_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280572744-0'",
		),
	'/m/tour/list'=> array(
			"'/183518426/uhk_webmb_tour_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280595555-0'",
			"'/183518426/uhk_webmb_tour_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280595555-1'",
			"'/183518426/uhk_webmb_tour_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280595555-2'",
		),
	'/m/tour/detail'=> array(
			"'/183518426/uhk_webmb_tour_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280613112-0'",
		),
	'/m/topic/list'=>array(
			"'/183518426/uhk_webmb_topic_lst_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1432280637257-0'",
			"'/183518426/uhk_webmb_topic_lst_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1432280637257-1'",
			"'/183518426/uhk_webmb_topic_lst_fixed3', [[320, 50], [300, 250]], 'div-gpt-ad-1432280637257-2'",
		),
	'/m/topic/detail'=> array(
			"'/183518426/uhk_webmb_topic_dtl_fixed1', [[300, 250], [320, 50]], 'div-gpt-ad-1432280656999-0'",
		),
	'/m/index'=>array(
			"'/183518426/uhk_webmb_homepage_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1438336721133-0'",
			"'/183518426/uhk_webmb_homepage_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1438336721133-1'",
		),
	'/m/search/index'=>array(
			"'/183518426/uhk_webmb_search_fixed1', [[320, 50], [300, 250]], 'div-gpt-ad-1438336751114-0'",
			"'/183518426/uhk_webmb_search_fixed2', [[320, 50], [300, 250]], 'div-gpt-ad-1438336751114-1'",
		),
	'/mall/index' => array(
			"'/183518426/mallking_webdt_homepage_babybanner1', [300, 100], 'div-gpt-ad-1429862818447-0'",
			"'/183518426/mallking_webdt_homepage_babybanner2', [300, 100], 'div-gpt-ad-1429862818447-1'",
			"'/183518426/mallking_webdt_homepage_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429862818447-2'",
			"'/183518426/mallking_webdt_homepage_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429862818447-3'",
			"'/183518426/mallking_webdt_homepage_skyscraper1', [[160, 600], [120, 600]], 'div-gpt-ad-1429862818447-4'",
			"'/183518426/mallking_webdt_homepage_skyscraper2', [[160, 600], [120, 600]], 'div-gpt-ad-1429862818447-5'",
			"'/183518426/mallking_webdt_homepage_superbanner1', [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429862818447-6'",
			"'/183518426/mallking_webdt_homepage_superbanner2', [[970, 250], [728, 90], [970, 90], [970, 160]], 'div-gpt-ad-1429862818447-7'",
		),
	'/mall/list' => array(
			"'/183518426/mallking_webdt_mall_lst_babybanner1', [300, 100], 'div-gpt-ad-1429862844154-0'",
			"'/183518426/mallking_webdt_mall_lst_babybanner2', [300, 100], 'div-gpt-ad-1429862844154-1'",
			"'/183518426/mallking_webdt_mall_lst_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429862844154-2'",
			"'/183518426/mallking_webdt_mall_lst_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429862844154-3'",
			"'/183518426/uhk_webdt_other_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-4'",
			"'/183518426/uhk_webdt_other_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-5'",
			"'/183518426/uhk_webdt_other_superbanner1',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861291959-6'"
		),
	'/mall/detail' => array(
			"'/183518426/mallking_webdt_mall_dtl_babybanner1', [300, 100], 'div-gpt-ad-1429862870755-0'",
			"'/183518426/mallking_webdt_mall_dtl_babybanner2', [300, 100], 'div-gpt-ad-1429862870755-1'",
			"'/183518426/mallking_webdt_mall_dtl_lrec1', [[300, 250], [300, 600]], 'div-gpt-ad-1429862870755-2'",
			"'/183518426/mallking_webdt_mall_dtl_lrec2', [[300, 250], [300, 600]], 'div-gpt-ad-1429862870755-3'",
			"'/183518426/mallking_webdt_mall_dtl_skyscraper1', [[120, 600], [160, 600]], 'div-gpt-ad-1429862870755-4'",
			"'/183518426/mallking_webdt_mall_dtl_skyscraper2', [[120, 600], [160, 600]], 'div-gpt-ad-1429862870755-5'",
			"'/183518426/mallking_webdt_mall_dtl_superbanner1', [[970, 160], [728, 90], [970, 90], [970, 250]], 'div-gpt-ad-1429862870755-6'",
			"'/183518426/mallking_webdt_mall_dtl_superbanner2', [[970, 160], [728, 90], [970, 90], [970, 250]], 'div-gpt-ad-1429862870755-7'",
		),
	'default' => array(
			"'/183518426/uhk_webdt_other_babybanner1', [300,100], 'div-gpt-ad-1429861291959-0'",
			"'/183518426/uhk_webdt_other_babybanner2', [300,100], 'div-gpt-ad-1429861291959-1'",
			"'/183518426/uhk_webdt_other_lrec1', [[300, 600], [300, 250]], 'div-gpt-ad-1429861291959-2'",
			"'/183518426/uhk_webdt_other_lrec2', [[300, 600], [300, 250]], 'div-gpt-ad-1429861291959-3'",
			"'/183518426/uhk_webdt_other_skyscraper1',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-4'",
			"'/183518426/uhk_webdt_other_skyscraper2',  [[120, 600], [160, 600]], 'div-gpt-ad-1429861291959-5'",
			"'/183518426/uhk_webdt_other_superbanner1',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861291959-6'",
			"'/183518426/uhk_webdt_other_superbanner2',  [[728, 90], [970, 160], [970, 90], [970, 250]], 'div-gpt-ad-1429861291959-7'"
		)
	);
						
$excludePhotoName = array(
			"ut/banner_spot.jpg",
			"ut/banner_eat.jpg",
			"ut/banner_relax.jpg",
			"ut/banner_shop.jpg",
			"ut/banner_hotel.jpg"
);

$uAdultWords= array(
	'找小姐',
	'叫小姐',
	'外送',
	'約妹',
	'外約',
	'送茶',
	'援交',
	'LINE：'
);