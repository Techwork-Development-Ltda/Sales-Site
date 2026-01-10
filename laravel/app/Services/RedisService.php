<?php
namespace App\Services;

use App\Exceptions\RedisConnectionException;
use App\Exceptions\RedisSocketException;
use App\Exceptions\RedisCommandException;

class RedisService
{
    protected $host;
    protected $port;
    protected $timeout;
    protected $socket;
    protected $password;
    protected $db;
    protected $isUnixSocket = false;

    public function __construct(string $host = 'redis', int $port = 6379, float $timeout = 2.0, ? string $password = null, ? int $db = null)
    {
        if (strpos($host, '/') === 0 || strpos($host, 'unix://') === 0) {
            $this->isUnixSocket = true;
        }
        $this->host = getenv('REDIS_HOST') ?? $host;
        $this->port = getenv('REDIS_PORT') ?? $port;
        $this->timeout = $timeout;
        $this->password = getenv('REDIS_PASSWORD') ?? $password;
        $this->db = $db;
    }

    public function connect(): bool
    {
        if ($this->socket && !feof($this->socket)) {
            return true;
        }

        $target = $this->isUnixSocket ?  $this->host : sprintf('tcp://%s:%d', $this->host, $this->port);

        $errNo = 0;
        $errStr = '';
        $this->socket = @stream_socket_client($target, $errNo, $errStr, $this->timeout);

        if ($this->socket === false) {
            throw new RedisConnectionException(
                "Não conseguiu conectar ao Redis em {$target}",
                [
                    'target' => $target,
                    'error_code' => $errNo,
                    'error_message' => $errStr
                ]
            );
        }

        stream_set_timeout($this->socket, (int)$this->timeout, (int)(($this->timeout - (int)$this->timeout) * 1e6));

        if ($this->password !== null) {
            $this->sendCommand('AUTH', [$this->password]);
        }
        if ($this->db !== null) {
            $this->sendCommand('SELECT', [(string)$this->db]);
        }

        return true;
    }

    public function close(): void
    {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
    }

    protected function buildCommand(array $parts): string
    {
        $cmd = '*' . count($parts) . "\r\n";
        foreach ($parts as $p) {
            $p = (string)$p;
            $cmd .= '$' . strlen($p) . "\r\n" . $p . "\r\n";
        }
        return $cmd;
    }

    public function sendCommand(string $command, array $args = [])
    {
        $this->connect();

        $parts = array_merge([$command], $args);
        $cmdText = $this->buildCommand($parts);

        $written = fwrite($this->socket, $cmdText);
        if ($written === false) {
            throw new RedisSocketException(
                "Erro escrevendo no socket Redis",
                ['command' => $command, 'args' => $args]
            );
        }

        return $this->readResponse();
    }

    protected function readLine(): string
    {
        $line = fgets($this->socket);
        if ($line === false) {
            throw new RedisSocketException(
                "Erro lendo do socket Redis",
                ['operation' => 'readLine']
            );
        }
        return rtrim($line, "\r\n");
    }

    protected function readBulk($length)
    {
        if ($length === -1) {
            return null;
        }

        $data = '';
        $toRead = $length;
        while ($toRead > 0) {
            $chunk = fread($this->socket, $toRead);
            if ($chunk === false || $chunk === '') {
                throw new RedisSocketException(
                    "Erro lendo bulk do socket Redis",
                    ['expected_length' => $length, 'read_so_far' => strlen($data)]
                );
            }
            $data .= $chunk;
            $toRead -= strlen($chunk);
        }
        $crlf = fread($this->socket, 2);
        return $data;
    }

    protected function readResponse()
    {
        $prefix = fgetc($this->socket);
        if ($prefix === false) {
            throw new RedisSocketException(
                "Erro lendo resposta do Redis (prefix)",
                ['operation' => 'readResponse']
            );
        }

        switch ($prefix) {
            case '+':
                return $this->readLine();
            case '-':
                $err = $this->readLine();
                throw new RedisCommandException(
                    "Erro no comando Redis",
                    ['redis_response' => $err],
                    $err
                );
            case ':':
                return (int)$this->readLine();
            case '$':
                $len = (int)$this->readLine();
                return $this->readBulk($len);
            case '*': 
                $count = (int)$this->readLine();
                if ($count === -1) {
                    return null;
                }
                $arr = [];
                for ($i = 0; $i < $count; $i++) {
                    $arr[] = $this->readResponse();
                }
                return $arr;
            default:
                throw new RedisSocketException(
                    "Resposta Redis desconhecida",
                    ['prefix' => $prefix]
                );
        }
    }

    // convenience wrappers
    public function ping()
    {
        return $this->sendCommand('PING');
    }

    public function set(string $key, string $value, ? int $ttlSeconds = null)
    {
        if ($ttlSeconds === null) {
            return $this->sendCommand('SET', [$key, $value]);
        }
        return $this->sendCommand('SET', [$key, $value, 'EX', (string)$ttlSeconds]);
    }

    public function get(string $key)
    {
        return $this->sendCommand('GET', [$key]);
    }

    public function del(string ...$keys)
    {
        return $this->sendCommand('DEL', $keys);
    }

    public function hgetall(string $key)
    {
        $res = $this->sendCommand('HGETALL', [$key]);
        if (! is_array($res)) {
            return $res;
        }
        $out = [];
        for ($i = 0; $i < count($res); $i += 2) {
            $field = $res[$i];
            $value = $res[$i + 1] ?? null;
            $out[$field] = $value;
        }
        return $out;
    }
}