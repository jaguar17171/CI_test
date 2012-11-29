<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=@$header['title'];?></title>
    <meta name="description" content="<?=@$header['description'];?>" />
    <meta name="keywords" content="" />
    <meta charset="utf-8">
    <meta name="author" content="" />
    <link rel='index' title='<?= SITE_TITLE ?>' href='<?=base_url();?>' />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- stylesheets -->
    <?php foreach ($header['files']['css'] as $css_file): ?>
    <link rel="stylesheet" href="<?=$css_file;?>" type="text/css" media="all" />
    <?php endforeach; ?>

    <!-- set global js variables -->
    <script type="text/javascript">
    <?php
    // set the JS variables
    foreach ($js_vars as $var => $value)
    {
        // normal variable, not array
        if (! is_array($value))
            echo "var $var = " . (is_numeric($value)?$value:"\"$value\"") . ";\n" ;
        // if variable was an associated array
        else
        {
            echo "var $var = {";
            foreach ($value as $key => $key_value)
            {
                $key = is_numeric($key) ? $key : "\"$key\"";
                $key_value = is_numeric($key_value) ? $key_value : "\"$key_value\"";
                echo "$key:$key_value" . (next($value)?", ":"");
            }
            echo "};\n";
        };
    };
    ?>
    </script>

    <!-- jss -->
    <?php foreach ($header['files']['js'] as $js_file): ?>
    <script type="text/javascript" src="<?=$js_file;?>" ></script>
    <?php endforeach; ?>
</head>
<body>
    <?php require_once(APPPATH."views/{$_theme}/_navbar.php");?>