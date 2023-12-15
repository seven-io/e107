<img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />


# Official [seven](https://www.seven.io) extension for [e107](https://e107.org/)

This plugin works with e107's notification system and adds the possibility to send SMS to your customers
via [seven](https://www.seven.io).

## Prerequisites

- An [API key](https://help.seven.io/en/api-key-access) from [seven](https://www.seven.io) - can be
  created in your [developer dashboard](https://app.seven.io/developer)
- [e107](https://e107.org/) - tested with v2.3.2

## Installation

1. Download
   the [latest release](https://github.com/seven-io/e107/releases/latest/download/seven-e107-latest.zip)
2. Extract the archive to `/path/to/e107/e107_plugins/`
3. Head to `Manage->Plugin Manager->Not installed` in your administration panel and enable the `seven` plugin
4. Go to `Plugin Manager->Installed`, open the settings page and enter
   your [API key](https://help.seven.io/en/api-key-access)

## Usage

You can start by going to the `Test page` and compose an SMS to see if it all works.

### Notification system

You can programmatically send SMS by triggering the e107 notification system for SMS dispatch.

```php
e107::getEvent()->trigger(
'system_send_sms',
[
   'message' => 'Your message',
   'to' => '+1-234-567-890',
]);
```

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
