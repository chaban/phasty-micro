<?php
namespace Phasty;

use Phalcon\Mvc\User\Plugin;

class Response extends Plugin {

	protected $head = false;

	public function __construct() {
		if (strtolower($this->di->get('request')->getMethod()) === 'head') {
			$this->head = true;
		}
	}

	public function send($records, $error = false) {

		// Error's come from HTTPException.  This helps set the proper envelope data
		//$this->response = $this->di->get('response');
		$status = ($error) ? 'ERROR' : 'SUCCESS';
        $data = [];
        if(isset($records['data'])){
            $data = $records['data'];
        }else{
            $data = $records;
        }

		$etag = md5(serialize($data));

		$message = [];
        if(isset($records['meta'])){
            $message['meta'] = $records['meta'];
        }
		$message['meta']['status'] = $status;
		if ($this->config->app->debug) {
			$message['meta']['memory_usage'] = memory_get_usage(true);
		}

		// Handle 0 record responses, or assign the records
		if ($error) {
			// This is required to make the response JSON return an empty JS object.  Without
			// this, the JSON return an empty array:  [] instead of {}
			$message['data'] = $this->config->app->debug ? $data : new \stdClass();
		} else {
			$message['data'] = $data;
		}

		$this->response->setContentType('application/json');
		$this->response->setHeader('E-Tag', $etag);

		// HEAD requests are detected in the parent constructor. HEAD does everything exactly the
		// same as GET, but contains no body.
		if (!$this->head) {
			$this->response->setJsonContent($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
		}

		$this->response->send();

		return $this;
	}
}
