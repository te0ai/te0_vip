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

<body class="app">
  <header class="app-header fixed-top">
    <div class="app-header-inner">
      <div class="container-fluid py-2">
        <div class="app-header-content">
          <div class="row justify-content-between align-items-center">

            <div class="col-auto">
              <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                  <title>Menu</title>
                  <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                </svg>
              </a>
            </div><!--//col-->
            <div class="app-utilities col-auto">
              <div class="app-utility-item">
                <a href="<?php h(_HOME_); ?>login/exit/" title="Settings">
                  <!-- Bootstrap Icons: https://icons.getbootstrap.com/ -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                    <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
                    <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117M11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5M4 1.934V15h6V1.077z" />
                  </svg>
                </a>
              </div>
            </div>


          </div><!--//row-->
        </div><!--//app-header-content-->
      </div><!--//container-fluid-->
    </div><!--//app-header-inner-->
    <div id="app-sidepanel" class="app-sidepanel">
      <div id="sidepanel-drop" class="sidepanel-drop"></div>
      <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <div class="app-branding">
          <img class="me-2" src="<?php h(_HOME_) ?>img/common/logo.webp" alt="logo" style="height:50px;">
        </div><!--//app-branding-->

        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
          <ul class="app-menu list-unstyled accordion" id="menu-accordion">
            <?php if (isset($menu)): ?>
              <?php foreach ($menu as $me): ?>
                <li class="nav-item">
                  <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                  <a class="nav-link" href="<?php h(_HOME_ . $me['url']); ?>">
                    <span class="nav-icon">
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-folder" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z" />
                        <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z" />
                      </svg>
                    </span>
                    <span class="nav-link-text"><?php h($me['name']); ?></span>
                  </a><!--//nav-link-->
                </li><!--//nav-item-->
              <?php endforeach; ?>
            <?php endif; ?>
          </ul><!--//app-menu-->
        </nav><!--//app-nav-->
        <div class="app-sidepanel-footer">
          <nav class="app-nav app-nav-footer">
            <ul class="app-menu footer-menu list-unstyled">
              <li class="nav-item">
                <a class="nav-link" href="https://te0.jp/use-policy/special-features">
                  <span class="nav-icon">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M12 1H4a1 1 0 0 0-1 1v10.755S4 11 8 11s5 1.755 5 1.755V2a1 1 0 0 0-1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z" />
                      <path fill-rule="evenodd" d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    </svg>
                  </span>
                  <span class="nav-link-text">ご利用規約</span>
                </a><!--//nav-link-->
              </li><!--//nav-item-->
            </ul><!--//footer-menu-->
          </nav>
        </div><!--//app-sidepanel-footer-->

      </div><!--//sidepanel-inner-->
    </div><!--//app-sidepanel-->
  </header><!--//app-header-->

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">

        <?php Session::flash(); ?>
        <?php self::contents(); ?>

        <?php if (_DEBUG_) : ?>
          <div id="foot_debug" class="container-fluid">
            <div class="well">
              [SESSION]<br>
              <?php r($_SESSION); ?>
              [GET]<br>
              <?php r($_GET); ?>
              [JavaScript]<br>
              <?php
              foreach ((array)Html::$configScript as $define) {
                echo $define . ' = \'' . constant($define) . '\' ;<br />';
              }
              ?>
              <?php
              foreach ((array)$_SESSION['auth'] as $key => $value) {
                if (is_string($value)) {
                  echo 'iam.' . $key . ' = \'' . $value . '\' ;<br />';
                } else {
                  foreach ((array)$value as $key2 => $value2) {
                    if (is_string($value2)) {
                      echo 'iam.' . $key . '.' . $key2 . ' = \'' . $value2 . '\' ;<br />';
                    }
                  }
                }
              }
              ?>
              [REQUEST]<br>
              <?php r($_REQUEST); ?>
            </div>
          </div>
        <?php endif; ?>

      </div><!--//container-fluid-->
    </div><!--//app-content-->

    <footer class="app-footer">
      <div class="container text-center py-3">
        <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
        <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>

      </div>
    </footer><!--//app-footer-->

  </div><!--//app-wrapper-->

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