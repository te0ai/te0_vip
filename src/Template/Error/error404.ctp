<div class="container">
  <div class="jumbotron mt-5 mx-auto">
    <div class="panel panel-default">
      <div class="panel-body text-center">
        <span class="fa fa-file fa-5x">&nbsp;404</span>
        <h2><?php if (isset($message)) h($message) ?></h2>
        <p>Page not found - ページがありません</p>
        <!-- ボタンで戻る -->
        <a href="<?php h(_HOME_); ?>login/exit/" class="btn btn-primary">ログアウト</a>
      </div>
    </div>
  </div>
</div> <!-- /container -->