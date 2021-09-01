<?php
if(!defined('e107_INIT'))
{
	exit;
}


class seven_event
{

	function config()
	{

		$event = [];

		if(e107::pref('seven', 'active', false))
		{
			$event[] = [
				'function' => 'sendSMS',
				'name'     => "system_send_sms",
			];
		}

		return $event;
	}

	/**
	 * @param array $data
	 * @param string|null $event
	 * @return array
	 * @noinspection PhpUnused
	 */
	function sendSMS($data, $event = null)
	{

		$pref = e107::pref('seven');

		$apiKey = $pref['api_key']; // Your API key from app.seven.io/developer
		$to = $data['to'];

		if(empty($apiKey))
		{
			e107::getMessage()->addError("API key is empty");

			return false;
		}

		if(empty($to))
		{
			e107::getMessage()->addError('To number is empty');

			return false;
		}

		$result = $this->sevenCreateSMS($to, $pref['from'], $data['message'], $apiKey);

		$type = 100 == $result['success'] ? E_LOG_INFORMATIVE : E_LOG_WARNING;

		e107::getLog()->addArray($result)->save('SEVEN_EVENT_SMS', $type);

		return $result;
	}


	/**
	 * @param string $to
	 * @param string $from
	 * @param string $text
	 * @param string $apiKey
	 * @return array
	 */
	private function sevenCreateSMS($to, $from, $text, $apiKey)
	{

		$url = 'https://gateway.seven.io/api/sms';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'X-Api-Key:' . $apiKey
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
			'to=' . rawurlencode($to) .
			'&from=' . rawurlencode($from) .
			'&text=' . rawurlencode($text) .
			'&json=1');

		$resp = curl_exec($ch);
		curl_close($ch);

		return json_decode($resp, true);

	}


}