<?php
/**
 * Rest
 *
 * @author Rest
 */

/**
 * Rest
 *
 * This class acts as a client for SixReps API that provides a DSL to make
 * GET, POST, PUT, and DELETE requests.
 *
 * @package Rest
 */

class Rest extends CApplicationComponent {

    /**
     * @var string User setting for API resource
     */
    public $api_host;

    /**
     * @var string Rest application ID
     */
    public $app_id;

    /**
     * @var string Rest application Secret
     */
    public $app_secret;

    /**
     * @var string Rest signed request key for internal request
     */
    public $signed_request_key;

    /**
     * @var string Default path to API resource server
     */
    protected $_host = 'http://api.domain.com/';

    /**
     * @var array A list of supported HTTP methods
     */
    protected $_http_methods = array('GET', 'POST', 'PUT', 'DELETE');

    /**
     * Create a new instance of Rest.
     * @return  void
     */
    public function __construct() {
        # Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase','autoload'));  

        # Include request library
        include_once(dirname(__FILE__) . '/lib/Requests.php');

        # Include Rest Request Builder
        include_once(dirname(__FILE__) . '/lib/SRBuildRequest.php');

        # Run request autoloader
        Requests::register_autoloader();

        # Give back the power to Yii        
        spl_autoload_register(array('YiiBase','autoload'));
    }

    /**
     * DSL wrapper to make a GET request.
     *
     * @param   string  $uri        Path to API resource
     * @param   array   $args       Associative array of passed arguments
     * @param   array   $headers    List of passed headers
     * @return  array               Processed response
     * @see     Rest::_response
     * @see     Rest::_request
     */
    public function get($uri, $args = array(), $headers = array()) {
        return $this->_request($uri, $args, $headers, 'GET');
    }

    /**
     * DSL wrapper to make a POST request.
     *
     * @param   string  $uri        Path to API resource
     * @param   array   $args       Associative array of passed arguments
     * @param   array   $headers    List of passed headers
     * @param   array   $extra      Extra parameter
     * @return  array               Processed response
     * @see     Rest::_response
     * @see     Rest::_request
     */
    public function post($uri, $args = array(), $headers = array(), $extra = array()) {
        return $this->_request($uri, $args, $headers, 'POST', $extra);
    }

    /**
     * DSL wrapper to make a PUT request.
     *
     * @param   string  $uri        Path to API resource
     * @param   array   $args       Associative array of passed arguments
     * @param   array   $headers    List of passed headers
     * @param   array   $extra      Extra parameter
     * @return  array               Processed response
     * @see     Rest::_response
     * @see     Rest::_request
     */
    public function put($uri, $args = array(), $headers = array(), $extra = array()) {
        return $this->_request($uri, $args, $headers, 'PUT', $extra);
    }

    /**
     * DSL wrapper to make a DELETE request.
     *
     * @param   string  $uri        Path to API resource
     * @param   array   $args       Associative array of passed arguments
     * @param   array   $headers    List of passed headers
     * @return  array               Processed response
     * @see     Rest::_response
     * @see     Rest::_request
     */
    public function delete($uri, $args = array(), $headers = array()) {
        return $this->_request($uri, $args, $headers, 'DELETE');
    }

    /**
     * DSL wrapper to make a HTTP request based on supported HTTP methods.
     *
     * @param   string  $uri        Path to API resource
     * @param   array   $args       Associative array of passed arguments
     * @param   array   $headers    List of passed headers
     * @param   string  $method     HTTP method
     * @return  array               Processed response
     * @see     Rest::_response
     */
    protected function _request($uri, $args = array(), $headers = array(), $method = 'GET', $extra = array()) {
        
        # Don't put this on construct because it'll return null instead get value from main configuration
        if (!empty($this->api_host)) {
            $this->_host = $this->api_host;
        }

        $url = $this->_host . trim($uri, '/');

        switch ($method) {
            case 'GET':
                if (!empty($args)) {
                    $url = $url . '?' . http_build_query($args);
                }
                $request = Requests::get($url, $headers);
                break;
            case 'POST':
                if (isset($extra['multipart']) && $extra['multipart'] == true) {
                    $srequest = new SRBuildRequest;
                    $buildRequest = $srequest->buildMultipartRequest($args);
                    $args = $buildRequest->data;
                    $headers = array_merge($headers, $buildRequest->headers);
                }
                $request = Requests::post($url, $headers, $args);
                break;
            case 'PUT':
                if (isset($extra['multipart']) && $extra['multipart'] == true) {
                    $srequest = new SRBuildRequest;
                    $buildRequest = $srequest->buildMultipartRequest($args);
                    $args = $buildRequest->data;
                    $headers = array_merge($headers, $buildRequest->headers);
                }
                $request = Requests::put($url, $headers, $args);
                break;
            case 'DELETE':
                if (!empty($args)) {
                    $url = $url . '?' . http_build_query($args);
                }
                $request = Requests::delete($url, array());
                break;
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unsupported %s HTTP method. It should match one of %s keywords.',
                    $method, implode(', ', $this->_http_methods)
                ));
        }

        $body = $request->body;
        $info['headers'] = $request->headers;
        $info['status_code'] = $request->status_code;
        $info['success'] = $request->success;        
        $info['redirects'] = $request->redirects;        
        $info['url'] = $request->url;
        return $this->_response($body, $info, $method);
    }

    /**
     * Typically process response returned from API request.
     *
     * @param   string  $body   Body of response returned from API request
     * @param   array   $info   Headers of response returned from API request
     * @return  array           Processed response
     */
    protected function _response($body, $info, $method) {
        $body = (json_decode($body)) ? json_decode($body) : $body;
        
        return array(
            $body,
            $info
        );
    }

    /**
     * Handle uncaught exception.
     *
     * @param   Exception   $e  Exception or its subclasses
     * @return  void
     */
    public static function handle_exception(Exception $e) {
        die($e);
    }
}

// This determines which errors are reported by PHP.
// By default, all errors (including E_STRICT) are reported.
// error_reporting(E_ALL | E_STRICT);

// PHP 5.3 will complain if you don't set a timezone. This tells PHP to use UTC.
if (@date_default_timezone_set(date_default_timezone_get()) === false) {
    date_default_timezone_set('UTC');
}

// Handle uncaught exception.
// set_exception_handler(array('Rest', 'handle_exception'));