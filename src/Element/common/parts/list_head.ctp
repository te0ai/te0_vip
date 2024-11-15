<div class="container-large mt-2">
  <div class="col-12 clearfix">
    <div class="float-left">
      <a href="<?php h(_HOME_) ?>" class="btn btn-lg d-inline-block page-link text-dark">
        <?php h($group['title']); ?>
      </a>
      <a href="<?php h(_HOME_) ?>auto/list/?uri=<?php h(urlencode('list/'.$group['str'])) ?>&amp;ptitle=<?php h(urlencode(Html::title())) ?>&amp;purl=<?php h(urlencode($_REQUEST['URL'])) ?>" class="btn btn-lg d-inline-block">
        自動実行一覧
      </a>
    </div>
    <div class="float-right">

      <div class="dropdown d-inline-block">
        <button class="btn btn-lg d-inline-block dropdown-toggle"
                type="button" id="dropdownMenu1" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
          <span class="lnr lnr-cog"></span>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
          <h6 class="dropdown-header">インポート</h6>
          <a href="<?php h(_HOME_) ?>a/p/<?php h(urlencode($group['str'])) ?>/csv/" class="dropdown-item">
            一括インポート
          </a>
          <h6 class="dropdown-header">レイアウト</h6>
          <a href="<?php h(_HOME_) ?>template/init/?key=<?php h(urlencode('list/'.$group['str'])) ?>&amp;type=html&amp;ptitle=<?php h(urlencode(Html::title())) ?>&amp;purl=<?php h(urlencode($_REQUEST['URL'])) ?>" class="dropdown-item">
            デザイン
          </a>
          <h6 class="dropdown-header">共有</h6>
          <a href="<?php h(_HOME_) ?>share/list/?key=<?php h(urlencode('list/'.$group['str'])) ?>&amp;type=html&amp;ptitle=<?php h(urlencode(Html::title())) ?>&amp;purl=<?php h(urlencode($_REQUEST['URL'])) ?>" class="dropdown-item">
            機能共有
          </a>
        </div>
      </div>

      <a href="<?php h(_HOME_) ?>app/edit/<?php h($group['str']) ?>/?thread-id=0" class="btn btn-lg btn-primary">
        <i class="fas fa-plus-circle"></i>&nbsp;<?php h($group['title']); ?>を新しく作る
      </a>

    </div>
  </div>
</div>
