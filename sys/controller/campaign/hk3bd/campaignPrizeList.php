<?php
//defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
$prize_list = getPrizeList();

define('PRIZE_NUMBER',2);


define('CAMPAIGN_PERIOD','prize'.sprintf("%02d", PRIZE_NUMBER));
define('CAMPAIGN_NEXT_PRIZE','prize'.sprintf("%02d",(PRIZE_NUMBER+1)));
define('CAMPAIGN_CACHE', '201709261745');

define('CAMPAIGN_META_IMAGE',UAPP_HOST.UAPP_BASE_URL.'/sys/view/campaign/hk3bd/images/prize-img/general-og.png');
define('CAMPAIGN_META_IMAGE_DAILY',UAPP_HOST.UAPP_BASE_URL.'/sys/view/campaign/hk3bd/images/prize-img/'.$prize_list[CAMPAIGN_PERIOD]['image_dir'].'/day'.$prize_list[CAMPAIGN_PERIOD]['id'].'-og.png');
define('CAMPAIGN_META_TITLE','港玩港食港生活　同您玩轉三周年！');
define('CAMPAIGN_META_DESCRIPTION','港生活3歲喇！為答謝各位忠實Fans以及會員過去3年來嘅厚愛同支持，港生活一於與眾同樂，於未來四星期入面強勢送出多份超爆人氣精選獎品回饋大家！一齊全情投入港生活3周年，超級巨獎Sss一齊FUN！');
define('CAMPAIGN_META_KEYWORD','');

if(CAMPAIGN_PERIOD=='prize11')
	define('CAMPAIGN_ALERT_MESSAGE','我哋已經收到您嘅參加表格啦，感謝參與！得獎名單將於 2017 年 11 月 1 日後於港生活網站內公佈。');
else
	define('CAMPAIGN_ALERT_MESSAGE','我哋已經收到您嘅參加表格啦，感謝參與！記得密切留意下一輪獎品公佈！得獎名單將於 2017 年 11 月 1 日後於港生活網站內公佈。');

/*********************************
Prize List status 
active=1, inactive=0, expired=-1
*********************************/

