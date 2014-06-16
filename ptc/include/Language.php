<?php 

class Language
{
	
	/**
	 * Singleton: holds instance of this object
	 * 
	 * @var Language
	 */
	private static $_instance; 
	
	/**
	 * Holds the current language
	 * 
	 * @var string
	 */
	private static $_language;
	
	/**
	 * Holds the default language
	 * 
	 * @var string
	 */
	private static $_defaultlanguage = "en";
	
	/** 
	 * Array with all translations
	 * 
	 * @var array
	 */
	private static $_translations = array();
	
	/**
	 * Use file_get_contents
	 * 
	 * @var GET
	 */
	const METHOD_GET = "get";
	
	/**
	 * Use CURL
	 * 
	 * @var POST
	 */
	const METHOD_POST = "post";
	
	/**
	 * Method to use to get the translation with google
	 * 
	 * GET: this uses the file_get_contents
	 * POST: this uses CURL
	 * 
	 * @var string
	 */
	private static $_method = self::METHOD_POST;
	
	/**
	 * Enable/Disable auto google translation
	 * 
	 * @var bool
	 */
	private static $_auto = FALSE;
	
	/**
	 * Enable/Disable auto google translation
	 * 
	 * @var bool
	 */
	private static $_page = FALSE;
	private static $_enableEditor = FALSE;
	/**
	 * Possible languages
	 *
	 * @var arrays
	 */
	public $languages = array( 	"auto" => "automatic",
									"sq" => "albanian",
									"ar" => "arabic",
									"bg" => "bulgarian",
									"ca" => "catalan",
									"zh-CN" => "chinese",
									"hr" => "croatian",
									"cs" => "czech",
									"da" => "danish",
									"nl" => "dutch",
									"en" => "english",
									"et" => "estonian",
									"tl" => "filipino",
									"fi" => "finnish",
									"fr" => "french",
									"gl" => "galician",
									"de" => "german",
									"el" => "greek",
									"iw" => "hebrew",
									"hi" => "hindi",
									"hu" => "hungarian",
									"id" => "indonesian",
									"it" => "italian",
									"ja" => "japanese",
									"ko" => "korean",
									"lv" => "latvian",
									"lt" => "lithuanian",
									"mt" => "maltese",
									"no" => "norwegian",
									"fa" => "persian alpha",
									"pl" => "polish",
									"pt" => "portuguese",
									"ro" => "romanian",
									"ru" => "russian",
									"sr" => "serbian",
									"sk" => "slovak",
									"sl" => "slovenian",
									"es" => "spanish",
									"sv" => "swedish",
									"th" => "thai",
									"tr" => "turkish",
									"uk" => "ukrainian",
									"vi" => "vietnamese"
									 );
	
	/**
	 * Constructor
	 * 
	 * @access private
	 * @return void
	 */
	private function __construct() {} 
	
