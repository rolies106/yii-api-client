<?php
/**
 * SRBuildRequest - Component library for build request
 *
 * @author Sixreps - Rolies106
 */

/**
 * SRBuildRequest
 *
 * This class acts as request builder for sixreps SDK
 *
 * @package Sixreps
 */

if (class_exists('SRBuildRequest')) {
    return true;
    exit();
}

class SRBuildRequest
{		
    /**
     * @var string Boundary id
     */
    protected $boundary;

	public function __construct()
	{
		$this->boundary = '------------------------------' . md5(uniqid()) . rand();
	}

    /**
     * Build 
     *
     * @param   Exception   $e  Exception or its subclasses
     * @return  string like http_query_build
     */
	public function buildMultipartRequest($args) {
        $data = $this->build_boundary($args, $this->boundary);

        $headers['Content-Type'] = 'multipart/form-data; boundary="' . $this->boundary . '"';
        $headers['Content-Length'] = strlen($data);

        $return['data'] = $data;
        $return['headers'] = $headers;
        return (object) $return;
	}

    /**
     * Process post fields array to string
     *
     * @param   Exception   $e  Exception or its subclasses
     * @return  string like http_query_build
     */
    protected function build_field_request($args) {
        $return = array();
        $multidimensional = false;

        if (is_array($args) && !empty($args)) {

            # Process for level 1
            foreach ($args as $key => $value) {
                if (is_array($value) && !empty($value)) {
                    # Tell script that this is multidimensional array
                    $multidimensional = true;

                    # Process for level 2 if is array
                    foreach ($value as $subkey => $subvalue) {
                        if (substr($subvalue, 0, 1) == '@') {
                            $return[] = $key . '=' . $subvalue;
                        } else {
                            $return[] = $key . '=' . urlencode($subvalue);
                        }
                    }

                } else {
                    if (substr($value, 0, 1) == '@') {
                        $return[] = $key . '=' . $value;
                    } else {
                        $return[] = $key . '=' . urlencode($value);
                    }
                }
            }

            $return = implode('&', $return);
        }

        if ($multidimensional == true) {
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * Process to build multipart from array to string
     *
     * @param   array   $args  Array data post
     * @return  string  boundary data string
     */
    protected function build_boundary($args, $boundary) {
        $return = array();
        $withfile = false;

        if (is_array($args) && !empty($args)) {

            # Process for level 1
            foreach ($args as $key => $value) {
                if (is_array($value) && !empty($value)) {
                    # Process for level 2 if is array
                    foreach ($value as $subkey => $subvalue) {
                        if (substr($subvalue, 0, 1) == '@') {
                            $withfile = true;
                            $return[] = $this->_boundaryFile($key, $subvalue, $boundary);
                        } else {
                            $return[] = $this->_boundaryText($key, $subvalue, $boundary);
                        }
                    }

                } else {

                    if (substr($value, 0, 1) == '@') {
                        $withfile = true;
                        $return[] = $this->_boundaryFile($key, $value, $boundary);
                    } else {
                        $return[] = $this->_boundaryText($key, $value, $boundary);
                    }
                    
                }
            }

            $return[] = "--{$boundary}--";
            $return = implode('', $return);
        }

        if ($withfile == true) {
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * Build boundary string for file
     *
     * @param   string   $name  Key name
     * @param   string   $path  Filepath
     * @return  string  boundary data string
     */
    protected function _boundaryFile($name, $path, $boundary)
    {
        if (substr($path, 0, 1) == '@') {
            $path = substr($path, 1, strlen($path));
        }

        if (file_exists($path)) {
            $body = "--{$boundary}\r\n"
                   . "Content-Disposition: form-data; name=\"$name\"; filename=\"" . basename($path) . "\"\r\n"
                   . "Content-Type: application/octet-stream\r\n" // You should put the right MIME type here
                   . "\r\n"
                   . file_get_contents($path) . "\r\n";
            return $body;
        } else {
            return NULL;
        }
    }

    /**
     * Build boundary string for text
     *
     * @param   string   $name  Key name
     * @param   string   $text  Text value
     * @return  string  boundary data string
     */
    protected function _boundaryText($name, $text, $boundary)
    {
        $body = "--{$boundary}\r\n"
               . "Content-Disposition: form-data; name=\"$name\"\r\n"
               . "\r\n"
               . "{$text}\r\n";
        return $body;
    }
}