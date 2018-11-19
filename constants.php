<?php

if (!defined('SOCILEN_API_BASE_URI'))
	define("SOCILEN_API_BASE_URI", 'api_uri/api_version/'); //IMPORTANT: Put a slash (/) after api version

if (!defined('SOCILEN_API_USER'))
	define("SOCILEN_API_USER", 'api_user');

if (!defined('SOCILEN_API_PASSWORD'))
	define("SOCILEN_API_PASSWORD", 'api_password');

//SSL Verify, only nedded on dev environments
//if (!defined('SOCILEN_API_VERIFY_SSL'))
//	define("SOCILEN_API_VERIFY_SSL", false);