	/**
	 * Method to translate a string
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function _($string,$editable){

		// If selected language is default
		// just return the value
		if(self::$_language == self::$_defaultlanguage){
			$return = $string;
		} else {
			if(isset(self::$_translations[$string])){
				if(self::$_method == self::METHOD_GET){
					$value = self::$_translations[$string];
				}else{
					//print_r(self::$_translations);
					$value = self::$_translations[$string];
				}
				$return = $value;
			}else{
				// Add string to translations
				if(self::$_auto){
					self::$_translations[$string] = self::translate(self::$_defaultlanguage, self::$_language, $string);
					self::save();
					$return = self::$_translations[$string];
					//return mb_convert_encoding(self::$_translations[$string],"UTF-8");
				}else{
					self::$_translations[$string] = '<!-- TRANSLATE THIS -->';
					self::save();
					$return = self::$_translations[$string];
				}
			}
		}
		if(self::$_enableEditor && $editable == TRUE) {
			$id = getValue("SELECT id FROM translations WHERE `language` = ".quote_smart(self::$_language)." AND `page` = ".quote_smart(self::$_page)." AND translationKey = ".quote_smart($string)."");
			$return = '<div id="belowButton'.$id.'">'.$return.'</div>';
			$GLOBALS['columEditors'][] = $id;
		}
		return $return;
	}
	
	/**
	 * Load language file
	 * 
	 * @access public
	 * @param string $language
	 * @return void
	 */
	private function load($language){
		$sql = mysql_query("SELECT * FROM translations WHERE `language` = ".quote_smart($language)." AND `page` = ".quote_smart(self::$_page)."");
		$count = mysql_num_rows($sql);
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($sql, $i);
			$arr = mysql_fetch_array($sql);
			$k = $arr['translationKey'];
			$v = $arr['translationValue'];
			self::$_translations[$k] = $v;
		}
	}
	
	/**
	 * Save translations back to csv
	 * 
	 * @access public
	 * @return void
	 */
	public function save(){
		$array = "";
		foreach(self::$_translations as $key => $value){
			//$array[]= $key . ";" . $value;
			if(getValue("SELECT COUNT(id) FROM translations WHERE `language` = ".quote_smart(self::$_language)." AND `page` = ".quote_smart(self::$_page)." AND translationKey = ".quote_smart($key)) > 0) {
				mysql_query("UPDATE translations SET translationValue = ".quote_smart($value)." WHERE translationKey = ".quote_smart($key)." AND `language` = ".quote_smart(self::$_language)." AND `page` = ".quote_smart(self::$_page)."") or die(mysql_error());
			} else if($value != '') {
				mysql_query("INSERT INTO translations (`language`, `page`, translationKey, translationValue) VALUES (".quote_smart(self::$_language).", ".quote_smart(self::$_page).", ".quote_smart($key)." , ".quote_smart($value).")") or die(mysql_error());
			}
		}
	}
	
	/**
	 * See if language is enabled as active translation
	 * 
	 * @param string $language	What language to check
	 * @return boolean
	
	private function IsEnabled($language){
		if(if(getValue("SELECT COUNT(id) FROM languages WHERE language = ".quote_smart($language)." AND enabled = '1'") > 0){
			return true;
		} else {
			return false;
		}
	} */
	
	/**
	 * Read file content
	 * 
	 * @param string $file	Wich file to read
	 * @return string
	 */
	private function readFileContent($file){
		$data = "";
		if(filesize($file) > 0){
			$fh = fopen($file , 'r');
			$data = fread($fh, filesize($file));
			fclose($fh); 
		}
		return $data;
	}
	
	/**
	 * Write content to file
	 * 
	 * @param string $file	Save content to wich file
	 * @param string $content	String that needs to be written to the file
	 * @return bool
	 */
	private function writeFileContent($file, $content){
		$fp = fopen($file, 'w');
		fwrite($fp, $content);
		fclose($fp);
		return true;
	}
	
	/**
	 * Get an the current instance of this object
	 * 
	 * @access public
	 * @return Lanuage
	 */
	public static function GetInstance() 
    { 
        if (!self::$_instance) { 
            self::$_instance = new Language(); 
			self::$_language = self::$_defaultlanguage;
        }
        return self::$_instance; 
    } 
    
    /**
     * Set the page thats being translated
     * 
     * @access public
     * @param string $page
     * @return void
     */
    public function SetPage($page){
    	self::$_page = $page;
    }
    
    /**
     * Set the path where to save the translation files
     * 
     * @access public
     * @param string $path
     * @return void
     */
    public function SetEnableEditor($value){
    	self::$_enableEditor = $value;
    }
    
    /**
     * Set the default language
     * 
     * @access public
     * @param string $language
     * @return void
     */
    public function SetDefault($language){
    	self::$_defaultlanguage = $language;
    }
    
    /**
     * Set http request method
     * 
     * @access public 
     * @param string $method
     * @return void
     */
    public function SetMethod($method){
    	
    	if($method == self::METHOD_GET){
    		self::$_method = $method;
    		return;
    	}
    	
    	// POST method
    	if(function_exists("json_decode") && function_exists("curl_init")){
    		self::$_method = $method;
    	}else{
    		trigger_error("You can't use POST method because your server does not support json_decode and/or curl.", E_USER_ERROR);
    	}
    }
    
    /**
     * Set the current language

     * 
     * @access public
     * @param string $language
     * @return void
     */
    public function Set(&$language){
    	if(!empty($language)){
	    	self::load($language);
	    	self::$_language = $language;
    	}else{
    		self::load(self::$_defaultlanguage);
    		$language = self::$_defaultlanguage;
    		self::$_language = $language;
    	}
    }
    
    /**
     * Get the current language
     * 
     * @access public
     * @return string
     */
    public function Get(){
    	return self::$_language;
    }
    
    /**
     * Enable auto google translation
     * 
     * @access public
	 * @param bool $bool
           * @return string
           */
    public function SetAuto($bool){
    	self::$_auto = $bool;
    }
    
    /**
	 * Translate a string using google translator
	 * 
	 * @access private
	 * @param string $from
	 * @param string $to
	 * @param string $string
	 * @return string
	 */
    private function translate($from, $to, $string){
		
		if(self::$_method == self::METHOD_GET){
			$return = self::getTranslation($from, $to, $string);
		}else{
			$return = self::postTranslation($from, $to, $string);
		}
		return $return;
    }
    
    /**
     * Translate with GET
     * 
     * @access private
     * @param string $from
	 * @param string $to
     * @param string $string
     * @return string
     */
    private function getTranslation($from, $to, $string){
    	if(getValue("SELECT COUNT(id) FROM translations WHERE `page` = ".quote_smart(self::$_page)." AND `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."") > 0) {
			return getValue("SELECT translationValue FROM translations WHERE `page` = ".quote_smart(self::$_page)." AND `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."");
		} else if(getValue("SELECT COUNT(id) FROM translations WHERE `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."") > 0) {
			$value = getValue("SELECT translationValue FROM translations WHERE `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."");
			mysql_query("INSERT INTO translations (`language`, `page`, translationKey, translationValue) VALUES (".quote_smart($to).", ".quote_smart(self::$_page).", ".quote_smart($string)." , ".quote_smart($value).")") or die(mysql_error());
			return getValue("SELECT translationValue FROM translations WHERE `page` = ".quote_smart(self::$_page)." AND `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."");
		} else {
			//COMMENTED FOR NEW SERVICE
			//$url		= 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' . rawurlencode($string) . '&langpair=' . rawurlencode($from.'|'.$to);
			//$url		= 'http://mymemory.translated.net/api/get?q=' . rawurlencode($string) . '&langpair=' . rawurlencode($from.'|'.$to);
		
			$url		= 'http://mymemory.translated.net/api/get?q=' . rawurlencode($string) . '&langpair=' . rawurlencode($from.'|'.$to);
			$response 	= file_get_contents($url, NULL,stream_context_create(array('http'=>array('method'=>"GET",'header'=>"Referer: http://". $_SERVER['HTTP_HOST']."/\r\n"))));
			
			
			return self::cleanText($response);
		}
    }
    
    /**
     * Translate with POST
     * 
     * @access private
     * @param string $from
	 * @param string $to
     * @param string $string
     * @return string
     */
    private function postTranslation($from, $to, $string){
    	if(getValue("SELECT COUNT(id) FROM translations WHERE `page` = ".quote_smart(self::$_page)." AND `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."") > 0) {
			return getValue("SELECT translationValue FROM translations WHERE `page` = ".quote_smart(self::$_page)." AND `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."");
		} else if(getValue("SELECT COUNT(id) FROM translations WHERE `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."") > 0) {
			$value = getValue("SELECT translationValue FROM translations WHERE `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."");
			mysql_query("INSERT INTO translations (`language`, `page`, translationKey, translationValue) VALUES (".quote_smart($to).", ".quote_smart(self::$_page).", ".quote_smart($string)." , ".quote_smart($value).")") or die(mysql_error());
			return getValue("SELECT translationValue FROM translations WHERE `page` = ".quote_smart(self::$_page)." AND `language` = ".quote_smart($to)." AND translationKey = ".quote_smart($string)."");
		} else {
			//$url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=" . urlencode($string) . "&langpair=" . rawurlencode($from.'|'.$to);
			$url		= 'http://mymemory.translated.net/api/get?q=' . rawurlencode($string) . '&langpair=' . rawurlencode($from.'|'.$to);
			
			$header    = array(
				"Accept-Charset: utf-8",
			); 
			
			
			// Do request to google server
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, "http://" . $_SERVER['HTTP_HOST']);
			$response = curl_exec($ch);
			curl_close($ch);
			
			return self::cleanRegText($response);
		}
    }
    
	function cleanRegText($string) {
    	if (preg_match("/{\"translatedText\":\"([^\"]+)\"/i", $string, $matches)) {
        	return self::unescapeUTF8EscapeSeq($matches[1]);$matches[1];
    	}
	}
	
    /**
     * Cleans up the returned google json and return the translated text
     * And unescape UTF-8 sequences
     * 
     * @access private
     * @param string $string
     * @return string
     */
    private function cleanText($string){
    	if (preg_match("/{\"translatedText\":\"([^\"]+)\"/i", $string, $matches)) {
        	return self::unescapeUTF8EscapeSeq($matches[1]);
    	}
    }
    
	/**
     * Convert UTF-8 Escape sequences in a string to UTF-8 Bytes
     * 
     * @return UTF-8 String
     * @param $str String
     */
     private function unescapeUTF8EscapeSeq($str) {
     	return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return Language::bin2utf8(hexdec($matches[1]));'), $str);
     }
     
	/**
     * Convert binary character code to UTF-8 byte sequence
     * 
     * @return String
     * @param $bin Mixed Interger or Hex code of character
     */
     public function bin2utf8($bin) {
     	if ($bin <= 0x7F) {
        	return chr($bin);
        } else if ($bin >= 0x80 && $bin <= 0x7FF) {
            return pack("C*", 0xC0 | $bin >> 6, 0x80 | $bin & 0x3F);
        } else if ($bin >= 0x800 && $bin <= 0xFFF) {
            return pack("C*", 0xE0 | $bin >> 11, 0x80 | $bin >> 6 & 0x3F, 0x80 | $bin & 0x3F);
        } else if ($bin >= 0x10000 && $bin <= 0x10FFFF) {
            return pack("C*", 0xE0 | $bin >> 17, 0x80 | $bin >> 12 & 0x3F, 0x80 | $bin >> 6& 0x3F, 0x80 | $bin & 0x3F);
        }
	}
    
}

/**
 * Translate a string
 *
 * @param string $string
 * @return string
 */
function __($string,$editable=FALSE){
	global $Language;
	if($editable) return $Language->_($string,TRUE);
	else return $Language->_($string,FALSE);
}

$Language = Language::GetInstance();

?>