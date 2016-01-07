<?php

$storage_dir = __DIR__;

$alert_errors = false;
$output_errors = true;

include './config.php';

foreach ($config as $k => $v) {

    foreach ($v['files'] as $f) {

        if (!file_exists($f)) {

            if ($alert_errors) {
                slack($v['config'], $f, "File $f does not exists");
            }

            if ($output_errors) {
                echo "File $f does not exists\n";
            }

            continue;
        }

        $seek = 0;

        $seek_file_path = $storage_dir . '/' . $k . '_' . md5($f) . '.seek';

        try {

            if (file_exists($seek_file_path)) {
                $seek = file_get_contents($seek_file_path);
            }

            $fp = fopen($f, 'r');

            fseek($fp, $seek);

            $l = fread($fp, 2056); // No more than 2046 b, security

            if (strlen($l) == 2056) {
                $l .= "\n\nDisplaying just 2056 bytes";

                // find the size of the file
                $seek = filesize($f);

            } else {
                $seek += strlen($l);
            }

            fclose($fp);

            file_put_contents($seek_file_path, $seek);

            if ($l !== '') {
                slack($v['config'], $f, $l);
            }

        } catch (Exception $e) {

            if ($alert_errors) {
                slack($v['config'], $f, $e->getMessage());
            }

            if ($output_errors) {
                echo $e->getMessage() . "\n";
            }
        }

    }

}

function slack($config, $file, $msg)
{

    $code = '```';

    $data = "payload=" . json_encode($config+['text' => "$file\n$code$msg$code"]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $config['url']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_exec($ch);

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
        throw new Exception("Got code " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . " from Slack. File $file");
    }

    curl_close($ch);

}
