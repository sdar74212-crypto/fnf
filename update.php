<?php
// Radio Portal Update Tool (v1.0-v1.1 to v1.2)
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$index_file = file_get_contents('index.php');
preg_match('/\$lapp_folder = \'(.*)\';/iUs', $index_file, $autoload);

$lapp_folder = __DIR__.'/lapp/';

define('LARAVEL_START', microtime(true));
require "$lapp_folder/vendor/autoload.php";
$app = require_once "$lapp_folder/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$method = $_SERVER['REQUEST_METHOD'];
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Radio Portal Update Tool (v1.0-v1.1 to v1.2)</title>
</head>

<body>

    <div class="container py-5">
        <h1>Radio Portal Update Tool (v1.0-v1.1 to v1.2)</h1>

        <?php if ($method == 'GET') {?>

        <form action="update.php" method="POST">
            <div class="mb-3">
                <ul>
                    <li>Customizations I make in system files and theme files may be overwritten.</li>
                    <li>I backed up the files and database.</li>
                    <li>I will delete this file after successfully completing the update.</li>
                </ul>
                <p>I approve of all this, and I want to continue.</p>

                <button type="submit" class="btn btn-success">Update</button>
        </form>

        <?php }?>

        <?php

if ($method == 'POST') {

    if (env('APP_VERSION') == '1.2') {
        echo '<div class="alert alert-warning mt-3" role="alert">
  The update has already been done.
</div>';
        exit;
    }

    $site_settings = DB::table('settings')->get();

    foreach ($site_settings as $setting) {
        $settings[$setting->name] = $setting->value;
    }

    $all_languages = DB::table('translations')->get();

    try {
        if (!file_exists('images/submissions')) {
                mkdir('images/submissions', 0777, true);
            }
            
        if (!file_exists('temp/css')) {

            file_put_contents("update.zip", file_get_contents("https://members.foxart.co/rp_1_2/update.zip?11"));
            $zip = new ZipArchive;
            $zip->open('update.zip');
            $zip->extractTo(__DIR__ . "/temp");
            $zip->close();

            unlink('update.zip');

            if (!file_exists('temp')) {
                mkdir('temp', 0777, true);
            }

            if (!file_exists('temp/backup')) {
                mkdir('temp/backup', 0777, true);
            }

            rename($lapp_folder, __DIR__ . "/temp/backup/lapp");
            rename(__DIR__ . "/temp/lapp", $lapp_folder);
            
            if (!file_exists(__DIR__ . '/assets/js/video.js')) {
            rename(__DIR__ . "/temp/video.js", __DIR__ . "/assets/js/video.js");
            }
                        
            if (!file_exists(__DIR__ . '/assets/css/bootstrap-select.min.csss')) {
            rename(__DIR__ . "/temp/bootstrap-select.min.css", __DIR__ . "/assets/css/bootstrap-select.min.css");
            }
                        
            if (!file_exists(__DIR__ . '/assets/js/bootstrap-select.min.js')) {
            rename(__DIR__ . "/temp/bootstrap-select.min.js", __DIR__ . "/assets/js/bootstrap-select.min.js");
            }
            
            rename(__DIR__ . "/assets/css/app.css", __DIR__ . "/temp/backup/time() . '_app.css'");
            rename(__DIR__ . "/temp/app.css", __DIR__ . "/assets/css/app.css");
            
            rename(__DIR__ . "/assets/js/other.js", __DIR__ . "/temp/backup/time() . '_other.js'");
            rename(__DIR__ . "/temp/other.js", __DIR__ . "/assets/js/other.js");
            
            rename(__DIR__ . "/assets/js/scripts.js", __DIR__ . "/temp/backup/time() . '_scripts.js'");
            rename(__DIR__ . "/temp/scripts.js", __DIR__ . "/assets/js/scripts.js");

        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert"><b>Error</b><br><br>';
        echo $e->getMessage();
        echo "<br><br><b>An error has occurred please create a support request by clicking <a href=\"mailto:support@foxart.co\">here</a>.</b></div>";
        exit;
    }

    try {

        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'contact_slug', 'contact-us' FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'contact_slug');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'contact_title', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'contact_title');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'contact_description', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'contact_description');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'contact_h1_title', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'contact_h1_title');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'faq_slug', 'faq' FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'faq_slug');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'faq_title', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'faq_title');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'faq_description', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'faq_description');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'faq_h1_title', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'faq_h1_title');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'enable_faq', '1' FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'enable_faq');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'radio_submission', '1' FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'radio_submission');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'submission_title', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'submission_title');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'submission_description', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'submission_description');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'submission_h1_title', null FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'submission_h1_title');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'submission_slug', 'submission' FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'submission_slug');");
        DB::update("INSERT INTO `settings` (`name`, `value`) SELECT 'api_source', 'de1' FROM dual WHERE NOT EXISTS (SELECT * FROM settings WHERE name = 'api_source');");

        if (!Schema::hasColumn('stations', 'details')) {
            DB::update("ALTER TABLE stations ADD details text NULL AFTER description;");
            echo "details column in stations table created successfully.<br />";
        }

