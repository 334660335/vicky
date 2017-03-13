<?php 
class Synchronization
{
    /**
     * @param Request $request
     */
    public function deploy()
    {
        $commands = ['cd /var/www/laravel-ubuntu', 'git pull'];
		$headers = getallheaders();
        $signature = $headers['X-Hub-Signature']; // $headers = getallheaders(); $headers['X-Hub-Signature']
        $payload = file_get_contents('php://input');
        if ($this->isFromGithub($payload, $signature)) {
			echo 1;die;
            foreach ($commands as $command) {
                shell_exec($command);
            }
            http_response_code(200);
        } else {
			echo 2;die;
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
}

$init = new Synchronization;
$init->deploy();