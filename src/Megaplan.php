<?php


namespace Zloykolobok\Megaplan_v3;


class Megaplan
{
    protected $key;
    protected $domain;
    protected $header;

    public function __construct(string $key, string $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->header = ['content-type: multipart/form-data'];
    }

    public function send(string $action, string $method = 'GET', array $data = [], array $header = [])
    {
        $headers = $this->header;
        $headers[] = 'AUTHORIZATION: Bearer ' . $this->key;
        foreach ($header as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }

        $url = $this->domain . '/' . $action;

        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_USERAGENT, __CLASS__ );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        if($method === 'POST'){
            $data = json_encode($data);
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 600 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 600);

        $res = curl_exec( $ch );

        $result = json_decode($res);

        if (is_null($result)) {
            throw new \Exception('Что-то пошло не так');
        }

        if ($result->meta->status != 200) {
            throw new \Exception($result->meta->errors[0]->message);
        }

        return $res;
    }

}
