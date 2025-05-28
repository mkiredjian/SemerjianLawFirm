<?php
require_once(ROOT_DIR .'/tools/config.php');

  class ProxyClicker {
    /**
     * Function to simulate a mobile click using a rotating proxy
     * and return the HTTP response code and message.
     *
     * @param string $url - The URL to "click"
     * @return array - The HTTP response code and message
     */

     private static $logFile;

     public function __construct() {
        self::$logFile = "/var/log/phpLogs/logfile" . date('Y-m-d') . ".log";
    }

    public function clickLinkWithProxy($url) {

    file_put_contents(self::$logFile, "Attempting to click URL: $url\n", FILE_APPEND);

        // Initialize cURL
        $ch = curl_init();
        
        // Set the URL to fetch
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // Set the proxy
        curl_setopt($ch, CURLOPT_PROXY, PROXY_SERVER);
        
        // Set the proxy user credentials
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, PROXY_USERPWD);

        // Automatically follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        // Disable SSL verification (equivalent to -k)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Set the User-Agent to mimic a mobile device
        curl_setopt($ch, CURLOPT_USERAGENT, USER_MOBILE);
        
        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute the cURL session
        $response = curl_exec($ch);
        

        // Prepare the return message
        if (curl_errno($ch)) {
            file_put_contents(self::$logFile, "Error:". curl_error($ch)."\n", FILE_APPEND);

            $message = 'Error: ' . curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            file_put_contents(self::$logFile, "httpCode:". $httpCode."\n", FILE_APPEND);
            $message = 'Response: ' . $response;
        }

        // Close cURL
        curl_close($ch);

        // Return the HTTP code and message
        return [
            'http_code' => $httpCode,
            'message' => $message
        ];
    }


	/**
	 * Function to check if the text contains a URL, and extract it if present.
	 *
	 * @param string $text - The input text
	 * @return mixed - The extracted URL if found, or false if no URL exists
	 */
	function containsAndExtractLink($text) {


        $pattern = '/https?:\/\/[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-]+(\/[^\s.,]*?(\.php)?)(?=\s|$|\.)/';


		preg_match($pattern, $text, $matches);
	
	
		if (!empty($matches)) {
			$link = rtrim($matches[0], '.'); // Remove any trailing period if present
			echo "Extracted link: " . $link;
			return $link;
		} else {
			echo "No link found in the text.";
		}
        return false;
	}
 }
?>

