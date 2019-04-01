<?php
class Weather {
    /**
    * Loads default values for global vars
    */
    // function __construct() {
    function defines()
    {
        /**
        * Measurement System
        * 
        * s = US Standard
        * m = Metric
        * 
        * @var String 
        */
        $this->_measurementSystem = 'm';

        /**
        * Iconset path
        * 
        * @var String 
        */
        $this->_iconDirURL = 'icons';

        /**
        * Number of forecast days
        * 
        * @var Int 
        */
        $this->_numDays = 1;

        /**
        * Show day names
        * 
        * @var Int /String
        */
        $this->_dayNames = 0;

        /**
        * Temperature separator
        * 
        * @var String 
        */
        $this->_tempSeparator = '';

        /**
        * Available showTypes
        * 
        * @var array 
        */
        $this->_avlShowTypes = array('desc', 'icon', 'temp', 'forecast', 'curtime', 'sunrise', 'sunset', 'sunrise-sunset',
            'vis', 'wind', 'hum', 'dew', 'high', 'low', 'high-low'
            );

        /**
        * Use cache (NOT IMPLEMENTED YET!)
        * 
        * @var Boolean 
        */
        $this->_useCache = false;

        /**
        * Holds the data to be shown
        * 
        * @var array 
        */
        $this->_showType = array();
    } 

    /**
    * Initialize global vars
    * 
    * @param JParameter $params 
    */
    function __construct()
    {
        self::defines();

        $this->_measurementSystem = "m";

        $this->_iconDirURL = "icons/";
        $this->_numDays = 1;
        $this->_dayNames = "short";
        $this->_tempSeparator = "&deg;";
        $this->_link = "xoap";
        $this->_prod = "xoap";
        $this->_par = WEATHER_PARTNER_ID;
        $this->_key = WEATHER_LICENSE_KEY;

        $curArray = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
        $curShowType = $this->_avlShowTypes;
        for($i = 0;$i < count($curShowType);$i++) {
            if ($curArray[$i] == 1) {
                array_push($this->_showType, $curShowType[$i]);
            } 
        } 

        /*foreach ($this->_avlShowTypes as $curShowType)
		{
			if ($params->get($curShowType) == 1)
			{
				array_push($this->_showType, $curShowType);
			}
		}*/

        /*foreach ($this->_avlShowTypes as $curShowType)
		{
			if ($params->get($curShowType) == 1)
			{
				array_push($this->_showType, $curShowType);
			}
		}*/
    } 

