<?php

/**
 * Class DeepLException
 https://github.com/Baby-Markt/deepl-php-lib
 *
 * @package BabyMarkt\DeepL
 */
class DeepLException extends \Exception
{
    // to be continued ..
}

/**
 * DeepL API client library
 *
 * @package BabyMarkt\DeepL
 */
class DeepL
{
    /**
     * API v1 URL
     */
    const API_URL_V1               = 'https://api.deepl.com/v1/translate';

    /**
     * API v2 URL
     */
    const API_URL_V2               = 'https://api.deepl.com/v2/translate';

    /**
     * API URL: Parameter auth_key
     */
    const API_URL_AUTH_KEY         = 'auth_key=%s';

    /**
     * API URL: Parameter text
     */
    const API_URL_TEXT             = 'text=%s';

    /**
     * API URL: Parameter source_lang
     */
    const API_URL_SOURCE_LANG      = 'source_lang=%s';

    /**
     * API URL: Parameter target_lang
     */
    const API_URL_DESTINATION_LANG = 'target_lang=%s';

    /**
     * API URL: Parameter tag_handling
     */
    const API_URL_TAG_HANDLING     = 'tag_handling=%s';

    /**
     * API URL: Parameter ignore_tags
     */
    const API_URL_IGNORE_TAGS      = 'ignore_tags=%s';

    /**
     * DeepL HTTP error codes
     *
     * @var array
     */
    protected $errorCodes = array(
        400 => 'Wrong request, please check error message and your parameters.',
        403 => 'Authorization failed. Please supply a valid auth_key parameter.',
        413 => 'Request Entity Too Large. The request size exceeds the current limit.',
        429 => 'Too many requests. Please wait and send your request once again.',
        456 => 'Quota exceeded. The character limit has been reached.'
    );

    /**
     * Supported translation source languages
     *
     * @var array
     */
    protected $sourceLanguages = array(
        'EN',
        'DE',
        'FR',
        'ES',
        'PT',
        'IT',
        'NL',
        'PL',
        'RU'
    );

    /**
     * Supported translation destination languages
     *
     * @var array
     */
    protected $destinationLanguages = array(
        'EN',
        'DE',
        'FR',
        'ES',
        'PT',
        'IT',
        'NL',
        'PL',
        'RU'
    );

    /**
     * @var integer
     */
    protected $apiVersion;

    /**
     * DeepL API Auth Key (DeepL Pro access required)
     *
     * @var string
     */
    protected $authKey;

    /**
     * cURL resource
     *
     * @var resource
     */
    protected $curl;

