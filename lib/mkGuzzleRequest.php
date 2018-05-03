<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

const DEBUG = FALSE;

/**
 * Usage:
 *   $request = mkGuzzleRequest();
 *	 $conf = new GenericConfiguration();
 *   $conf
 * 		->setCountry('co.jp')
 *		->setAccessKey(AWS_API_KEY)
 *		->setSecretKey(AWS_API_SECRET_KEY)
 *		->setAssociateTag(AWS_ASSOCIATE_TAG)
 *		->setRequest($request)
 *		->setResponseTransformer(new \ApaiIO\ResponseTransformer\XmlToSimpleXmlObject());
 *
 *	$apaiIO = new ApaiIO($conf);
 */
function mkGuzzleRequest() {
    // リトライ判断
	$decider = function(
        $retries,
        Request $request,
	    Response $response = null,
        RequestException $e = null) {

//        printf("CHECK retries= %s\n", $retries);
//        var_dump($e);
        
		if ($retries >= 5) {
			return false;
		}
		$shouldRetry = false;
        
		if ($e instanceof ConnectException) {
			$shouldRetry = true;
		}
		if ($response) {
			if ($response->getStatusCode() >= 500) {
				$shouldRetry = true;
			}
		}
		if ($shouldRetry) {
			if (DEBUG)
				printf("Retrying %s\n %s\n %s/5, %s\n",
					   $request->getMethod(),
					   $request->getUri(),
					   $retries + 1,
					   $response ? 'status code: ' . $response->getStatusCode() :
					   $e->getMessage()
					);
		}
        return $shouldRetry;
	};
    
	
	// 遅延時間
	$delay = function($retries) {
				 return 1000;  // 1000ミリ秒待つ
			 };
	$handlerStack = HandlerStack::create(new CurlHandler());
	$handlerStack->push(Middleware::retry($decider, $delay));
	$client = new \GuzzleHttp\Client(['handler' => $handlerStack, 'timeout' => 5]);
	$request = new \ApaiIO\Request\GuzzleRequest($client);

    return $request;
}
?>