    /**
    * 检查XML是否过期
    * 
    * @param string $file  
    * @return bool 
    */
    function xmlIsExpire($file)
    {
		if (time()-filemtime($file) > 60*60*2) // expire when last update is 2 hours ago
			$rt = true;
		else
			$rt = false;

		//if($rt) echo 'expire!<br>';

		return $rt;
	}
    /**
    * 检查XML是否含有错误信息
    * 
    * @param string $file  
    * @return bool 
    */
    function xmlDataError($file)
    {
		$xml = file_get_contents($file);
		if ( $xml == null) 
			$rt = true;
		else{
			$doc = new DOMDocument();
			@$doc->loadXML($xml);
			@$items = $doc->getElementsByTagName( "error" );
			//print_r($items->length);
			if ($items->length > 0 ) 
				$rt = true;
			else
				$rt = false;
		}
		//if($rt) echo 'DataError!<br>';
		return $rt;
	}
    /**
    * This function loads weather data for a specified cityCode
    * 
    * @param string $cityCode 
    * @return array 
    */
    function _loadWeatherData($cityCode)
    {
		if(!$cityCode || !preg_match('/^[a-z]+[0-9]+$/i',$cityCode)) return false;

        //debug($cityCode);
        $url = "http://xoap.weather.com/weather/local/" . $cityCode . "?cc=*&dayf=" . $this->_numDays . "&unit=" . $this->_measurementSystem . "&link=" . $this->_link . "&prod=" . $this->_prod . "&par=" . $this->_par . "&key=" . $this->_key; 
        // debug($url);
        // $file = "jweather.xml";
        $file = PATH_TRAVEL . "jweather".DS."xml".DS . $cityCode . ".xml";

        // file error then reload it
		if (!file_exists($file) || $this->xmlIsExpire($file) || $this->xmlDataError($file)) {
			//echo 'reload<br>';
			$data = file_get_contents($url);
            file_put_contents($file,$data);
        }
		else{
			$data = file_get_contents($file);
		}
		

		//file error return false
		if($this->xmlDataError($file)) return false;

        $xml_parser = xml_parser_create();

        /**
        * Initialize arrays
        */
        $vals = array();
        $index = array();

        xml_parse_into_struct($xml_parser, $data, $vals, $index);
        xml_parser_free($xml_parser);

        $params = array();
        $level = array();
        foreach ($vals as $xml_elem) {
            if ($xml_elem['type'] == 'open') {
                if (array_key_exists('attributes', $xml_elem)) {
                    $level[$xml_elem['level']] = array_shift($xml_elem['attributes']);
                } else {
                    $level[$xml_elem['level']] = $xml_elem['tag'];
                } 
            } 
            if ($xml_elem['type'] == 'complete') {
                $start_level = 1;
                $php_stmt = '$params';
                while ($start_level < $xml_elem['level']) {
                    $php_stmt .= '[$level[' . $start_level . ']]';
                    $start_level++;
                } 
                $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                eval($php_stmt);
            } 
        } 

        $this->_weatherData = $params;

        return true;
    } 

