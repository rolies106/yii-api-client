<?php
/**
 * CurlRequests - Simple curl request components based on Requests Lib
 *
 * @author Sixreps
 */

/**
 * CurlRequests
 *
 * This class acts as a component for send request based on Requests Lib
 * GET, POST, PUT, and DELETE requests.
 *
 * @package CurlRequests
 */

class CurlRequests extends CApplicationComponent {

    /**
     * @var array A list of supported HTTP methods
     */
    protected $_http_methods = array('GET', 'POST', 'PUT', 'DELETE');

    /**
     * Create a new instance
     * @return  void
     */
    public function __construct() {
        if (!class_exists('Requests', false)) {
            # Turn off our amazing library autoload
            spl_autoload_unregister(array('YiiBase','autoload'));

            # Include request library
            include_once(dirname(__FILE__) . '/lib/Requests.php');

            # Include Sixreps Request Builder
            include_once(dirname(__FILE__) . '/lib/SRBuildRequest.php');

            # Run request autoloader
            Requests::register_autoloader();

            # Give back the power to Yii        
            spl_autoload_register(array('YiiBase','autoload'));            
        }
    }

    /**
     * DSL wrapper to make a GET request.
     *
     * @param   string  $uri        Target URL
     * @param   array   $headers    List of passed headers
     * @return  array               Processed response
     */
    public function get($uri, $headers = array()) {
        return $this->_request($uri, $headers, 'GET');
    }

    /**
     * DSL wrapper to make a POST request.
     *
     * @param   string  $uri        Target URL
     * @param   array   $headers    List of passed headers
     * @param   array   $extra      Extra parameter
     * @return  array               Processed response
     */
    public function post($uri, $headers = array(), $extra = array()) {
        return $this->_request($uri, $headers, 'POST', $extra);
    }

    /**
     * DSL wrapper to make a PUT request.
     *
     * @param   string  $uri        Target URL
     * @param   array   $headers    List of passed headers
     * @param   array   $extra      Extra parameter
     * @return  array               Processed response
     */
    public function put($uri, $headers = array(), $extra = array()) {
        return $this->_request($uri, $headers, 'PUT', $extra);
    }

    /**
     * DSL wrapper to make a DELETE request.
     *
     * @param   string  $uri        Target URL
     * @param   array   $headers    List of passed headers
     * @return  array               Processed response
     */
    public function delete($uri, $headers = array()) {
        return $this->_request($uri, $headers, 'DELETE');
    }

    /**
     * Get information from video links.
     *
     * @param   string  $uri        Target URL
     * @param   string   $type      youtube or vimeo
     * @return  array               Processed response
     */
    public function getVideoDetail($uri, $type) {
        if ($type == 'youtube') {

        } else if ($type == 'vimeo') {

        } else {
            return false;
        }
    }

    /**
     * Save image from URL.
     *
     * @param   string  $img        Source image URI
     * @param   array   $fullpath   Full path destination folder
     * @return  void
     */
    public function save_image($img, $fullpath = NULL){
        $ch = curl_init ($img);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        
        $rawdata=curl_exec($ch);
        
        curl_close ($ch);

        if(file_exists($fullpath)){
            unlink($fullpath);
        }

        if (empty($fullpath)) {
            return $rawdata;
        } else {
            $fp = fopen($fullpath,'x');
            fwrite($fp, $rawdata);
            fclose($fp);
        }
    }

    /**
     * DSL wrapper to make a HTTP request based on supported HTTP methods.
     *
     * @param   string  $uri        Target URL
     * @param   array   $args       Associative array of passed arguments
     * @param   array   $headers    List of passed headers
     * @param   string  $method     HTTP method
     * @return  array               Processed response
     */
    protected function _request($uri, $headers = array(), $method = 'GET', $extra = array()) {
        
        $url = trim($uri, '/');

        if (substr(trim($uri), 0, 4) != 'http') {
            $url = trim('http://' . $uri, '/');
        }

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

        Yii::import('ext.SimpleHTMLDOM.SimpleHTMLDOM');
        $simpleHTML = new SimpleHTMLDOM;
        $html = $simpleHTML->str_get_html($request->body);
        $info['metadata'] = array(
                                'title' => @$html->find('title', 0)->innertext, 
                                'image' => @$html->find('img', 0)->src,
                                'description' => @$html->find('p', 0)->plaintext
                            );

        $metas = $html->find('meta');
        foreach ($metas as $meta) {
            if ($meta->name != '') {
                $info['metadata'][$meta->name] = $meta->content;
            } else if ($meta->itemprop == 'image') {
                $info['metadata']['image'] = $meta->content;
            }
        }

        $body = $request->body;
        // $info['body'] = $request->body;
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
        return array(
            $body, $info
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
// set_exception_handler(array('Sixreps', 'handle_exception'));