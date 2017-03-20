<?php 
class Synchronization
{
    /**
     * @param Request $request
     */
    public function deploy()
    {
        $commands = ['cd /data/wwwroot/vicky/www', 'git pull'];
		$headers = $this->__getallheaders();
        $signature = $headers['X-Hub-Signature']; // $headers = getallheaders(); $headers['X-Hub-Signature']
        $payload = file_get_contents('php://input');
        if ($this->isFromGithub($payload, $signature)) {
            foreach ($commands as $command) {
                shell_exec($command);
            }
            http_response_code(200);
        } else {
            http_response_code(403);
        }
    }
    /**
     * @param $payload
     * @param $signature
     * @return bool
     */
    private function isFromGithub($payload, $signature)
    {
        return 'sha1=' . hash_hmac('sha1', $payload, 'wangdachui', false) === $signature;
    }
	
	private function __getallheaders()
	{
		foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
	}
	
}

$init = new Synchronization;
$init->deploy();