    /**
     * DeepL constructor
     *
     * @param string  $authKey
     * @param integer $apiVersion
     */
    public function __construct($authKey, $apiVersion = 2)
    {
        $this->authKey    = $authKey;
        $this->apiVersion = $apiVersion;
        $this->curl       = curl_init();

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * DeepL destructor
     */
    public function __destruct()
    {
        if ($this->curl && is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Translate the text string or array from source to destination language
     *
     * @param string|string[] $text
     * @param string          $sourceLanguage
     * @param string          $destinationLanguage
     * @param array           $tagHandling
     * @param array           $ignoreTags
     *
     * @return string|string[]
     *
     * @throws DeepLException
     */
    public function translate(
        $text,
        $sourceLanguage = 'de',
        $destinationLanguage = 'en',
        array $tagHandling = array(),
        array $ignoreTags = array()
    )
    {
        // make sure we only accept supported languages
        $this->checkLanguages($sourceLanguage, $destinationLanguage);

        // build the DeepL API request url
        $url  = $this->buildUrl($sourceLanguage, $destinationLanguage, $tagHandling, $ignoreTags);
        $body = $this->buildBody($text);

        // request the DeepL API
        $translationsArray = $this->request($url, $body);
        $translationsCount = count($translationsArray['translations']);

        if ($translationsCount == 0) {
            throw new DeepLException('No translations found.');
        }
        else if ($translationsCount == 1) {
            return $translationsArray['translations'][0]['text'];
        }
        else {
            return $translationsArray['translations'];
        }
    }

    /**
     * Check if the given languages are supported
     *
     * @param string $sourceLanguage
     * @param string $destinationLanguage
     *
     * @return boolean
     *
     * @throws DeepLException
     */
    protected function checkLanguages($sourceLanguage, $destinationLanguage)
    {
        $sourceLanguage = strtoupper($sourceLanguage);

        if (!in_array($sourceLanguage, $this->sourceLanguages)) {
            throw new DeepLException(sprintf('The language "%s" is not supported as source language.', $sourceLanguage));
        }

        $destinationLanguage = strtoupper($destinationLanguage);

        if (!in_array($destinationLanguage, $this->destinationLanguages)) {
            throw new DeepLException(sprintf('The language "%s" is not supported as destination language.', $sourceLanguage));
        }

        return true;
    }

    /**
     * Build the URL for the DeepL API request
     *
     * @param string $sourceLanguage
     * @param string $destinationLanguage
     * @param array  $tagHandling
     * @param array  $ignoreTags
     *
     * @return string
     */
    protected function buildUrl(
        $sourceLanguage,
        $destinationLanguage,
        array $tagHandling = array(),
        array $ignoreTags = array()
    )
    {
        // select correct api url
        switch ($this->apiVersion) {
            case 1:
                $url = DeepL::API_URL_V1;
                break;
            case 2:
                $url = DeepL::API_URL_V2;
                break;
            default:
                $url = DeepL::API_URL_V1;
        }

        $url .= '?' . sprintf(DeepL::API_URL_AUTH_KEY, $this->authKey);
        $url .= '&' . sprintf(DeepL::API_URL_SOURCE_LANG, strtolower($sourceLanguage));
        $url .= '&' . sprintf(DeepL::API_URL_DESTINATION_LANG, strtolower($destinationLanguage));

        if (!empty($tagHandling)) {
            $url .= '&' . sprintf(DeepL::API_URL_TAG_HANDLING, implode(',', $tagHandling));
        }

        if (!empty($this->ignoreTags)) {
            $url .= '&' . sprintf(DeepL::API_URL_IGNORE_TAGS, implode(',', $ignoreTags));
        }

        return $url;
    }

    /**
     * Build the body for the DeepL API request
     *
     * @param string|string[] $text
     *
     * @return string
     */
    protected function buildBody($text)
    {
        $body  = '';
        $first = true;

        if (!is_array($text)) {
            $text = (array)$text;
        }

        foreach ($text as $textElement) {

            $body .= ($first ? '' : '&') . sprintf(DeepL::API_URL_TEXT, rawurlencode($textElement));

            if ($first) {
                $first = false;
            }
        }

        return $body;
    }

    /**
     * Make a request to the given URL
     *
     * @param string $url
     *
     * @return array
     *
     * @throws DeepLException
     */
    protected function request($url, $body)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $response = curl_exec($this->curl);

        if (!curl_errno($this->curl)) {
            $httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

            if ($httpCode != 200 && array_key_exists($httpCode, $this->errorCodes)) {
                throw new DeepLException($this->errorCodes[$httpCode], $httpCode);
            }
        }
        else {
            throw new DeepLException('There was a cURL Request Error.');
        }

        $translationsArray = json_decode($response, true);

        if (!$translationsArray) {
            throw new DeepLException('The Response seems to not be valid JSON.');
        }

        return $translationsArray;
    }
}

/**
 * Translate
 * @author Nikita Gusakov <dev@nkt.me>
 * @link   http://api.yandex.com/translate/doc/dg/reference/translate.xml
 */
class WPR5_Translator
{
    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';
    const MESSAGE_UNKNOWN_ERROR = 'Unknown error';
    const MESSAGE_JSON_ERROR = 'JSON parse error';
    const MESSAGE_INVALID_RESPONSE = 'Invalid response from service';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var resource
     */
    protected $handler;

    /**
     * @link http://api.yandex.com/key/keyslist.xml Get a free API key on this page.
     *
     * @param string $key The API key
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Returns a list of translation directions supported by the service.
     * @link http://api.yandex.com/translate/doc/dg/reference/getLangs.xml
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     *
     * @return array
     */
    public function getSupportedLanguages($culture = null)
    {
        return $this->execute('getLangs', array(
            'ui' => $culture
        ));
    }

    /**
     * Detects the language of the specified text.
     * @link http://api.yandex.com/translate/doc/dg/reference/detect.xml
     *
     * @param string $text The text to detect the language for.
     *
     * @return string
     */
    public function detect($text)
    {
        $data = $this->execute('detect', array(
            'text' => $text
        ));

        return $data['lang'];
    }

    /**
     * Translates the text.
     * @link http://api.yandex.com/translate/doc/dg/reference/translate.xml
     *
     * @param string|array $text     The text to be translated.
     * @param string       $language Translation direction (for example, "en-ru" or "ru").
     * @param bool         $html     Text format, if true - html, otherwise plain.
     * @param int          $options  Translation options.
     *
     * @return array
     */
    public function translate($text, $language, $html = false, $options = 0)
    {
        $data = $this->execute('translate', array(
            'text'    => $text,
            'lang'    => $language,
            'format'  => $html ? 'html' : 'plain',
            'options' => $options
        ));

        // @TODO: handle source language detecting
        return new WPR5_Translation($text, $data['text'], $data['lang']);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     *
     * @throws Exception
     * @return array
     */
    protected function execute($uri, array $parameters)
    {
        $parameters['key'] = $this->key;
        curl_setopt($this->handler, CURLOPT_URL, static::BASE_URL . $uri);
        curl_setopt($this->handler, CURLOPT_POST, true);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($parameters));
        
        $remoteResult = curl_exec($this->handler);
        if ($remoteResult === false) {
            throw new Exception(curl_error($this->handler), curl_errno($this->handler));
        }

        $result = json_decode($remoteResult, true);
        if (!$result) {
            $errorMessage = self::MESSAGE_UNKNOWN_ERROR;
            if (version_compare(PHP_VERSION, '5.3', '>=')) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    if (version_compare(PHP_VERSION, '5.5', '>=')) {
                        $errorMessage = json_last_error_msg();
                    } else {
                        $errorMessage = self::MESSAGE_JSON_ERROR;
                    }
                }
            }
            throw new Exception(sprintf('%s: %s', self::MESSAGE_INVALID_RESPONSE, $errorMessage));
        } elseif (isset($result['code']) && $result['code'] > 200) {
            throw new Exception($result['message'], $result['code']);
        }

        return $result;
    }
}

