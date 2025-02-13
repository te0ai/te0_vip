<?php

class indexC extends commonC{

	//初期設定**********************************

	//タイトル
	static public $title = '誰一人取り残さない、人に優しいデジタル化';

	//インデックス用フレーム
	static public $type = 'login';

	//ロール
	static public $role = array();

	//実処理**********************************
	static public function beforeFilter(){

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function action(){
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = $_POST['email'];
			$password = $_POST['password'];
			$remember = isset($_POST['RememberPassword']);

			// RememberPasswordがチェックされている場合はクッキーに保存
			if ($remember) {
				setcookie('email', $email, time() + (86400 * 30), "/"); // 30日間有効
				setcookie('password', $password, time() + (86400 * 30), "/");
			} else {
				// チェックが外されている場合はクッキーを削除
				setcookie('email', '', time() - 3600, "/");
				setcookie('password', '', time() - 3600, "/");
			}

			//取り込み
			$session_str = $email;
			$session_pass = $password;

			//通常ログイン
			if ($session_str === "") {
				Session::error('メールアドレスを入力してください');
				return;
			};
			if (!Mail::valid($session_str)) {
				Session::error('メールアドレスが正しい形式ではありません。');
				return;
			}
			$oid_sanitized = My::s($session_str);
			$oid = $session_str;
			$ip = My::s($_SERVER["REMOTE_ADDR"]);
			$env = My::s(json_encode($_SERVER));

			//ログイン試行回数確認(ブルートフォース && リバースブルートフォース)
			$pmin = date("Y-m-d H:i:s", strtotime("-" . _LOGIN_ATT_MIN_ . " minute"));
			$login_log = array();
			$login_log = My::show(
				"SELECT * FROM `" . _DB_SESSION_DBN_ . "`.`tmp_login` WHERE
				(`oid` LIKE '" . $oid_sanitized . "' OR `ip` LIKE '" . $ip . "')
				AND `add_date` > '" . $pmin . "' LIMIT " . _LOGIN_ATT_ . ";",
				array(
					'HOST' => _DB_SESSION_RO_HOST_,
					'DB' => _DB_SESSION_RO_DBN_,
					'USER' => _DB_RO_USER_,
					'PASSWORD' => _DB_RO_PASSWORD_
				)
			);
			if ($login_log['count'] >= _LOGIN_ATT_) {
				Session::error(_LOGIN_ATT_MIN_ . '分間の間に' . _LOGIN_ATT_ . '回以上のログイン試行があったためアカウントをロックしました、しばらくしてからアクセスしてください');
				return;
			}

			//ログイン試行回数追加
			$now = date('Y-m-d H:i:s');
			$login_log = array();
			$login_log = My::edit(
				"INSERT `" . _DB_SESSION_DBN_ . "`.`tmp_login` SET
				`oid` = '" . $oid_sanitized . "',
				`ip` = '" . $ip . "',
				`env` = '" . $env . "',
				`add_date` = '" . $now . "',
				`edit_date` = '" . $now . "'
				;",
				array(
					'HOST' => _DB_SESSION_HOST_,
					'DB' => _DB_SESSION_DBN_,
					'USER' => _DB_RW_USER_,
					'PASSWORD' => _DB_RW_PASSWORD_
				)
			);
			if (!_DEBUG_) {
				$msg = "ログイン試行回数の更新に失敗しました";
			} else if (isset($login_log['error'])) {
				$msg = "ログイン試行回数の更新に失敗しました(" . __LINE__ . ") DEBUG:" . $login_log['error'];
			}
			if ($login_log['status'] !== true) {
				Session::error($msg);
				return;
			}
			//通常ログイン
			$dtb_login = array();
			$dtb_login = My::select(array(
				'HOST' => _DB_COMMON_RO_HOST_,
				'DB' => _DB_COMMON_RO_DBN_,
				'USER' => _DB_RO_USER_,
				'PASSWORD' => _DB_RO_PASSWORD_,
				'TABLE' => 'dtb_user',
				'WHERE' => array(
					'oid' => $oid,
					'delete' => 0
				),
				'LIMIT' => 1
			));
			if ((string)$dtb_login['count'] !== '0') {
				//アカウントが存在 & パスワードによるログイン試行
				if ($dtb_login['data'][0]['password'] !== '') {
					$dtb_org = array();
					$dtb_org = My::select(array(
						'HOST' => _DB_COMMON_RO_HOST_,
						'DB' => _DB_COMMON_RO_DBN_,
						'USER' => _DB_RO_USER_,
						'PASSWORD' => _DB_RO_PASSWORD_,
						'TABLE' => 'dtb_org',
						'WHERE' => array(
							'id' => $dtb_login['data'][0]['gid'],
							'delete' => 0
						),
						'LIMIT' => 1
					));
					if ((string)$dtb_org['count'] !== '0') {
						$_SESSION = array();
						$_SESSION['auth'] = array(
							'uid' => $dtb_login['data'][0]['id'],
							'gid' => $dtb_login['data'][0]['gid'],
							'type' => $dtb_org['data'][0]['type'],
							'user' => $dtb_login['data'][0],
							'org' => $dtb_org['data'][0]
						);
					} else {
						Session::info('組織情報が存在しませんでした。');
						return;
					}
				} else {
					Session::info('あなたはパスワードを設定していません、本サイトにログインをするにはパスワードが必要です。');
					return;
				}
			} else {
				Session::error('ユーザーが存在しません、メールアドレスを確認してください。');
				return;
			}
		}

		//ログインしている場合はリダイレクト
		if(
			isset($_SESSION['auth']['type']) &&
			$_SESSION['auth']['type']=== 'crew'
		){
			self::$redirect = array('url' => 'crew/', 'sec' => 0);
		}else if (
			isset($_SESSION['auth']['type']) &&
			$_SESSION['auth']['type'] === 'agent'
		) {
			self::$redirect = array('url' => 'agent/', 'sec' => 0);
		}else if (
			isset($_SESSION['auth']['gid'])&&
			is_numeric($_SESSION['auth']['gid'])
		) {
			self::$redirect = array('url' => 'gid'.$_SESSION['auth']['gid'].'/', 'sec' => 0);
		}
	}
}
