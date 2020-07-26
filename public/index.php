<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$articles = \App\Models\Article::all();

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <title>Документ</title>
</head>
<body>
<div class="uk-container uk-margin-large-top">
    <?php
    var_dump($articles); ?>
</div>
<script src="/js/app.js"></script>
</body>
</html>
