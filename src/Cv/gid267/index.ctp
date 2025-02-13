<?php if (isset($downloadurl)): ?>
  <a href="<?php h($downloadurl) ?>" class="btn btn-lg app-btn-primary mb-5 mt-2" style="font-size: 1.5rem;padding: 1rem 2rem;border-radius: 0.5rem;" download>コンバートされたCSVをダウンロードする</a>
<?php endif; ?>

<h1 class="app-page-title">CSVコンバーター</h1>
<div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
  <div class="inner">
    <div class="app-card-body p-3 p-lg-4">
      <h3 class="mb-3">CSVコンバーター</h3>
      <div class="row gx-5 gy-3">
        <div class="col-12 col-lg-9">
          <div>テレAIの標準CSVをアップロードすることでご希望のCSVの形に変換いたします。</div>
          <div>仕様はお客様のご要望の通り決定しています。</div>
        </div><!--//col-->
        <div class="col-12 col-lg-3">

        </div><!--//col-->
      </div><!--//row-->
    </div><!--//app-card-body-->

  </div><!--//inner-->
</div><!--//app-card-->

<h1 class="app-page-title">手順</h1>
<hr class="mb-4">
<div class="row g-4 settings-section">
  <div class="col-12 col-md-4">
    <h3 class="section-title">1.テレAIから「標準CSV」をダウンロード</h3>
    <div class="section-intro">テレAIから標準のCSVをダウンロードしてください</div>
  </div>
  <div class="col-12 col-md-8">
    <div class="app-card app-card-settings shadow-sm p-4">

      <div class="app-card-body">
        <form class="settings-form">
          <div class="mb-3">
            <img src="<?php h(_HOME_) ?>img/common/2024-11-14_10h46_53.png" class="img-fluid" alt="Responsive image" style="height:200px;">
          </div>
          <a href="https://te0.ai/dash/list_call/" class="btn app-btn-primary">注文一覧へ</a>
        </form>
      </div><!--//app-card-body-->

    </div><!--//app-card-->
  </div>
</div><!--//row-->
<hr class="my-4">
<div class="row g-4 settings-section">
  <div class="col-12 col-md-4">
    <h3 class="section-title">2.コンバート</h3>
    <div class="section-intro">CSVファイルを御社のフォーマットにコンバート致します</div>
  </div>
  <div class="col-12 col-md-8">
    <div class="app-card app-card-settings shadow-sm p-4">
      <div class="app-card-body">
        <form class="settings-form" enctype="multipart/form-data" method="POST" action="">
          <div class="mb-3">
            <label for="promo_code" class="form-label">プロモーションコード</label>
            <input type="text" class="form-control" id="promo_code" name="promo_code" required>
          </div>
          <div class="mb-3">
            <label for="csv_file" class="form-label">テレAIからダウンロードしたCSVファイル</label>
            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
          </div>
          <button type="submit" class="btn app-btn-primary">利用規約に同意してコンバート</button>
        </form>
      </div><!--//app-card-body-->
    </div><!--//app-card-->
  </div>
</div><!--//row-->
<hr class="my-4">

<div class="app-card alert alert-dismissible shadow-sm mb-4" role="alert">
  <div class="inner">
    <div class="app-card-body p-3 p-lg-4">
      <h3 class="mb-3">仕様情報</h3>
      <div class="row gx-5 gy-3">
        <div class="col-12 col-lg-9">
          <ul>
            <li>必ずテレAIからダウンロードした件数と貴社システムにインポートされた件数が一致していることを確認をお願いします。</li>
            <li>1001件以上のCSVが存在する場合は1000件づつに分けてアップロードしてください。</li>
            <li>「外部受注番号」は、テレAI標準CSVの「着信日時」を基準に、1日ごとに取り込み順で連番を付けて算出します。</li>
            <li>「外部受注番号」は、テレAI標準CSVの「注文ID」を基準に、ユニークな外部受注番号を生成しています。</li>
            <li>「外部受注番号」は、同じ「注文ID」を再度入力すると、最初に出力された外部受注番号が再び出力されます。</li>
            <li>11個以上商品がある場合は「メモ」の項目に「11個以降商品あり」と記載します。</li>
            <li>CSVの改行は「CRLF」形式とします。</li>
            <li>「住所1」「住所2」は半角をすべて全角に変換します。</li>
            <li>「住所1」「住所2」は半角スペース、全角スペースをすべて削除します。</li>
          </ul>
        </div><!--//col-->
        <div class="col-12 col-lg-3">

        </div><!--//col-->
      </div><!--//row-->
    </div><!--//app-card-body-->

  </div><!--//inner-->
</div><!--//app-card-->