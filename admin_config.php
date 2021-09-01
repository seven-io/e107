<?php
require_once '../../class2.php';

if(!getperms('P'))
{
	e107::redirect('admin');
	exit;
}


// e107::lan('seven',true);

class seven_adminArea extends e_admin_dispatcher
{

	protected $adminMenu = [
		'main/prefs' => [
			'caption' => LAN_PREFS,
			'perm'    => 'P',
		],
		'main/test'  => [
			'caption' => 'Test',
			'perm'    => 'P',],
	];

	protected $adminMenuAliases = [
		'main/edit' => 'main/list',
	];

	protected $menuTitle = 'seven';

	protected $modes = [
		'main' => [
			'controller' => 'seven_ui',
			'path'       => null,
			'ui'         => 'seven_form_ui',
			'uipath'     => null,
		],

	];
}


class seven_ui extends e_admin_ui
{

	protected $batchCopy = true;
	protected $batchDelete = true;
	protected $batchExport = true;
	// protected $eventName		= 'seven-'; // remove comment to enable event triggers in admin.
	protected $fieldpref = [];
	protected $fields = [];
	protected $listOrder = ' DESC';
	protected $perPage = 10;
	protected $pid = '';
	protected $pluginName = 'seven';
	protected $pluginTitle = 'seven';
	protected $prefs = [
		'active'  => [
			'data'       => 'str',
			'help'       => '',
			'tab'        => 0,
			'title'      => 'Active',
			'type'       => 'boolean',
			'writeParms' => [],
		],
		'api_key' => [
			'data'       => 'str',
			'help'       => 'Your seven API key which you can find in your developer dashboard',
			'tab'        => 0,
			'title'      => 'API key',
			'type'       => 'text',
			'required'   => true,
			'writeParms' => [
				'size' => 'xxlarge',
			]
		],
		'from'    => [
			'data'       => 'str',
			'help'       => 'Optional sender identifier - maximum 11 alphanumeric or 16 numeric characters - country specific restrictions may apply',
			'tab'        => 0,
			'title'      => 'From',
			'type'       => 'text',
			'writeParms' => [
				'placeholder' => '+1-234-567-890',
			]
		],
	];
	protected $table = '';

	public function init()
	{

		if(!e107::isInstalled('seven')) // This code may be removed once plugin development is complete.
		{
			e107::getMessage()
				->addWarning("Plugin is not yet installed. Saving and loading of preference or table data will fail.");
		}
	}

	public function beforeCreate($new_data, $old_data)
	{

		return $new_data;
	}

	public function afterCreate($new_data, $old_data, $id) { }

	public function onCreateError($new_data, $old_data) { }

	public function beforeUpdate($new_data, $old_data, $id)
	{

		return $new_data;
	}

	public function afterUpdate($new_data, $old_data, $id) { }

	public function onUpdateError($new_data, $old_data, $id) { }

	public function renderHelp() // left-panel help menu area. (replaces e_help.php used in old plugins)
	{

		$text = "<p>This plugins adds <a href='https://www.seven.io/' target='_blank' title='Visit seven website'>seven</a> SMS capabilities to e107.</p>
				<p>After saving your <a href='https://help.seven.io/en/api-key-access' target='_blank' title='How to find your seven API key'>API key</a>, as found in your seven dashboard, use the <b>Test page</b> to send yourself an SMS to check that is it functioning correctly.</p>
				<p>You can also choose to have <a href='" . e_ADMIN . "notify.php'>system notifications</a> sent to you by SMS if you wish. </p>
				<p>Plugin developers may send an SMS message using the following code example:</p>
				<pre>e107::getEvent()->trigger(<br />'system_send_sms',<br />[<br />&nbsp;&nbsp;&nbsp;'to' => '+1-234-567-890',<br />&nbsp;&nbsp;&nbsp;'message' => 'Your message'<br />]);</pre>
";

		return [
			'caption' => LAN_HELP,
			'text'    => $text,
		];
	}

	public function testPage()
	{

		if(!empty($_POST['testSeven']) && !empty($_POST['to']) && !empty($_POST['message']))
		{
			if($result = e107::getEvent()->trigger('system_send_sms', $_POST))
			{
				e107::getMessage()->addInfo('SENT: ' . print_a($result, true));
			}
			else
			{
				e107::getMessage()->addError('There was a problem. Check the number and make sure it begins with <b>+</b>.');
			}

		}

		$frm = $this->getUI();

		$text = $frm->open('testPage');
		$text .= "<table class='table table-bordered' style='width:auto'><tr><td>";
		$text .= $frm->text('to', varset($_POST['to']), 80, ['placeholder' => '+1-234-567-890']);
		$text .= '</td><tr><td>';
		$text .= $frm->textarea('message', 'Hello from e107!', 6);
		$text .= '</td></tr></table>';
		$text .= $frm->submit('testSeven', 'Send Test SMS');
		$text .= $frm->close();

		return $text;
	}

}


class seven_form_ui extends e_admin_form_ui
{

	function apikey($curVal, $mode)
	{

		if($mode == 'write') // Edit Page
		{
			return $this->text('api_key', $curVal, 255, 'size=large');
		}

		return null;
	}
}


new seven_adminArea;

require_once e_ADMIN . 'auth.php';
e107::getAdminUI()->runPage();

require_once e_ADMIN . 'footer.php';
exit;
