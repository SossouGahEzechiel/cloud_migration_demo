<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TcpListenCommand extends Command
{
	/**
	 * Start a local TCP listener and answer each line-based request.
	 *
	 * Expected client protocol: one line per request, newline terminated.
	 */
	protected $signature = 'tcp:listen
                            {--host=127.0.0.1 : Interface to bind}
                            {--port=9001 : TCP port to listen on}';

	protected $description = 'Start a local TCP server and respond to incoming requests';

	public function handle(): int
	{
		$host = (string) $this->option('host');
		$port = (int) $this->option('port');

		if ($port < 1 || $port > 65535) {
			$this->error('Invalid port. Use a value between 1 and 65535.');

			return self::FAILURE;
		}

		$endpoint = sprintf('tcp://%s:%d', $host, $port);
		$server = @stream_socket_server($endpoint, $errno, $errstr);

		if ($server === false) {
			$this->error(sprintf('Unable to start TCP server on %s: %s (%d)', $endpoint, $errstr, $errno));

			return self::FAILURE;
		}

		$this->info(sprintf('TCP server listening on %s', $endpoint));
		$this->line('Press Ctrl+C to stop.');

		while (true) {
			$client = @stream_socket_accept($server, 1);

			if ($client === false) {
				continue;
			}

			$peer = stream_socket_get_name($client, true) ?: 'unknown';
			$line = fgets($client);

			if ($line === false) {
				fwrite($client, "ERR Empty request\n");
				fclose($client);
				continue;
			}

			$payload = trim($line);

			Log::info('TCP payload received', [
				'peer' => $peer,
				'payload' => $payload,
			]);

			fwrite($client, sprintf("OK %s\n", $payload));
			fclose($client);
		}
	}
}
