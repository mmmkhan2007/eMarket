<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

/* >-->-->-->  CONNECT PAGE START  <--<--<--< */
require_once(getenv('DOCUMENT_ROOT') . '/model/start.php');
/* ------------------------------------------ */

// ФОРМИРУЕМ ДАННЫЕ ДЛЯ ФАЙЛА КОНФИГУРАЦИИ
$ROOT = getenv('DOCUMENT_ROOT');
$crypt_method = $VALID->inPOST('crypt_method');
$db_family = $VALID->inPOST('database_family');
$db_name = $VALID->inPOST('database_name');
$db_port = $VALID->inPOST('database_port');
$db_pref = $VALID->inPOST('database_prefix');
$db_type = $VALID->inPOST('database_type');
$hash_method = $VALID->inPOST('hash_method');
$http = 'http://' . $VALID->inSERVER('HTTP_HOST') . '/';
$lng = strtolower($VALID->inPOST('language'));
$login_admin = $VALID->inPOST('login_admin');
$login_db = $VALID->inPOST('login_db');
$password_admin = $VALID->inPOST('password_admin');
$password_db = $VALID->inPOST('password_db');
$serv_db = $VALID->inPOST('server_db');
// Данные по таблицам
$tab_admin = $db_pref . 'administrators';
$tab_basic_settings = $db_pref . 'basic_settings';
$tab_categories = $db_pref . 'categories';
$tab_countries = $db_pref . 'countries';
$tab_currencies = $db_pref . 'currencies';
$tab_customers = $db_pref . 'customers';
$tab_customers_activation = $db_pref . 'customers_activation';
$tab_length = $db_pref . 'length';
$tab_manufacturers = $db_pref . 'manufacturers';
$tab_modules = $db_pref . 'modules';
$tab_products = $db_pref . 'products';
$tab_password_recovery = $db_pref . 'password_recovery';
$tab_regions = $db_pref . 'regions';
$tab_taxes = $db_pref . 'taxes';
$tab_template_constructor = $db_pref . 'template_constructor';
$tab_units = $db_pref . 'units';
$tab_vendor_codes = $db_pref . 'vendor_codes';
$tab_weight = $db_pref . 'weight';
$tab_zones = $db_pref . 'zones';
$tab_zones_value = $db_pref . 'zones_value';

// Подготавливаем данные для файла конфигурации
$config = '<?php' . "\n" .
        '  define(\'HTTP_SERVER\', \'' . $http . '\');' . "\n" .
        '  define(\'ROOT\', \'' . $ROOT . '\');' . "\n" .
        '  define(\'DB_SERVER\', \'' . $serv_db . '\');' . "\n" .
        '  define(\'DB_USERNAME\', \'' . $login_db . '\');' . "\n" .
        '  define(\'DB_PASSWORD\', \'' . $password_db . '\');' . "\n" .
        '  define(\'DB_NAME\', \'' . $db_name . '\');' . "\n" .
        '  define(\'DB_FAMILY\', \'' . $db_family . '\');' . "\n" .
        '  define(\'DB_PREFIX\', \'' . $db_pref . '\');' . "\n" .
        '  define(\'DB_PORT\', \'' . $db_port . '\');' . "\n" .
        '  define(\'DB_TYPE\', \'' . $db_type . '\');' . "\n" .
        '  define(\'HASH_METHOD\', \'' . $hash_method . '\');' . "\n" .
        '  define(\'CRYPT_METHOD\', \'' . $crypt_method . '\');' . "\n" .
        '  define(\'DEFAULT_LANGUAGE\', \'' . $lng . '\');' . "\n" .
        '  define(\'TABLE_ADMINISTRATORS\', \'' . $tab_admin . '\');' . "\n" .
        '  define(\'TABLE_BASIC_SETTINGS\', \'' . $tab_basic_settings . '\');' . "\n" .
        '  define(\'TABLE_CATEGORIES\', \'' . $tab_categories . '\');' . "\n" .
        '  define(\'TABLE_COUNTRIES\', \'' . $tab_countries . '\');' . "\n" .
        '  define(\'TABLE_CURRENCIES\', \'' . $tab_currencies . '\');' . "\n" .
        '  define(\'TABLE_CUSTOMERS\', \'' . $tab_customers . '\');' . "\n" .
        '  define(\'TABLE_CUSTOMERS_ACTIVATION\', \'' . $tab_customers_activation . '\');' . "\n" .
        '  define(\'TABLE_LENGTH\', \'' . $tab_length . '\');' . "\n" .
        '  define(\'TABLE_MANUFACTURERS\', \'' . $tab_manufacturers . '\');' . "\n" .
        '  define(\'TABLE_MODULES\', \'' . $tab_modules . '\');' . "\n" .
        '  define(\'TABLE_PRODUCTS\', \'' . $tab_products . '\');' . "\n" .
        '  define(\'TABLE_PASSWORD_RECOVERY\', \'' . $tab_password_recovery . '\');' . "\n" .
        '  define(\'TABLE_REGIONS\', \'' . $tab_regions . '\');' . "\n" .
        '  define(\'TABLE_TAXES\', \'' . $tab_taxes . '\');' . "\n" .
        '  define(\'TABLE_TEMPLATE_CONSTRUCTOR\', \'' . $tab_template_constructor . '\');' . "\n" .
        '  define(\'TABLE_UNITS\', \'' . $tab_units . '\');' . "\n" .
        '  define(\'TABLE_VENDOR_CODES\', \'' . $tab_vendor_codes . '\');' . "\n" .
        '  define(\'TABLE_WEIGHT\', \'' . $tab_weight . '\');' . "\n" .
        '  define(\'TABLE_ZONES\', \'' . $tab_zones . '\');' . "\n" .
        '  define(\'TABLE_ZONES_VALUE\', \'' . $tab_zones_value . '\');' . "\n" .
        '?>';

