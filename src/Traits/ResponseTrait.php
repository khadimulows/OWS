<?php
namespace Pitangent\Workflow\Traits;

trait ResponseTrait
{
    protected $message;
    /*
    |-------------------------------------
    | @param mixed $data
    | @param boolean $success
    | @param status $status
    | @return \Illuminate\Http\Response
    |-------------------------------------
    */
    protected function response($data, $success = true, $status = 200){
        return response()->json([
            'status' => $status,
            'success' => $success,
            'message' => $this->message,
            'data' => $data
        ], $status);
    }
    /*
    |-------------------------------------
    | @param mixed $messages
    | @param status $status
    | @return \Illuminate\Http\Response
    |-------------------------------------
    */
    protected function responseMessages($messages, $status = 400){
        return response()->json([
            'status' => $status,
            'success' => false,
            'messages' => $messages,
            'data' => null
        ], $status);
    }

    public function forceResponse($data = [], $status=200){
    	ob_end_clean();
        header("Connection: close\r\n");
        header("Content-Encoding: none\r\n");
        header("Content-Type: application/json\r\n");
        header("HTTP/1.1 $status", true, $status);
        ignore_user_abort(true);
        ob_start();
        echo json_encode($data, JSON_PRETTY_PRINT);
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();
    }
}