class WPR5_Translation
{
    /**
     * @var string|array
     */
    protected $source;
    /**
     * @var string|array
     */
    protected $result;

    /**
     * @var array
     */
    protected $language;

    /**
     * @param string|array $source   The source text
     * @param string|array $result   The translation result
     * @param string       $language Translation language
     */
    public function __construct($source, $result, $language)
    {
        $this->source = $source;
        $this->result = $result;
        $this->language = explode('-', $language);
    }

    /**
     * @return string|array The source text
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array|string The result text
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string The source language.
     */
    public function getSourceLanguage()
    {
        return $this->language[0];
    }

    /**
     * @return string The translation language.
     */
    public function getResultLanguage()
    {
        return $this->language[1];
    }

    /**
     * @return string The translation text.
     */
    public function __toString()
    {
        if (is_array($this->result)) {
            return join(' ', $this->result);
        }

        return (string) $this->result;
    }
}

function wpr5_yandex_trans($apikey, $text, $l1, $l2) {
	
	try {
		$translator = new WPR5_Translator($apikey);
		$translation = $translator->translate($text, $l1.'-'.$l2);

		$trans = $translation->getResult(); // Привет мир
		
		if(empty($trans[0])) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("Translation Error.","wprobot");
			return $return;			
		}

		return $trans[0];
		
	} catch (Exception $e) {
		$msg = $e->getMessage();
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "cURL Error";
		$return["error"]["message"] = __("Translation Error: ","wprobot").$msg;
		return $return;		
	}
}

