> :warning: **Use this with care — provide access only to trusted user roles**. This bridge connects Joomla! & WordPress admin to your CiviCRM database, potentially allowing a CMS user without CiviCRM admin priviliges to publish your full Civi Contact or Event database. Do not use this on live sites without testing and care over who has access to the YooThemeAdmin.

# Yootheme CiviCRM Bridge

This is a bridge of [YooThemePro (YTP) Page Builder](https://yootheme.com/page-builder) with [CiviCRM](https://civicrm.org). It provides a front-end, 'drag and drop' layout builder for CiviCRM Entities, such as Contacts and Events, which are loaded as Dynamic Content [custom sources](https://yootheme.com/support/yootheme-pro/joomla/developers-sources) into YTP. It uses CiviCRM API3, and integrates with Dynaimic Content features including filtering and sorting by field, filtering by group and content type, conditional display of fields based on any other field value, date formating & image URLs.

It is developed by [Deepak Srivastava](https://github.com/deepak-srivastava/) of [Mountev](https://mountev.co.uk/), with the support of [Joshua Gowans](https://lab.civicrm.org/josh) and [Nicol](https://lab.civicrm.org/nicol) Wistreich ([Vingle](https://github.com/vingle)).

## Installation

1. Install YooThemePro theme (WordPress) / template (Joomla!)

2. Install this Child Theme

3. Activate & enable

4. Follow YTP's [documentation for Dynamic Content](https://yootheme.com/support/yootheme-pro/joomla/dynamic-content), selecting the relevent CiviCRM entites as Custom Sources

> **It is recommended to use this only on test/dev sites until you are comfortable with, and understand, how it works**
