<?php
namespace veapon;

class NeteaseMusic
{
	const TYPE_SINGLE = 1;
	const TYPE_ALBUM = 10;
	const TYPE_ARTIST = 100;

	public $server = 'http://music.163.com/api/';

	public function search($keyword, $type = SELF::TYPE_SINGLE)
	{
		$url = $this->server.'search/get/web?csrf_token=';
		$param['s'] = urlencode($keyword);
		$param['type'] = $type;
		$param['offset'] = 0;
		$param['limit'] = 100;
		
		$http = new \Net_Http_Client();

		// Set referer header
		$http->setHeader('Referer', 'http://music.163.com/search/');
		
		$body = $http->post($url, $param)->getBody();
		$res = json_decode($body, true);
		if (!isset($res['code']) || $res['code'] != 200) {
			$msg = isset($res['message']) ? $res['message'] : 'Failed to connect the api server.';
			throw new UnexpectedValueException($msg);
		}

		return $res;
	}

	public function album($id)
	{
		$url = $this->server.'album/'.$id.'/?id='.$id.'&csrf_token=';

		$http = new \Net_Http_Client();

		// Set referer header
		$http->setHeader('Referer', 'http://music.163.com');

		$body = $http->get($url)->getBody();

		$res = json_decode($body, true);
		
		if (!isset($res['code']) || $res['code'] != 200) {
			$msg = isset($res['message']) ? $res['message'] : 'Failed to connect the api server.';
			throw new UnexpectedValueException($msg);
		}

		$artist = array();
		foreach ($res['album']['artists'] as $v) {
			$artist[] = array(
				'id'	=>$v['id'],
				'name'	=>$v['name'],
				'pic'	=>$v['picUrl']
			);	
		}

		$songs = array();
		foreach ($res['album']['songs'] as $v) {
			$songs[] = array(
				'id'		=>$v['id'],
				'name'		=>$v['name'],
				'duration'	=>$v['duration'],
				'mp3'		=>$v['mp3Url']
			);
		}

		// return data
		$return = array(
			'id'		=>$res['album']['id'],
			'name'		=>$res['album']['name'],
			'cover'		=>$res['album']['picUrl'],
			'releaseTime' 	=>$res['album']['publishTime'],
		);
		
		$return['artist'] = $artist;
		$return['songs'] = $songs;
		
		return $return;
	}
}
