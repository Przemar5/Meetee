<?php

namespace Meetee\Libs\Sockets;

use Meetee\Libs\Sockets\SocketServer;

abstract class WebSocketServer extends SocketServer
{
	public const string ADDRESS = '0.0.0.0';
	public const int PORT = 12344;
	public static array $users = [];
	private \Socket $client;

	public function __construct(): void
	{
		$this->client = $this->openClient();
		$headers = $this->prepareHeaders();
		socket_write($this->client, $headers, strlen($headers));
	}

	private function openClient(): \Socket
	{
		$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
		socket_bind($server, static::ADDRESS, static::PORT);
		socket_listen($server);
		$client = socket_accept($server);
		static::$users[] = $client;

		return $client;
	}

	private function prepareHeaders(string $key): string
	{
		$request = socket_read($client, 5000);
		preg_match('/Sec-WebSocket-Key: (.*)\r\n/', $request, $matches);
		$key = base64_encode(pack(
		    'H*',
		    sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
		));
		$headers = "HTTP/1.1 101 Switching Protocols\r\n";
		$headers .= "Upgrade: websocket\r\n";
		$headers .= "Connection: Upgrade\r\n";
		$headers .= "Sec-WebSocket-Version: 13\r\n";
		$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";

		return $headers;
	}

	public function awaitForMessage(callable $callback): void
	{
		while ($msg = socket_read($client, 2048)) {
		    $msg = $this->unmask($msg);
		
		    return $callback($msg);
		}
	}

	private function unmask(string $text): string
	{
	    $length = ord($text[1]) & 127;

	    if ($length == 126) {
	        $masks = substr($text, 4, 4);
	        $data = substr($text, 8);
	    }
	    elseif ($length == 127) {
	        $masks = substr($text, 10, 4);
	        $data = substr($text, 14);
	    }
	    else {
	        $masks = substr($text, 2, 4);
	        $data = substr($text, 6);
	    }

	    $text = '';

	    for ($i = 0; $i < strlen($data); ++$i)
	        $text .= $data[$i] ^ $masks[$i%4];
	    
	    return $text;
	}

	private function mask(string $text): string
	{
	    $b1 = 0x80 | (0x1 & 0x0f);
	    $length = strlen($text);

	    if ($length <= 125)
	        $header = pack('CC', $b1, $length);
	    elseif ($length > 125 && $length < 65536)
	        $header = pack('CCn', $b1, 126, $length);
	    elseif ($length >= 65536)
	        $header = pack('CCNN', $b1, 127, $length);
	    
	    return $header . $text;
	}
}