function getPrizeList(){
$prize_list = array(
					'prize01'=>array(
						'period'=>'prize01',
						'status'=>'0',
						'prize_id'=>'d1',
						'id'=>'1',
						'prize_title'=>'SONY PlayStation®4 <br>主機連 VR 套裝',
						'_title'=>'PlayStation®4 連PlayStation®VR攝影機同捆裝',
						'title'=>'PlayStation®4 連<br>PlayStation®VR攝影機同捆裝', 
						'prize_value'=>'價值HK$5,368 | 名額2個',
						'intro_description'=>'PlayStation®4配備市場上最快的處理器和記憶體，成為遊戲的最佳平台，讓您享受各式各樣充滿創新意念的精彩遊戲。配合PlayStation®VR，逼真的3D空間將全方位360度圍繞玩家，提供充滿臨場感的影像及猶如身歷其境的打機新體驗。',
						'question_1'=>'假如PS4連VR到手，第一時間最想玩嘅遊戲係…？',
						'question_2'=>'港生活3歲喇！講低一樣最想我地改進嘅地方，等我地可以做得更好吖！',
						'terms'=>array(
						'今期獎品活動期由2017年9月25日至9月27日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出2位最有創意之得獎者，每位得獎者限得SONY PlayStation®4 主機 (極致黑) 500GB壹部及PlayStation®VR PlayStation®Camera 同捆裝壹份 (價值$5,368)，當中包括PlayStation®4 主機(極致黑) 500GB、DUALSHOCK®4 無線控制器、VR頭戴裝置、訊號處理器及VR頭戴裝置連接線各壹件。',
						'獎品不設顏色選擇，不得更換、退回、轉讓或兌換現金。',
						'獎品之保養由Sony Interactive Entertainment Hong Kong Ltd.提供，詳情請與Sony Interactive Entertainment Hong Kong Ltd.查詢。',
						'獎品不設送貨服務。',
						'有關獎品之服務、品質及一切服務內容皆以Sony Interactive Entertainment Hong Kong Ltd.公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'playstation01',
						'fb_post_link'=>'https://www.facebook.com/hk.ulifestyle.com.hk/videos/vb.835266909851002/475660966141187/?type=3&theater'
					),
					'prize02'=>array(
						'period'=>'prize02',
						'status'=>'0',
						'prize_id'=>'d2', 
						'id'=>'2',
						'prize_title'=>'香港文華東方酒店<br>快船廊雙人自助晚餐',
						'_title'=>'香港文華東方酒店快船廊雙人自助晚餐',
						'title'=>'香港文華東方酒店<br>快船廊雙人自助晚餐',
						'prize_value'=>'價值HK$1,381.6 | 名額3個',
						'hints'=>'減肥？食埋今餐先啦…',
						'intro_description'=>'快船廊自助晚餐加入了即席炮製的龍蝦意粉，伴隨多款名貴海鮮、壽司、沙律、各式熱盤以及肉車。廚師團隊更會為賓客端出即點即開生蠔，讓賓客能安在位置上也可享受極致新鮮的味道。此外，更有逾四十五款甜點，如童話般美麗奪目的甜品花園令人忍不住要「相機食先」！',
						'question_1'=>'自助餐咁多好嘢食～您又最鍾意邊一個國家嘅美食呢？請分享原因。',
						'question_2'=>'假期時通常同咩人出去玩呢? 試講出3個計劃假期活動時所遇到嘅困難。',
						'terms'=>array(
						'今期獎品活動期由2017年9月27至9月29日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出3位最有創意之得獎者，每位得獎者限得香港文華東方酒店快船廊雙人自助晚餐連加一壹份 (價值$1381.6)。',
						'自助餐禮券有效期至2018年4月30日止，除2017年11月23日、12月25-26日、2018年1月1日及2018年2月25日外，詳情請留意自助餐禮券上之條款及細則，逾期無效，敬請提前預約。',
						'自助餐禮券不得更換、退回、轉讓或兌換現金。',
						'有關自助晚餐之服務、品質及一切服務內容皆以香港文華東方酒店公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'dinnerbuffet02',
						'fb_post_link'=>'https://www.facebook.com/hk.ulifestyle.com.hk/videos/476055026101781/'
					),
					'prize03'=>array(
						'period'=>'prize03',
						'status'=>'0',
						'prize_id'=>'d3', 
						'id'=>'3',
						'prize_title'=>'Rasonic<br>Mini Cube洗碗碟機',
						'_title'=>'Rasonic Mini Cube洗碗碟機',
						'title'=>'Rasonic mini cube<br>洗碗碟機',
						'prize_value'=>'價值HK$2,680 | 名額4個',
						'hints'=>'有咗佢，就唔洗驚有主婦手啦！',
						'intro_description'=>'Rasonic免安裝洗碗碟機，機身迷你，適合1至4人家庭使用。特設易拆式水箱，方便使用。洗碗碟機設有多段清洗功能，備有多段清洗時間（35、90 及120分鐘）、沖洗及烘乾組合。以最高約70°C高溫清洗，有助分解油脂及污垢。',
						'question_1'=>'請分享一樣你最討厭做嘅家務，並說明原因。',
						'question_2'=>'您每月投放最多金錢嘅消閒類別係...？(例如：飲食、購物、玩樂)而您又會係基於咩原因而選擇該項消費呢?',
						'terms'=>array(
						'今期獎品活動期由2017年9月29日至10月3日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出4位最有創意之得獎者，每位得獎者限得Rasonic Mini Cube洗碗碟機壹部 (價值$2,680)。',
						'Rasonic Mini Cube洗碗碟機不設顏色選擇，不得更換、退回、轉讓或兌換現金。',
						'Rasonic Mini Cube洗碗碟機之送貨服務由信興電工工程有限公司提供，詳情將於得獎電郵通知各得獎者。',
						'Rasonic Mini Cube洗碗碟機之送貨服務不包括離島、偏遠地區如沒有交通直接到達的地方，及禁區。',
						'Rasonic Mini Cube洗碗碟機之保養由信興電工工程有限公司提供，請保留送貨單及填寫盒內附帶之保修咭以享用貨品之一年保養，詳情請與信興電工工程有限公司查詢。',
						'有關Rasonic Mini Cube洗碗碟機之服務、品質及一切服務內容皆以信興電工工程有限公司公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'minicube03',
						'fb_post_link'=>''
					),
					'prize04'=>array(
						'period'=>'prize04',
						'status'=>'0',
						'prize_id'=>'d4',
						'id'=>'4', 
						'prize_title'=>'美圖<br>M8手機',
						'_title'=>'美圖M8手機',
						'title'=>'美圖<br>M8手機',
						'prize_value'=>'價值HK$3,799 | 名額2個',
						'hints'=>'一秒變靚亦得，得咗！',
						'intro_description'=>'美圖M8是一部具備人工智能拍照功能的手機，配置SONY IMX362前置雙像素自拍鏡頭。 美圖人工智能除可準確識別性別、年齡和五官外，更可多維度識別半身、輪廓和自拍時的臉部光線等，並深度分析膚色、頭髮等資訊，令自拍技術顯得額外專業和出色。',
						'question_1'=>'美圖m8手機具備多種拍照功能，請分享其中您最想試用的一種功能及原因。',
						'question_2'=>'作為我哋嘅用戶，您希望港生活網頁可以新增哪一項功能呢？請分享原因。',
						'terms'=>array(
						'今期獎品活動期由2017年10月3日至10月6日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出2位最有創意之得獎者，每位得獎者限得美圖M8標準版手機壹部 (價值$3,799)。',
						'獎品不設顏色選擇，不得更換、退回、轉讓或兌換現金。',
						'獎品之保養由Meitu Inc.提供，激活手機後可享用貨品之一年保養詳情請與Meitu Inc.查詢。',
						'有關獎品之服務、品質及一切服務內容皆以Meitu Inc.公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'m8mobile04',
						'fb_post_link'=>''
					),
					'prize05'=>array(
						'period'=>'prize05',
						'status'=>'0',
						'prize_id'=>'d5',
						'id'=>'5', 
						'prize_title'=>'amika<br>吹整造型套裝',
						'_title'=>'amika吹整造型套裝',
						'title'=>'amika<br>吹整造型套裝',
						'prize_value'=>'價值HK$5,240 | 名額4個',
						'hints'=>'Set頭唔洗去Salon！',
						'intro_description'=>'套裝包括數碼鈦金屬造型器、迷你鈦金屬造型器及迷你風筒各壹個。amika™造型器擁有100%的鈦金屬板及高效能的MCH陶瓷熱能技術。鈦金屬板輕盈耐用，而且夾面極度平滑，確保滑動時不會纏住頭髮，固深受專業造型師歡迎。而迷你風筒特設兩個風嘴，先進的離子風筒可以減省乾髮時間。',
						'question_1'=>'平時最令您苦惱嘅頭髮問題係…? 而Amika吹整造型套裝又可以如何為您解決問題呢?',
						'question_2'=>'您最鐘意睇港生活網站入面邊一類內容？您又希望我哋未來可以為您帶來咩類型嘅內容呢？',
						'terms'=>array(
						'今期獎品活動期由2017年10月6日至10月9日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出4位最有創意之得獎者，每位得獎者限得amika吹整造型套裝壹份(價格$5,240)，套裝包括數碼鈦金屬造型器、迷你鈦金屬造型器及迷你風筒各壹個。',
						'amika吹整造型套裝不設顏色選擇，不得更換、退回、轉讓或兌換現金。',
						'獎品之保養由amika提供，請保留由U Lifestyle發出之得獎信以享用貨品之一年保養，詳情請與Beauty Express Ltd.查詢。',
						'有關amika吹整造型套裝之服務、品質及一切服務內容皆以Beauty Express Ltd.公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'beautyset05',
						'fb_post_link'=>''
					),
					'prize06'=>array(
						'period'=>'prize06',
						'status'=>'0',
						'prize_id'=>'d6', 
						'id'=>'6',
						'prize_title'=>'香港JW萬豪酒店<br>JW 咖啡室雙人下午茶自助餐',
						'_title'=>'香港JW萬豪酒店JW 咖啡室雙人下午茶自助餐',
						'title'=>'香港JW萬豪酒店<br>JW 咖啡室雙人下午茶自助餐',
						'prize_value'=>'價值HK$699.6 | 名額5個',
						'hints'=>'又到三點三！',
						'intro_description'=>'JW 咖啡室週末下午茶自助餐讓您於11個互動烹調站飽嚐滋味豐盛的美食，大廚們挑選各式上佳食材，精心呈獻令人回味無窮的環球美食、新鮮炮製的點心、特色粵菜、多達18款新鮮沙律、優質刺身壽司等，更有數之不盡的自家製甜品包括葡式蛋撻、杯子蛋糕以及奧地利著名甜點林茲撻。',
						'question_1'=>'下午茶自助餐入面，邊一類食品最吸引您呢？請分享原因。',
						'question_2'=>'您每月投放最多金錢去購買邊類型嘅產品呢？ (例如：衣飾、鞋履、電子產品) 通常又會以哪種途徑去接收購物優惠情報呢？',
						'terms'=>array(
						'今期獎品活動期由2017年10月9日至10月11日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出5位最有創意之得獎者，每位得獎者限得香港JW萬豪酒店JW 咖啡室雙人下午茶自助餐連加一壹份 (價值$699.6)。',
						'下午茶自助餐只適用於星期六及星期日下午3時15分至5時，自助餐禮券有效期至2018年4月2日止，除2017年12月24至26日、12月31日及2018年2月16至17日外，詳情請留意自助餐禮券上之條款及細則，逾期無效，敬請提前預約。',
						'自助餐禮券不得更換、退回、轉讓或兌換現金。',
						'有關下午茶自助餐之服務、品質及一切服務內容皆以香港JW萬豪酒店公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'jwcafetea06',
						'fb_post_link'=>''
					),
					'prize07'=>array(
						'period'=>'prize07',
						'status'=>'0',
						'prize_id'=>'d7', 
						'id'=>'7',
						'prize_title'=>'RED MR. 紅人派對<br>HK$1000禮劵',
						'_title'=>'RED MR. 紅人派對HK$1000禮劵',
						'title'=>'RED MR. 紅人派對<br>HK$1000禮劵',
						'prize_value'=>'價值HK$1,000 | 名額5個',
						'hints'=>'一開心唱飲歌 不開心唱飲歌～♫',
						'intro_description'=>'RedMR紅人派對宗旨是「改寫流行」，為香港KTV的發展定下全新詮釋，走在潮流最前，帶來多項無可比擬的獨家新體驗。除了提供高質素「玩樂」設施，更提供多元餐飲享受，適合三五知己共聚或是大夥兒狂歡派對。',
						'question_1'=>'請推介近年一首本地歌曲，並分享你認為值得推介嘅原因。',
						'question_2'=>'計劃假期活動時您會如何排列以下因素嘅重要性？ (日期、地區、活動類型、消費、用戶評價) 還有甚麼因素您會考慮嗎？',
						'terms'=>array(
						'今期獎品活動期由2017年10月11日至10月13日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出5位最有創意之得獎者，每位得獎者限得Red MR紅人派對 $1000現金券壹份 (每份含$500現金券兩張)。',
						'現金券只適用於星期日至星期四，有效期至2018年3月31日止，除2017年12月20日至31日、公眾假期及公眾假期前夕外，詳情請留意現金券上之條款及細則，逾期無效，敬請提前預約。',
						'現金券不得更換、退回、轉讓或兌換現金。',
						'有關獎品之服務、品質及一切服務內容皆以Red MR紅人派對及bma Catering Management Limited公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'redmrparty07',
						'fb_post_link'=>''
					),
					'prize08'=>array(
						'period'=>'prize08',
						'status'=>'0',
						'prize_id'=>'d8', 
						'id'=>'8',
						'prize_title'=>'CASIO<br> EX-FR100L「長腿自拍相機」',
						'_title'=>'CASIO EX-FR100L「長腿自拍相機」',
						'title'=>'CASIO<br> EX-FR100L「長腿自拍相機」',
						'prize_value'=>'價值HK$3,480 | 名額3個',
						'hints'=>'人人期望可達到 42吋長腿冇難度！',
						'intro_description'=>'全新CASIO EX-FR100L採用創新的「分體式設計」，鏡頭及3吋92萬像素輕觸式LCD顯示屏可以一分為二，靈活性高，可於各種拍攝場景或角度進行自拍，包括特寫和全身自拍等。EX-FR100L配備最新的「美顏藝術Make-up Art」功能，可調節肌膚膚色及平滑度外，全新功能更可同時提升色彩飽和度及背景對比度。同時亦配備FR100L 16mm超廣角鏡頭，方便與一大班好友自拍，是外遊首選！',
						'question_1'=>'最想帶CASIO EX-FR100L相機去邊度影靚相？請分享原因。',
						'question_2'=>'除咗港生活，您仲有睇過邊一啲介紹香港食買玩嘅網頁呢？試舉出一個佢哋網頁做得比我們好嘅地方。',
						'terms'=>array(
						'今期獎品活動期由2017年10月13日至10月16日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出3位最有創意之得獎者，每位得獎者限得CASIO EX-FR100L長腿自拍相機壹部 (價值$3,480)。',
						'CASIO EX-FR100L相機不設顏色選擇，不得更換、退回、轉讓或兌換現金。',
						'CASIO EX-FR100L之保養由捷成消費品有限公司提供，請保留單據及填寫盒內附帶之保修咭以享用貨品之一年保養，詳情請與捷成消費品有限公司查詢。',
						'有關CASIO EX-FR100L 長腿自拍相機之服務、品質及一切服務內容皆以Jebsen Consumer Products Co.Ltd公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'casiomobile08',
						'fb_post_link'=>''
					),
					'prize09'=>array(
						'period'=>'prize09',
						'status'=>'0',
						'prize_id'=>'d9', 
						'id'=>'9',
						'prize_title'=>'港島香格里拉酒店<br>Cafe TOO雙人自助晚餐',
						'_title'=>'港島香格里拉酒店Cafe TOO雙人自助晚餐',
						'title'=>'港島香格里拉酒店<br>Cafe TOO雙人自助晚餐',
						'prize_value'=>'價值HK$1,535.6 | 名額3個',
						'hints'=>'有食唔食，罪大惡極！',
						'intro_description'=>'cafe TOO自助餐的精選代表菜式包括鐵板燒和牛、清蒸鮑魚、帶子海膽刺身；舖滿海鮮櫃檯的新鮮波士頓龍蝦、大蝦、阿拉斯加蟹腳、海螺與青口，以及由印度大廚主理的天多尼燒大蝦、薄餅及咖哩等地道印度佳餚，更有多款精緻糕餅、即製梳乎里、傳統中式甜點以及十多款不同口味的雪糕在美食舞台上大放異彩。',
						'question_1'=>'最想同邊個去歎港島香格里拉酒店嘅Dinner Buffet呢？請分享原因。',
						'question_2'=>'您最常喺香港邊一區進行消費活動呢？ (例如：灣仔區、屯門區) 請推介一間位於該區嘅食店/消閒玩樂活動。',
						'terms'=>array(
						'今期獎品活動期由2017年10月16日至10月18日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出3位最有創意之得獎者，每位得獎者限得港島香格里拉大酒店 café TOO 雙人自助晚餐連加一壹份 (價值$1535.6)。',
						'自助餐禮券有效期至2018年3月31日止，詳情請留意自助餐禮券上之條款及細則，逾期無效，敬請提前預約。',
						'自助餐禮券不得更換、退回、轉讓或兌換現金。',
						'有關自助晚餐之服務、品質及一切服務內容皆以港島香格里拉大酒店公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'cafetoobuffet09',
						'fb_post_link'=>''
					),
					'prize10'=>array(
						'period'=>'prize10',
						'status'=>'0',
						'prize_id'=>'d10', 
						'id'=>'10',
						'prize_title'=>'香港黃金海岸酒店海景豪華雙人房連海岸扒房雙人套餐',
						'_title'=>'香港黃金海岸酒店海景豪華雙人房連海岸扒房雙人套餐',
						'title'=>'香港黃金海岸酒店海景豪華雙人房<br>連海岸扒房雙人套餐',
						'prize_value'=>'價值HK$4,813.6 | 名額1個',
						'intro_description'=>'遠離繁囂鬧市，入住香港黃金海岸酒店高級海景客房一晚，配合優雅環境，放鬆身心休閒玩樂，留港亦可過一個悠閑假期。同時亦可與親朋好友一邊欣賞醉人景致，一邊品嚐優質的海岸扒房推出的全新菜單。另外，逢星期二至日每晚 9 時開始更設現場樂隊演奏，份外寫意！',
						'hints'=>'唔洗飛澳洲！留港歎返晚～',
						'question_1'=>'您有看過港生活的「香港周圍遊」嗎？您又有甚麼可以放鬆身心的週末一天遊行程建議呢？',
						'question_2'=>'您希望港生活可以舉辦哪類型的會員活動？請分享原因。',
						'terms'=>array(
						'今期獎品活動期由2017年10月18日至10月20日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出1位最有創意之得獎者，每位得獎者限得香港黃金海岸酒店海景豪華雙人房連海岸扒房雙人套餐連加一壹份 (價值$4,813.6)。',
						'香港黃金海岸酒店海景豪華雙人房連海岸扒房餐飲劵有效期至2017年12月14日止，除2017年11月10日至12日、公眾假期前夕、公眾假期、節日及酒店特備節目外，詳情請留意禮券上之條款及細則，逾期無效，敬請提前預約。',
						'登記入住香港黃金海岸酒店之住客須年滿18歲。',
						'酒店住宿及海岸扒房餐飲劵均不得更換、退回、轉讓或兌換現金。',
						'有關香港黃金海岸酒店海景豪華雙人房及海岸扒房雙人套餐之服務、品質及一切服務內容皆以黃金海岸酒店公佈為準，U Lifestyle及港生活概不負責。'
						),
						'image_dir'=>'hotelroom10',
						'fb_post_link'=>''
					),
					'prize11'=>array(
						'period'=>'prize11',
						'status'=>'0',
						'prize_id'=>'d11',
						'id'=>'11',
						'prize_title'=>'Switch<br>組合套裝',
						'_title'=>'Switch主機連Splatoon 2遊戲組合套裝(包括Switch主機(電光藍/紅)壹部、《漆彈大作戰2 Splatoon 2》遊戲一隻及Joy-Con外置電池配件壹套)',
						'title'=>'Switch主機連<br>Splatoon 2遊戲組合套裝',
						'prize_value'=>'價值HK$3,019 | 名額3個',
						'hints'=>'無論喺街、喺屋企、喺office，有佢一定冇悶場！',
						'intro_description'=>'「Nintendo Switch」是家庭用遊戲機，不單止可連接電視來玩，也可配合遊玩方式，自由選擇3種模式。當中包括TV模式、桌上模式及手提模式。',
						'question_1'=>'最想同邊個玩依部超人氣遊戲機呢？請分享原因。',
						'question_2'=>'一想到港生活您會諗到啲咩？請用三個詞語形容我哋吖。',
						'terms'=>array(
						'今期獎品活動期由2017年10月20日至10月25日上午11時59分截止，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
						'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出3位最有創意之得獎者，每位得獎者限得Nintendo Switch組合套裝乙份，包括Switch主機(電光藍/紅)壹部、《漆彈大作戰2 Splatoon 2》遊戲一隻及Joy-Con 外置電池配件 (總值$3,019)。',
						'Nintendo Switch主機不設顏色選擇，不得更換、退回、轉讓或兌換現金。',
						'Nintendo Switch主機之保養由任天堂（香港）有限公司提供，詳情請與任天堂（香港）有限公司查詢。',
						'Nintendo Switch主機之服務、品質及一切服務內容皆以任天堂（香港）有限公司公佈為準，U Lifestyle及港生活概不負責。',
						),
						'image_dir'=>'switchgameset11',
						'fb_post_link'=>''
					)
				);
	return $prize_list;
}

function getMainTerms(){
	$main_terms = array(
		'每位參加者均須讚好<a href="https://www.facebook.com/hk.ulifestyle.com.hk/" target="_blank"><b>港生活 Facebook專頁</b></a>，並於Facebook 讚好(like)及公開分享(share to public) 此活動的帖子(Post)，並於活動帖子下留言「港生活同你玩轉3周年！勁多巨獎等你攞！」及標籤(tag) 2位朋友。',
		'每位參加者均須登記成為<a href="https://search.ulifestyle.com.hk/registration/personal?from=uhk&service=%2Faccount.html%3Faction%3Dregister" target="_blank"><b>港生活會員</b></a>，並在已登入帳號之情況下填妥報名表格。',
		'如未有完成以上步驟，其得獎資格將被取消。',
		'整個活動期由即日起至 2017 年 10 月 25 日上午11時59分截止，以U Lifestyle及港生活伺服器接收為準。',
		'U Lifestyle及港生活團隊將根據參加者提交之內容質素選出最有創意之得獎者，得獎名額及獎品以每期獎品更新為準，所有答案之提交時間以U Lifestyle及港生活伺服器接收為準，逾期無效。',
		'凡參加遊戲，會員均須提供正確個人資料，如發現未有提供資料、資料有誤、重複參加、使用虛假帳號、盜用第三者個人資料或內容抄襲、使用不當用語、虛構、挑釁或誤導或以任何方式擾亂或操控遊戲，其參加資格將被取消，並不會作出通知、解釋或補償。',
		'獎品項目及問題將於活動期內不定時更新，詳情以港生活公佈為準。',
		'每期獎品均接受會員參加壹次，每名會員於活動期內之參加總次數為有機會獲得獎品項目之總數。',
		'任何因電腦、通訊工具、網路等技術問題而導致參加者所遞交的文字有延遲、遺失、錯誤、無法辨識等情況，U Lifestyle及港生活概不負責。',
		'得獎名單將於 2017年11月1日後於港生活網站內公佈，得獎者將收到電郵通知有關領獎事宜；對未能獲獎者將不會作個別通知。',
		'得獎者需確定所提供的個人資料無誤，因未能聯絡或資料出錯令得獎者未能領取獎品，U Lifestyle及港生活概不負責，得獎者將不獲補發獎品；獎品名額將作廢，其他參加者不能補上。 ',
		'是次活動之所有得獎者均須於指定領獎期內，持獎品領取信，預約及親身前往香港經濟日報集團辦公室領取獎品，不設代領、快遞及郵寄，逾期作廢。',
		'得獎者於領取獎品時將須要拍照，U Lifestyle及港生活有權使用其照片或肖像作日後宣傳或廣告之用，而不作另行通知。',
		'領獎時，得獎者必須出示身份證或其他身份證明文件以核對個人資料及更新會員資料庫。',
		'所有獎品款式或顏色將由U Lifestyle及港生活分配，得獎者不得更換或指定款式及顏色，亦不可退回、轉讓或兌換現金。',
		'所有獎品、門票或現金券之有效期，將於更新每期獎品時於條款及細則公佈，得獎者不得更換、退回、轉讓或兌換現金。',
		'部分獎品不設保用，詳情請參閱每期獎品公佈之條款及細則，得獎者不得更換、退回、轉讓或兌換現金。',
		'有關贊助獎品之服務、品質及一切服務內容皆以贊助商公佈為準，U Lifestyle及港生活概不負責。',
		'U Lifestyle及港生活保留對此推廣活動及獎品之條款及細則的修改權利，任何改動將於HK港生活網站公開發佈，不另作通知。',
		'凡參加此活動即表示同意接受U Lifestyle及港生活的私隱條款、服務條款及此活動之一切條款及細則。',
		'香港經濟日報集團員工及家屬不得參與是次活動，以示公允。',
		'如有任何爭議，U Lifestyle保留最終決定權。',
		'如有查詢，請電郵至 <a href="mailto:info.hk@ulifestyle.com.hk"><b>info.hk@ulifestyle.com.hk</b></a>。'
	);
	return $main_terms;
}
?>

