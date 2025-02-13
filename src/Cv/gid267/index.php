<?php

class indexC extends commonSubC{

	//初期設定**********************************

	//タイトル
	static public $title = '特別システム';

	//追加CSS
	static public $addCss = array();

	//フレームレス
	static public $type = 'gid';

	//モデル読み込み
	static public $use = array();

	//実処理**********************************
	static public function beforeFilter(){
		

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function action(){

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// プロモーションコードを取得
			$promoCode = isset($_POST['promo_code']) ? $_POST['promo_code'] : '';

			// ファイルがアップロードされたか確認
			if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
				$fileTmpPath = $_FILES['csv_file']['tmp_name'];
				$fileName = $_FILES['csv_file']['name'];
				$fileSize = $_FILES['csv_file']['size'];
				$fileType = $_FILES['csv_file']['type'];

				// CSVファイルの拡張子チェック（オプション）
				$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
				if (strtolower($fileExtension) != 'csv') {
					Session::error('CSVファイルをアップロードしてください。');
					return;
				}

				//CSVが1000件以上の場合はエラー
				$lineCount = 0;
				$handle = fopen($fileTmpPath, 'r');
				while (fgets($handle) !== false) {
					$lineCount++;
				}
				fclose($handle);
				if ($lineCount > 1000) {
					Session::error('1000件以上のCSVファイルはアップロードできません。');
					return;
				}

				// ファイルを開いて内容を読み込む
				if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
					$csvData = [];
					$headers = [];

					$lineNumber = 0; // 行番号を追跡
					
					while (($data = fgetcsv2($handle,",")) !== FALSE) {
						$lineNumber++;
						// 最初の行をヘッダーとして取得
						if (empty($headers)) {
							$headers = array_map(function ($item) {
								return trim($item);
							}, $data);
							continue;
						}
						// ヘッダーとデータの要素数が一致するか確認
						if (count($headers) !== count($data)) {
							$headerCount = count($headers);
							$dataCount = count($data);
							Session::error("CSVファイルのフォーマットが正しくありません（".$lineNumber." 行目：ヘッダーのカラム数 ".$headerCount."、データのカラム数 ".$dataCount."）。");
							return;
						}
						// データ行の文字コードをUTF-8に変換し、ヘッダーをキーに設定
						$row = array_combine($headers, array_map(function ($item) {
							return trim($item);
						}, $data));
						// 注文IDがない場合はエラー
						if(!isset($row['注文ID'])){
							Session::error('CSVファイルのフォーマットが正しくありません（テレAIの標準フォーマットを利用してください）。');
							return;
						}
						$csvData[$row['注文ID']][] = $row;
					}
					fclose($handle);
				} else {
					Session::error('ファイルの読み込みに失敗しました。');
					return;
				}

				//CSVデータを制作
				$csv = '';
				$csv .= '"会社コード","受付方法","外部受注番号","受付日","顧客番号","電話番号","氏名漢字","氏名カナ","郵便番号","住所1","住所2","住所3","生年月日","性別","プロモーションコード","ポイント全使用フラグ","支払方法","配送指定日","時間帯指定","注文番号1","受注数量１","注文番号2","受注数量2","注文番号3","受注数量3","注文番号4","受注数量4","注文番号5","受注数量5","注文番号6","受注数量6","注文番号7","受注数量7","注文番号8","受注数量8","注文番号9","受注数量9","注文番号10","受注数量10","メモ"'."\n";
				foreach ($csvData as $orderId => $orderDatas){
					//注文日時を取得
					$date = new DateTime($orderDatas[0]['着信日時']);
					$dateOnly = $date->format('Ymd');
					//外部受注番号を算出--------------------
					//オーダーがあるか確認
					$orders = array();
					$orders = My::select(array(
						'HOST' => _DB_VIP_HOST_,
						'DB' => _DB_VIP_DB_,
						'USER' => _DB_VIP_USER_,
						'PASSWORD' => _DB_VIP_PASSWORD_,
						'TABLE' => 'orders',
						'WHERE' => array(
							'order_id' => $orderDatas[0]['注文ID'],
							'delete' => 0
						),
						'LIMIT' => 1
					));
					if((string)$orders['count'] === '1') {
						//オーダーがある場合は外部受注番号を取得
						$externalOrderNumber = $orders['data'][0]['external_order_number'];
					}else{
						//オーダーがない場合は新規作成
						$sequences = array();
						$sequences = My::select(array(
							'HOST' => _DB_VIP_HOST_,
							'DB' => _DB_VIP_DB_,
							'USER' => _DB_VIP_USER_,
							'PASSWORD' => _DB_VIP_PASSWORD_,
							'TABLE' => 'sequences',
							'WHERE' => array(
								'received_date' => $dateOnly,
								'delete' => 0
							),
							'LIMIT' => 1
						));
						if ((string)$sequences['count'] === '1'){
							//インクリメント
							$sequence_number = $sequences['data'][0]['sequence_number'] + 1;
							//シーケンスがある場合は一足しておく
							$sequences = array();
							$sequences = My::update(array(
								'HOST' => _DB_VIP_HOST_,
								'DB' => _DB_VIP_DB_,
								'USER' => _DB_VIP_USER_,
								'PASSWORD' => _DB_VIP_PASSWORD_,
								'TABLE' => 'sequences',
								'SET' => array(
									'sequence_number' => $sequence_number,
								),
								'WHERE' => array(
									'received_date' => $dateOnly,
									'delete' => 0
								),
								'LIMIT' => 1
							));
						}else{
							$sequence_number = 1;
							//シーケンスがない場合は新規作成
							$sequences = array();
							$sequences = My::insert(array(
								'HOST' => _DB_VIP_HOST_,
								'DB' => _DB_VIP_DB_,
								'USER' => _DB_VIP_USER_,
								'PASSWORD' => _DB_VIP_PASSWORD_,
								'TABLE' => 'sequences',
								'SET' => array(
									'received_date' => $dateOnly,
									'sequence_number' => '1',
								),
								'LIMIT' => 1
							));
						}
						//外部受注番号を生成(2桁(西暦下2桁)＋日数(0～366)＋識別コード(0)＋連番(4桁))
						$externalOrderNumber = substr($date->format('y'), -2) . $date->format('z').'0'.sprintf('%04d', $sequence_number);
						//外部受注番号を保存
						$orders = array();
						$orders = My::insert(array(
							'HOST' => _DB_VIP_HOST_,
							'DB' => _DB_VIP_DB_,
							'USER' => _DB_VIP_USER_,
							'PASSWORD' => _DB_VIP_PASSWORD_,
							'TABLE' => 'orders',
							'SET' => array(
								'order_id' => $orderDatas[0]['注文ID'],
								'external_order_number' => $externalOrderNumber,
								'received_date' => $dateOnly,
							),
							'LIMIT' => 1
						));
					}
					$csv .= '"226",'; //会社コード
					$csv .= '"1",'; //受付方法
					$csv .= '"'. $externalOrderNumber.'",'; //外部受注番号
					$csv .= '"'.$dateOnly.'",'; //受付日
					$csv .= '"",'; //顧客番号
					$csv .= '"' . str_replace('-', '', $orderDatas[0]['発送先電話番号']) . '",'; //電話番号
					$csv .= '"' . str_replace(' ', '　', $orderDatas[0]['発送先氏名']) . '",'; //氏名漢字
					$csv .= '"' . str_replace(' ', '　', $orderDatas[0]['発送先氏名(カナ)']) . '",'; //氏名カナ
					$csv .= '"' . str_replace('-', '', $orderDatas[0]['発送先郵便番号']) . '",'; //郵便番号
					$csv .= '"' . mb_convert_kana(str_replace([" ", "　"], "", $orderDatas[0]['発送先住所１']), "ASKV") . '",'; //住所1
					$csv .= '"' . mb_convert_kana(str_replace([" ", "　"], "", $orderDatas[0]['発送先住所２']), "ASKV") . '",'; //住所2
					$csv .= '"",'; //住所3
					$csv .= '"",'; //生年月日
					$csv .= '"2",'; //性別
					$csv .= '"' . trim($promoCode) . '",'; //プロモーションコード
					$csv .= '"",'; //ポイント全使用フラグ
					$csv .= '"1",'; //支払方法
					$csv .= '"",'; //配送指定日
					$csv .= '"",'; //時間帯指定
					for($i=0;$i<10;$i++){
						if(isset($orderDatas[$i])){
							$csv .= '"' . $orderDatas[$i]['商品コード'] . '",'; //注文番号1～10
							$csv .= '"' . $orderDatas[$i]['受注数量'] . '",'; //受注数量１～10
						} else {
							$csv .= '"",'; //注文番号1～10
							$csv .= '"",'; //受注数量１～10
						}
					}
					if (isset($orderDatas[10])) {
						$csv .= '"11個以降商品あり",';//メモ
					} else {
						$csv .= '"",';//メモ
					}
					//最後のカンマを改行に変換（CRLF）
					$csv = preg_replace('/,$/', "\r\n", $csv);
				}

				//CSV出力
				$csv = mb_convert_encoding($csv, 'sjis-win', 'utf-8');
				$fld = random();
				$file = 'export.' . date("YmdHis") . '.csv';
				if (!is_dir(_TMP_ROOT_ . $fld)) mkdir(_TMP_ROOT_ . $fld, 0700);
				file_put_contents(_TMP_ROOT_ . $fld . '/' . $file, $csv);

				// ダウンロードリンクを表示
				$downloadUrl = _TMP_HOME_ . $fld . '/' . $file;
				self::set('downloadurl',$downloadUrl);
				Session::info('CSVファイルのコンバートが完了しました。');
			} else {
				Session::error('ファイルのアップロードに失敗しました。');
				return;
			}
		}

	}

}