// Создаем файл конфигурации и записываем в него данные
$fpd = fopen($ROOT . '/model/configure/configure.php', 'w+');
fputs($fpd, $config);
fclose($fpd);

// Если есть файл конфигурации, то ставим на него права 644
if (file_exists($ROOT . '/model/configure/configure.php')) {
    chmod($ROOT . '/model/configure/configure.php', 0644);
} else {
// Если файла конфигурации нет или он недоступен, то переадресуем на страницу ошибки
    header('Location: /controller/install/error.php?file_configure_not_found=true');
}

// Подключаем CONFIGURE.PHP
require_once($ROOT . '/model/configure/configure.php');

// Подключаем файл БД
if ($db_type == 'mysql') {
    $file_name = ROOT . '/model/databases/mysql.sql';
}

// Если файла нет, то
if (!file_exists($file_name)) {
    // Если отсутствует файл БД, то переадресуем на страницу ошибки
    header('Location: /controller/install/error.php?file_not_found=true');
}

// Устанавливаем префикс БД
$buffer = str_replace('emkt_', DB_PREFIX, implode(file($file_name)));

//Устанавливаем семейство БД
if ($db_family == 'myisam') {
    $buffer = str_replace('ENGINE=InnoDB', 'ENGINE=MyISAM', $buffer);
}

$PDO->getExec($buffer);

$password_admin_hash = $AUTORIZE->passwordHash($password_admin);

if ($VALID->inPOST('login_admin') && $VALID->inPOST('password_admin')) {
    $PDO->inPrepare("INSERT INTO " . TABLE_ADMINISTRATORS . "  SET login=?, password=?, permission=?, language=?", [$login_admin, $password_admin_hash, 'admin', $lng]);
}

// СОЗДАЕМ .HTACCESS
$text = "#****** Copyright © 2018 eMarket ******#
#   GNU GENERAL PUBLIC LICENSE v.3.0   #
# https://github.com/musicman3/eMarket #
#**************************************#

RewriteEngine On
#Redirect
RewriteCond %{DOCUMENT_ROOT}/controller/catalog/$1 -d
RewriteRule ^(.*)$ controller/catalog/$1 [L,QSA]
RewriteCond %{DOCUMENT_ROOT}/controller/catalog/$1 -f
RewriteRule ^(.*)$ controller/catalog/$1 [L,QSA]";

// открываем файл
$fp = fopen(ROOT . '/.htaccess', "w+");
// записываем в файл текст
fwrite($fp, $text);
fclose($fp);

// Если есть файл .htaccess, то ставим на него права 644
if (file_exists(ROOT . '/.htaccess')) {
    chmod(ROOT . '/.htaccess', 0644);
}

/* ->-->-->-->  CONNECT PAGE END  <--<--<--<- */
require_once(getenv('DOCUMENT_ROOT') . '/model/end.php');
/* ------------------------------------------ */
?>