if (!Schema::hasTable('comments')) {
    DB::update("CREATE TABLE `comments` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `content_id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text DEFAULT NULL,
  `approval` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

    DB::update("ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);");
    DB::update("ALTER TABLE `comments`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;");

    echo "comments table created successfully.<br />";
}

if (!Schema::hasTable('faqs')) {
    DB::update("CREATE TABLE `faqs` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` longtext NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

    DB::update("ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);");
    DB::update("ALTER TABLE `faqs`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;");

    echo "faqs table created successfully.<br />";
}

if (!Schema::hasTable('submissions')) {
    DB::update("CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` varchar(755) NOT NULL,
  `details` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stream_url` varchar(1255) DEFAULT NULL,
  `genres` varchar(255) NOT NULL,
  `countries` varchar(255) DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

    DB::update("ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);");
    DB::update("ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

    echo "submissions table created successfully.<br />";
}

    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert"><b>Error</b><br><br>';
        echo $e->getMessage();
        echo "<br><br><b>An error has occurred please create a support request by clicking <a href=\"mailto:support@foxart.co\">here</a>.</b></div>";
        exit;
    }
    
    
    try {

        $app_name = env('APP_NAME');
        $app_key = env('APP_KEY');
        $app_url = env('APP_URL');

        $db_connection = env('DB_CONNECTION');
        $db_host = env('DB_HOST');
        $db_port = env('DB_PORT');
        $db_database = env('DB_DATABASE');
        $db_username = env('DB_USERNAME');
        $db_password = env('DB_PASSWORD');

        $mail_driver = env('MAIL_DRIVER');
        $mail_host = env('MAIL_HOST');
        $mail_port = env('MAIL_PORT');
        $mail_username = env('MAIL_USERNAME');
        $mail_password = env('MAIL_PASSWORD');
        $mail_encryption = env('MAIL_ENCRYPTION');
        $mail_from_address = env('MAIL_FROM_ADDRESS');
        $mail_from_name = env('MAIL_FROM_NAME');

        $admin_url = env('ADMIN_URL');
        $admin_login_url = env('ADMIN_LOGIN_URL');

        $env_new = "APP_NAME=\"Radio Portal\"
APP_ENV=local
APP_KEY=$app_key
APP_DEBUG=false
APP_URL=$app_url
ADMIN_URL=\"$admin_url\"
ADMIN_LOGIN_URL=\"$admin_login_url\"
APP_VERSION=\"1.2\"

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=$db_host
DB_PORT=$db_port
DB_DATABASE=\"$db_database\"
DB_USERNAME=\"$db_username\"
DB_PASSWORD=\"$db_password\"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=$mail_driver
MAIL_HOST=$mail_host
MAIL_PORT=$mail_port
MAIL_USERNAME=\"$mail_username\"
MAIL_PASSWORD=\"$mail_password\"
MAIL_ENCRYPTION=$mail_encryption
MAIL_FROM_ADDRESS=\"$mail_from_address\"
MAIL_FROM_NAME=\"$mail_from_name\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
MIX_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"
";

        $env_file_path = $lapp_folder . "/.env";
        file_put_contents($env_file_path, $env_new);
        
          foreach ($all_languages as $language) {

            if (!file_exists("/$lapp_folder/resources/lang/$language->code/")) {
                mkdir("/$lapp_folder/resources/lang/$language->code/", 0777, true);
            }

            // Frontend
            
            if (!file_exists("/$lapp_folder/resources/lang/$language->code/general.php")) {
            rename(__DIR__ . "/temp/backup/lapp/resources/lang/$language->code/general.php", "/$lapp_folder/resources/lang/$language->code/general.php");
            }

            $language_file_path = "/$lapp_folder/resources/lang/$language->code/general.php";

            $a = \File::getRequire("/$lapp_folder" . '/resources/lang/' . $language->code . '/general.php');
            $b = \File::getRequire('temp/general.php');

            foreach ($a as $key => $value) {
                $value = addslashes($value);
                $translation[$key] = $value;
            }

            $add_variable = '';

            foreach ($b as $b_key => $b_value) {
                $value = addslashes($b_value);

                if ($b_key != 'translation_type') {
                    if (isset($translation[$b_key])) {

                        $add_variable .= "'$b_key' => '$translation[$b_key]',\n";

                    } else {

                        $add_variable .= "'$b_key' => '$b_value',\n";

                    }
                }
            }

            $add_pre = '
<?php
return [
\'translation_type\' => \'2\',
';

            $add_pre_admin = '
<?php
return [
\'translation_type\' => \'3\',
';

            $add_after = '];';

            file_put_contents($language_file_path, $add_pre . $add_variable . $add_after);

            // Admin

            if (!file_exists("/$lapp_folder/resources/lang/$language->code/admin.php")) {
            rename(__DIR__ . "/temp/backup/lapp/resources/lang/$language->code/admin.php", "/$lapp_folder/resources/lang/$language->code/admin.php");
            }

            $language_file_path_admin = "/$lapp_folder/resources/lang/$language->code/admin.php";

            $a = \File::getRequire("/$lapp_folder" . '/resources/lang/' . $language->code . '/admin.php');
            $b = \File::getRequire('temp/admin.php');

            foreach ($a as $key => $value) {
                $value = addslashes($value);
                $translation[$key] = $value;
            }

            $add_variable_admin = '';

            foreach ($b as $b_key => $b_value) {
                $value = addslashes($b_value);

                if ($b_key != 'translation_type') {
                    if (isset($translation[$b_key])) {

                        $add_variable_admin .= "'$b_key' => '$translation[$b_key]',\n";

                    } else {

                        $add_variable_admin .= "'$b_key' => '$b_value',\n";

                    }
                }
            }

            file_put_contents($language_file_path_admin, $add_pre_admin . $add_variable_admin . $add_after);

        }

    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert"><b>Error</b><br><br>';
        echo $e->getMessage();
        echo "<br><br><b>An error has occurred please create a support request by clicking <a href=\"mailto:support@foxart.co\">here</a>.</b></div>";
        exit;
    }

    echo '<div class="alert alert-success mt-3" role="alert">
  Update completed successfully!
</div>';

}
?>

    </div>

</body>

</html>