function wpr5_deepl_trans($apikey, $text, $l1, $l2) {
	
	try {

		$authKey = $apikey;
		$deepl   = new DeepL($authKey);	

		$trans = $deepl->translate($text, $l1, $l2, array("xml"));

		if(empty($trans)) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("Translation Error.","wprobot");
			return $return;			
		}

		return $trans;
		
	} catch (DeepLException $e) {
		$msg = $e->getMessage();
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "cURL Error";
		$return["error"]["message"] = __("Translation Error: ","wprobot").$msg;
		return $return;		
	}
}

function wpr5_gtrns($text, $from, $to, $apikey, $deeplkey, $which) {

	if($which == "deepl") {
		// deepl translation
		$string = wpr5_deepl_trans($deeplkey, $text, $from, $to);	
	} else {
		$string = wpr5_yandex_trans($apikey, $text, $from, $to);	
	}

	return $string;
}

function wpr5_translate($text,$t1="",$t2="",$t3="",$t4="", $apikey, $deeplkey, $which) {

	if(empty($text)) {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("Empty text given.","wprobot");	
		return $return;		
	}
	
	if(empty($t2)) {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("No target language specified.","wprobot");	
		return $return;		
	}		
	
	if($t1 == $t2) {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("Same languages specified.","wprobot");	
		return $return;		
	}		

	if ($t1!='no' && $t2!='no') {
		$transtext = wpr5_gtrns($text, $t1, $t2, $apikey, $deeplkey, $which);
		if(!empty($transtext["error"]["reason"])) {
			return $transtext;
		}
	}
	if ($t1!='no'  && $t2!='no'  && $t3!='no') {
		$transtext = wpr5_gtrns($transtext, $t2, $t3, $apikey, $deeplkey, $which);
		if(!empty($transtext["error"]["reason"])) {
			return $transtext;
		}			
	}
	if ($t1!='no'  && $t2!='no'  && $t3!='no'  && $t4!='no') {
		$transtext = wpr5_gtrns($transtext, $t3, $t4, $apikey, $deeplkey, $which);
		if(!empty($transtext["error"]["reason"])) {
			return $transtext;
		}			
	}	

	if ( !empty($transtext) && $transtext != ' ') {
		return $transtext;
	} else {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("The post could not be translated.","wprobot");	
		return $return;		
	}
}

function wpr5_translate_partial($content) {

	$checkcontent = $content;
	
	preg_match_all('#\[translate(.*)\](.*)\[/translate\]#smiU', $checkcontent, $matches, PREG_SET_ORDER);
	if ($matches) {
		foreach($matches as $match) {
			$match[1] = substr($match[1], 1);
			$langs = explode("|", $match[1]);
			if(!empty($langs)) {

				if(empty($langs[0])) {$langs[0] = "no";}
				if(empty($langs[1])) {$langs[1] = "no";}
				if(empty($langs[2])) {$langs[2] = "no";}
				if(empty($langs[3])) {$langs[3] = "no";}
				$transcontent = wpr5_translate($match[2],$langs[0],$langs[1],$langs[2],$langs[3]);

			}
			
			if(!empty($transcontent) && !is_array($transcontent)) {
				$content = str_replace($match[0], $transcontent, $content);	
				return $content;
			} else {
				$content = str_replace($match[0], "", $content);	
				return $content;
			}
		}
	} else {
		return $content;	
	}	
	
	if(!empty($transcontent) && !is_array($transcontent)) {
		return $transcontent;
	} else {
		return $content;
	}

}

?>