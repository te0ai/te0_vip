<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Language" content="ja">
  <meta name="google" content="notranslate">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="apple-touch-icon" href="<?php h(_HOME_) ?>img/apple-touch-icon.png">
  <link type="image/x-icon" rel="shortcut icon" href="<?php h(_HOME_) ?>img/favicon.png">
  <title><?php h(Html::title()); ?> | <?php h(_TITLE_) ?></title>
  <meta name="description" content="<?php h(Html::title()); ?>のページ">
  <meta name="keywords" content="テレ,tere,work,system">
  <meta property="og:title" content="<?php h(Html::title()); ?> | <?php h(_TITLE_) ?>">
  <meta property="og:type" content="website">
  <meta property="og:description" content="<?php h(Html::title()); ?>のページ">
  <meta property="og:url" content="<?php h(_HOME_) ?>">
  <meta property="og:image" content="<?php h(_HOME_) ?>img/logo_ogp.png">
  <meta property="og:site_name" content="<?php h(Html::title()); ?> | <?php h(_TITLE_) ?>">
  <meta property="og:email" content="te0@te0.jp">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@te0jp">
  <meta name="twitter:creator" content="@te0jp">
  <meta name="twitter:title" content="<?php h(Html::title()); ?> | <?php h(_TITLE_) ?>">
  <meta name="twitter:description" content="<?php h(Html::title()); ?>のページ">
  <meta name="twitter:url" content="<?php h(_HOME_) ?>">
  <meta name="twitter:image:src" content="<?php h(_HOME_) ?>img/logo_ogp.png">
  <rdf:rdf xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:foaf="http://xmlns.com/foaf/0.1/">
    <rdf:description rdf:about="<?php h(_HOME_) ?>">
      <foaf:maker rdf:parsetype="Resource">
        <foaf:holdsaccount>
          <foaf:onlineaccount foaf:accountname="te0jp">
            <foaf:accountservicehomepage rdf:resource="http://www.hatena.ne.jp/"></foaf:accountservicehomepage>
          </foaf:onlineaccount>
        </foaf:holdsaccount>
      </foaf:maker>
    </rdf:description>
  </rdf:rdf>
  <?php Html::redirect(); //リダイレクト 
  ?>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.11.2/css/all.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.2.1/font-awesome-animation.min.css">
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <!-- bootstrap 4.5.3 -->
  <?php Html::css('portal/portal'); ?>
  <?php Html::css('common'); ?>
  <?php Html::css(); ?>
  <?php Html::addCss(); ?>
  <style>
    <?php Html::fetch('css'); ?>
  </style>
</head>

<body class="app app-login p-0">
  
  <?php Session::flash(); ?>
  <?php self::contents(); ?>

  <?php Html::configScript(); ?>
  <?php Html::script('bootstrap/bootstrap.min'); ?>
  <?php Html::script('bootstrap/popper.min'); ?>
  <?php Html::script('fontawasome/all.min'); ?>
  <?php Html::script('specific/app'); ?>
  <?php Html::script('core'); ?>
  <?php Html::script('common'); ?>
  <?php Html::addScript(); ?>
  <?php Html::script(); ?>
  <script>
    <?php Html::fetch('js'); ?>
  </script>


</body>

</html>