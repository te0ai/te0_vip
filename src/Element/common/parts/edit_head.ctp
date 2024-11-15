<div class="container-large mt-2">
  <div class="row">
    <div class="col-12 clearfix">
      <div class="float-left">
        <a href="<?php h(_HOME_) ?>app/list/<?php h($group['str']) ?>/" class="btn btn-lg d-inline-block page-link text-dark">
          <?php h($group['title']); ?>
        </a>
        <a href="<?php h(_HOME_) ?>auto/list/?uri=<?php h(urlencode('edit/'.$group['str'])) ?>&amp;ptitle=<?php h(urlencode(Html::title())) ?>&amp;purl=<?php h(urlencode($_REQUEST['URL'])) ?>" class="btn btn-lg d-inline-block">
          自動実行一覧
        </a>
      </div>
      <div class="float-right">

        <?php /*
        <div class="dropdown d-inline-block">
          <button class="btn btn-lg d-inline-block dropdown-toggle"
                  type="button" id="dropdownMenu1" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
            <span class="lnr lnr-magic-wand"></span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <h6 class="dropdown-header">この内容で</h6>
            <a href="javascript:void(0);" class="dropdown-item wibox" data-url="<?php h(_HOME_) ?><?php h($group['str']) ?>/makebutton/?frame=popup" data-width="800" data-height="500">
              ボタン生成
            </a>
            <a href="javascript:void(0);" class="dropdown-item wibox" data-url="../makebatch/?frame=popup" data-width="800" data-height="500">
              定期生成
            </a>
          </div>
        </div>
        */ ?>

        <div class="dropdown d-inline-block">
          <button class="btn btn-lg d-inline-block dropdown-toggle"
                  type="button" id="dropdownMenu1" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
            <span class="lnr lnr-cog"></span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <h6 class="dropdown-header">レイアウト</h6>
            <a href="<?php h(_HOME_) ?>template/init/?key=<?php h(urlencode('edit/'.$group['str'])) ?>&amp;type=html&amp;ptitle=<?php h(urlencode(Html::title())) ?>&amp;purl=<?php h(urlencode($_REQUEST['URL'])) ?>" class="dropdown-item">
              デザイン
            </a>
            <h6 class="dropdown-header">共有</h6>
            <a href="<?php h(_HOME_) ?>share/list/?key=<?php h(urlencode('edit/'.$group['str'])) ?>&amp;type=html&amp;ptitle=<?php h(urlencode(Html::title())) ?>&amp;purl=<?php h(urlencode($_REQUEST['URL'])) ?>" class="dropdown-item">
              機能共有
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