    /**
    * Returns retrieved weather data
    * 
    * @param string $cityCode 
    * @return array 
    */
    function getWeatherData($cityCode)
    {
        if(!self::_loadWeatherData($cityCode)) return false;

        $output = array();
        /**
        * Save number of days to be shown
        */
        $output['numDays'] = $this->_numDays;

        /**
        * Need we save the day names?
        */
        switch ($this->_dayNames) {
            /**
            * We use JText to be sure the dayname is translated
            * 
            * TODO - Fix strftime to show correctly in localized language
            */
            case 'short':
                for ($i = 0; $i < $this->_numDays; $i++) {
                    // $output['days'][$i] = ucfirst(JText::_(strftime("%a", strtotime("+".$i." days"))));
                    $output['days'][$i] = ucfirst(date("D", strtotime("+" . $i . " days")));
                } 
                break;
            case 'long':
                for ($i = 0; $i < $this->_numDays; $i++) {
                    // $output['days'][$i] = ucfirst(JText::_(strftime("%A", strtotime("+".$i." days"))));
                    $output['days'][$i] = ucfirst(date("l", strtotime("+" . $i . " days")));
                } 
                break;
        } 

        foreach ($this->_showType as $showType) {
            switch ($showType) {
                case 'desc':
                    $output['desc'] = $this->_weatherData["2.0"][$cityCode]['DNAM'];
                    break;
                case 'icon':
                    for ($i = 0; $i < $this->_numDays; $i++) {
                        if ($i == 0) {
                            /**
                            * Update icon numeric code
                            */
                            if (is_numeric($this->_weatherData["2.0"]['CC']['ICON']) && $this->_weatherData["2.0"]['CC']['ICON'] < 10) {
                                $this->_weatherData["2.0"]['CC']['ICON'] = '0' . $this->_weatherData["2.0"]['CC']['ICON'];
                            } 
                            $output['icon'][$i]['image'] = $this->_iconDirURL . $this->_weatherData["2.0"]['CC']['ICON'] . '.png';
                            $output['icon'][$i]['alt'] = $this->_weatherData["2.0"]['CC']['T'];
                        } else {
                            /**
                            * Update icon numeric code
                            */
                            if (is_numeric($this->_weatherData["2.0"]['DAYF'][$i]['d']['ICON']) && $this->_weatherData["2.0"]['DAYF'][$i]['d']['ICON'] < 10) {
                                $this->_weatherData["2.0"]['DAYF'][$i]['d']['ICON'] = '0' . $this->_weatherData["2.0"]['DAYF'][$i]['d']['ICON'];
                            } 
                            $output['icon'][$i]['image'] = $this->_iconDirURL . $this->_weatherData["2.0"]['DAYF'][$i]['d']['ICON'] . '.png';
                            $output['icon'][$i]['alt'] = $this->_weatherData["2.0"]['DAYF'][$i]['d']['T'];
                        } 
                    } 
                    break;
                case 'temp':
                    for ($i = 0; $i < $this->_numDays; $i++) {
                        if ($i == 0) {
                            $output['temp'][$i] = $this->_weatherData["2.0"]['CC']['TMP'] . $this->_tempSeparator . $this->_weatherData["2.0"]['HEAD']['UT'];
                        } else {
                            $output['temp'][$i] = $this->_weatherData["2.0"]['DAYF'][$i]['HI'] . $this->_tempSeparator . $this->_weatherData["2.0"]['HEAD']['UT'];
                        } 
                    } 
                    break;
                case 'forecast':
                    for ($i = 0; $i < $this->_numDays; $i++) {
                        if ($i == 0) {
                            $output['forecast'][$i] = $this->_weatherData["2.0"]['CC']['T'];
                        } else {
                            $output['forecast'][$i] = $this->_weatherData["2.0"]['DAYF'][$i]['d']['T'];
                        } 
                    } 
                    break;
                case 'sunrise':
                    $output['sunrise'] = $this->_weatherData["2.0"][$cityCode]['SUNR'];
                    break;
                case 'sunset':
                    $output['sunset'] = $this->_weatherData["2.0"][$cityCode]['SUNS'];
                    break;
                case 'sunrise-sunset':
                    $output['sunrise-sunset'] = $this->_weatherData["2.0"][$cityCode]['SUNR'] . '/' . $this->_weatherData["2.0"][$cityCode]['SUNS'];
                    break;
                case 'vis':
                    $output['vis'] = $this->_weatherData["2.0"]['CC']['VIS'] . $this->_weatherData["2.0"]['HEAD']['UD'];
                    break;
                case 'wind':
                    $output['wind'] = $this->_weatherData["2.0"]['CC']['WIND']['S'] . $this->_weatherData["2.0"]['HEAD']['US'];
                    break;
                case 'hum':
                    $output['hum'] = $this->_weatherData["2.0"]['CC']['HMID'];
                    break;
                case 'dew':
                    $output['dew'] = $this->_weatherData["2.0"]['CC']['DEWP'];
                    break;
                case 'high':
                    $output['high'] = $this->_weatherData["2.0"]['DAYF'][0]['HI'];
                    break;
                case 'low':
                    $output['low'] = $this->_weatherData["2.0"]['DAYF'][0]['LOW'];
                    break;
                case 'high-low':
                    $output['high-low'] = $this->_weatherData["2.0"]['DAYF'][0]['HI'] . '/' . $this->_weatherData["2.0"]['DAYF'][0]['LOW'];
                    break;
            } 
        } 

        return $output;
    } 
    /*
	*当day>0且城市为国内时可能取不到数据 
	*/
    function get_day_weather($WeatherID, $day = 0)
    {
        $this->_numDays = $day + 1;
        $data = $this->getWeatherData($WeatherID); 
		if(!$data || $data['icon'][$day]['image'] == 'icons/.png') 
			$rt = false;
		else{
			//debug($data);
			$rt = array("days" => $data['days'][$day],
				"image" => $data['icon'][$day]['image'],
				"temp" => $data['temp'][$day],
				"forecast" => $data['forecast'][$day]
				);
		}
        return $rt;
    } 
} 

/*
* testing
define('WEATHER_PARTNER_ID',1126770212);
define('WEATHER_LICENSE_KEY','ea1cd4bb2b0d44cd');

$helper = new Weather();
echo '<pre>';
print_r($helper->get_day_weather('CHXX0049'));
*/
?>