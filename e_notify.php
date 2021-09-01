<?php
if(!defined('e107_INIT'))
{
	exit;
}


// e107::lan('_blank','notify',true);

class seven_notify extends notify // v2.x Standard
{

	function router()
	{

		return [
			'sms' => [
				'category' => '',
				'field'    => 'phone',
				'label'    => 'SMS',
			]
		];
	}


	function phone($name, $curVal)
	{

		return e107::getForm()->text($name, $curVal, 80, [
			'placeholder' => '+1-234-567-890',
			'size'        => 'large',
		]);
	}


	function sms($data = [])
	{

		if(!empty($data['recipient']))
		{
			$to = $data['recipient'];
		}

		if(!empty($data['to']))
		{
			$to = $data['to'];
		}

		$ev = [
			'message' => strip_tags(str_replace('<br />', "\n", $data['message'])),
			'to'      => $to,
		];

		return e107::getEvent()->trigger('system_send_sms', $ev);
	}
}


