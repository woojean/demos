<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FibonacciRpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            'localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "", FALSE, FALSE, TRUE, FALSE);
        $this->channel->basic_consume(
            $this->callback_queue, '', FALSE, FALSE, FALSE, FALSE,
            [$this, 'on_response']);
    }

    public function on_response($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }

        /*
         If we see an unknown correlation_id value, we may safely discard the message - it doesn't belong to our requests.
        You may ask, why should we ignore unknown messages in the callback queue, rather than failing with an error?
         It's due to a possibility of a race condition on the server side.
        Although unlikely, it is possible that the RPC server will die just after sending us the answer, but before sending an acknowledgment message for the request.
        If that happens, the restarted RPC server will process the request again.
        That's why on the client we must handle the duplicate responses gracefully, and the RPC should ideally be idempotent.*/
    }

    public function call($n)
    {
        $this->response = NULL;
        $this->corr_id = uniqid();  // set it to a unique value for every request

        $msg = new AMQPMessage(
            (string)$n,
            ['correlation_id' => $this->corr_id,
             'reply_to'       => $this->callback_queue]
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');
        while (!$this->response) {
            $this->channel->wait();
        }
        return intval($this->response);
    }
};



$fibonacci_rpc = new FibonacciRpcClient();
$response = $fibonacci_rpc->call(30);
echo " [.] Got ", $response, "\n";