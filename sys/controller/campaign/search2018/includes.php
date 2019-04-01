<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

define('CAMPAIGN_NAME','search2018');
define('CAMPAIGN_CACHE','201808081400');
define('CAMPAIGN_LAYOUT','/campaign/'.CAMPAIGN_NAME.'/layout');

define('CAMPAIGN_META_IMAGE','');
define('CAMPAIGN_META_TITLE','體驗港生活全新「好去處搜尋器」 贏取主題公園全年入場證！');
define('CAMPAIGN_META_DESCRIPTION','');
define('CAMPAIGN_META_KEYWORD','');

define('CAMPAIGN_ALERT_MESSAGE','我哋已經收到您嘅參加表格啦，感謝參與！');
define('CAMPAIGN_FB_POST_LINK','https://www.facebook.com/hk.ulifestyle.com.hk/photos/p.2089269687784045/2089269687784045/?type=3');

define('CAMPAIGN_DAILY_Q1','*於搜尋結果中，您最想參與的夏季活動是…?');
define('CAMPAIGN_DAILY_Q2','*請分享您對港生活「好去處搜尋器」的意見及建議。');

require_once(dirname(__FILE__).'/campaignAnswer.class.php');


function getMainTerms(){
	$main_terms = array(
		'每位參加者均須讚好 <a href="https://www.facebook.com/hk.ulifestyle.com.hk/" target="_blank">HK港生活</a> Facebook專頁，於Facebook 讚好(like)及公開分享(share to public) <a href="'.CAMPAIGN_FB_POST_LINK.'" target="_blank">此活動的帖子(post)</a>，並於活動帖子下留言「放假去邊度？用港生活好去處搜尋器一Search就搵到！」及標籤(tag) 2位朋友。',
		'每位參加者均須登記成為<a href="https://search.ulifestyle.com.hk/registration/personal?from=uhk_search" target="_blank">港生活會員</a>，並在已登入帳號之情況下，按指示搜尋及細閱文章，揀選一個最想參與的夏季活動，回答所有問題及填妥所須之個人資料，並提交報名表格。',
		'如未有完成以上步驟，其參加及得獎資格將被取消。',
		'整個活動期由2018 年 8 月 8 日起至 2018 年 8 月 20 日晚上11時59分截止，以U Lifestyle及港生活伺服器接收為準。',
		'U Lifestyle及港生活團隊將根據參加者提交「問題1」之答案質素選出最有心思之8位得獎者，每名得獎者限得香港「海洋公園智紛全年入場證金卡」禮券乙張，可於香港海洋公園兌換「智紛全年入場證金卡」乙張，兌換有效期不少於6個月，詳情請留意禮券上之兌換條件及細則。',
		'本活動由港生活主辦，香港海洋公園並非本活動贊助商，亦未有以任何形式參與本活動。',
		'所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾時無效。',
		'凡參加遊戲，會員均須提供正確個人資料，如發現未有提供資料、資料有誤、重複參加、使用虛假帳號、盜用第三者個人資料或內容、抄襲、使用不當用語、虛構、挑釁或誤導或以任何方式擾亂或操控遊戲，其參加資格將被取消，並不會作出通知、解釋或補償。',
		'任何因電腦、通訊工具、網路等技術問題而導致參加者所遞交的文字有遲延、遺失、錯誤、無法辨識等情況，U Lifestyle及港生活概不負責。',
		'得獎名單將於 2018 年 8 月 28 日後於港生活網站「會員專享」區內公佈，得獎者將收到電郵通知有關領獎事宜；對未能獲獎者將不會作個別通知。',
		'得獎者需確定所提供的個人資料無誤，因未能聯絡或資料出錯令得獎者未能獲知得獎或領取獎品，U Lifestyle及港生活概不負責，得獎者將不獲補發獎品；獎品名額將作廢，其他參加者不能補上。',
		'是次活動之所有得獎者均須於指定領獎期內，持獎品領取信，預約及親身前往香港經濟日報集團辦公室領取獎品，不設代領、快遞及郵寄，逾期作廢。',
		'得獎者於領取獎品時將須要拍照，U Lifestyle及港生活有權使用其照片或肖像作日後宣傳或廣告之用，而不作另行通知。',
		'所有獎品不可退回、轉讓或兌換現金。',
		'有關獎品之服務、品質及一切服務內容皆以服務供應商公佈為準，U Lifestyle及港生活概不負責。',
		'U Lifestyle及港生活保留對此推廣活動及獎品之條款及細則的修改權利，任何改動將於港生活網站公開發佈，不另作通知。',
		'凡參加此活動即表示同意接受U Lifestyle及港生活的<a href="https://hk.ulifestyle.com.hk/aboutus" target="_blank">私隱條款</a>、<a href="https://hk.ulifestyle.com.hk/aboutus" target="_blank">服務條款</a>及此活動之一切條款及細則。',
		'香港經濟日報集團員工及家屬不得參與是次活動，以示公允。',
		'如有任何爭議，U Lifestyle及港生活保留最終決定權。'
	);
	return $main_terms;
}
?>