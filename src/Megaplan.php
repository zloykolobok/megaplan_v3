<?php

namespace Zloykolobok\Megaplan_v3;

class Megaplan
{
	protected $key;
	protected $domain;
	protected $header;

	/**
	 * Megaplan constructor.
	 *
	 * @param string $key
	 * @param string $domain
	 */
	public function __construct( $key, $domain )
	{
		$this->key    = $key;
		$this->domain = $domain;
	}

	/**
	 * @param string $action
	 * @param string $method
	 * @param array  $data
	 * @param array  $header
	 *
	 * @return bool|string
	 * @throws \Exception
	 */
	public function send( $action, $method = 'GET', $data = [], $header = [] )
	{
		$headers   = $this->header;
		$headers[] = 'AUTHORIZATION: Bearer ' . $this->key;
		foreach ( $header as $key => $value )
		{
			$headers[] = $key . ': ' . $value;
		}

		$url = $this->domain . '/' . $action;

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_USERAGENT, __CLASS__ );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		if ( $method === 'POST' )
		{
			$data = json_encode( $data );
			curl_setopt( $ch, CURLOPT_POST, TRUE );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		}

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 600 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 600 );

		$res = curl_exec( $ch );

		$result = json_decode( $res );

		if ( is_null( $result ) )
		{
			throw new \Exception( 'Что-то пошло не так' );
		}

		if ( $result->meta->status != 200 )
		{
			throw new \Exception( $result->meta->errors[0]->message );
		}

		return $res;
	}

	/**
	 * @param string $pathToFile
	 *
	 * @return bool|string
	 * @throws \Exception
	 */
	public function sendFile( $pathToFile )
	{
		$headers   = $this->header;
		$headers[] = 'AUTHORIZATION: Bearer ' . $this->key;
		$url       = $this->domain . '/api/file';

		$data = [ 'files[]' => new \CURLFile( $pathToFile ) ];

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_USERAGENT, __CLASS__ );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 600 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 600 );

		$res = curl_exec( $ch );

		$result = json_decode( $res );

		if ( is_null( $result ) )
		{
			throw new \Exception( 'Что-то пошло не так' );
		}

		if ( $result->meta->status != 200 )
		{
			throw new \Exception( $result->meta->errors[0]->message );
		}

		return $res;
	}
}
