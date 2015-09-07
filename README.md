DPFields
=============
This is the official DPFields Github repository. DPFields is a Joomla extension suite which adds custom fields for Articles, Users, Categories, Modules, DPCalendar events or DPCases cases. But it's main purpose is to serve Joomla extension developers a custom fields feature with minimal code changes.

PREREQUISITS
------------
- Joomla 3.4 and above
- mysql >= 5.0.0
- PHP >= 5.3.0

INSTALLATION
------------
Just install the downloaded zip file trough the Joomla extension manager and make sure the plugins are
enabled.

INTEGRATION
------------
If you are an extension developer you just need a couple of small requirements to support custom fields
in your component. The following list outlines the steps needed:

1. Add a sidebar link, see commit [44aca7838c564b5188dffd8362044d74bfcfe75e](https://github.com/Digital-Peak/Joomla-3.2-Hello-World-Component/commit/44aca7838c564b5188dffd8362044d74bfcfe75e) on the Hello World example
2. Make sure the same context is loaded in the form and the save events
3. Trigger the onContentBeforeDisplay event in your view, see commit [31bf7abc8cd843ee596ac2f70193c002456f0e59](https://github.com/Digital-Peak/Joomla-3.2-Hello-World-Component/commit/31bf7abc8cd843ee596ac2f70193c002456f0e59)

More information can be found in the [developer documentation](https://joomla.digital-peak.com/documentation/162-dpfields/2756-developer).

UPGRADE
-------
To upgrade DPFields from an older version just install the downloaded zip file trough the Joomla
extension manager or the built in update manager.

DOCUMENTATION
-------------
Check https://joomla.digital-peak.com/documentation/162-dpfields for more information.


Have fun
The Digital Peak team