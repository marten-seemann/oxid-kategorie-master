<?php
// specify the path where your OXID shop installation is located
$config['oxid_basedir'] = '../';

// specify the default language to use
// note that the language can be changed in the Category Master itself, this is only the language that will be used on first startup
$config['language'] = 'de'; // possibilities: de, en
// interval to update the category assignments, in s
// if you want to disable this updating feature, set to a large value, e.g. 3600
$config['category_update_interval'] = 3600;

// path to your installation of the Category Admin
$config['category_admin_path'] = '../kategorie-admin/';
