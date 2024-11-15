<?php
class Scrap {

	//基本機能------------------------
	static public $body = '';
	static public $dom = NULL;

	//コンテンツ取得
	static public function get_contents($arr){
		//初期化
		self::$body = '';
		self::$dom = NULL;

		//コンテンツ取得
		try{
			$context = stream_context_create(array(
			  'http' => array(
					'ignore_errors' => true,
					'timeout' => 1200,
				)
			));
			self::$body = file_get_contents($arr['url'],false,$context);
			preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
			$status_code = $matches[1];
			if(300 > $status_code && 200 <= $status_code){
				return array(
					'result' => 'success',
					'status_code' => $status_code,
					'body' => self::$body,
				);
			}else{
				return array(
					'result' => 'error',
					'status_code' => $status_code,
					'body' => self::$body,
				);
			}
		}catch (Exception $e) {
			return array(
				'result' => 'error',
				'status_code' => 0,
				'body' => $e->getMessage(),
			);
		}

	}

	//CSSセレクターでコンテンツを取得
	static public function select($input_selector,$arr=array()){

		if($input_selector==="") return false;

		//初期値設定
		if(isset($arr['body'])) self::$body = $arr['body'];
		$throw_exception = isset($arr['throw_exception']) ?: false;

		//エンコード
		self::dom_enc();
		//セレクタを分解（なぜかorのカンマを使うとノードの取得に失敗するのでこのレイヤーで処理）
		$selecters = array();
		$selecters = explode(',',$input_selector);
		//CSSセレクターをXpathに変換
		$xpathes = array();
		foreach($selecters as $select){
			$xpathes[] = self::selector2xpath($select);
		}
		//アクセス
		$dom_xpath = new DOMXPath(self::$dom);
		//クエリアクセス
		$html = array();
		foreach($xpathes as $xpath){
			$nodes = NULL;
			$nodes = $dom_xpath->query($xpath);
			if(is_object($nodes) && $nodes->length!==0){
				foreach ($nodes as $node){
					$tmp = '';
					$tmp = self::$dom->saveHTML($node);
					$html[] = $tmp;
				}
			}
		}
		//結果
		if(!empty($html)){
			return $html;
		}else{
			return false;
		}

	}

	//DOMエンコード
	static public function dom_enc($body=NULL){
		//body取得
		$body = $body ?: self::$body;
		//dom
		$dom_document = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_document->loadHTML(
			mb_convert_encoding($body,'HTML-ENTITIES','UTF-8'),
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
		);
		libxml_clear_errors();
		self::$dom = $dom_document;
	}

	//CSSセレクターをXpathに変換
	static public function selector2xpath($input_selector,$throw_exception = false){
	    $regex = array(
	        'element'    => '/^(\*|[a-z_][a-z0-9_-]*|(?=[#:.\[]))/i',
	        'id_class'   => '/^([#.])([a-z0-9*_-]*)/i',
	        //'attribute'  => '/^\[\s*([^~|=\s]+)\s*([~|]?=)\s*"([^"]+)"\s*\]/',
					'attribute'  => '/^\[\s*([^~|\*=\s]+)\s*([~|\*]?=)\s*"([^"]+)"\s*\]/',//201225 edit wired
	        'attr_box'   => '/^\[([^\]]*)\]/',
	        'attr_not'   => '/^:not\(([^)]*)\)/i',
	        'pseudo'     => '/^:([a-z0-9_-]+)(\(\s*([a-z0-9_\s+-]+)\s*\))?/i',
		      //'combinator' => '/^(\s*[>+~\s])/i',
		      //'comma'      => '/^(,)/',
	        'combinator_or_comma' => '/^(\s*[>+~\s,])/i',
	    );
	    $parts[] = '//';
	    $last = '';
	    $selector = trim($input_selector);
	    $element = true;

	    $pregMatchDelete = function ($pattern, &$subject, &$matches){ // 正規表現でマッチをしつつ、マッチ部分を削除
	        if (preg_match($pattern, $subject, $matches)) {
	            $subject = substr($subject, strlen($matches[0]));
	            return true;
	        }
	    };

	    while ((strlen(trim($selector)) > 0) && ($last != $selector)){

	        $selector = trim($selector);
	        $last = trim($selector);

	        // Elementを取得
	        if($element){
	            if ($pregMatchDelete($regex['element'], $selector, $e)){
	                $parts[] = $e[1]==='' ? '*' : $e[1];
	            }
	            elseif($throw_exception) {
	                throw new UnexpectedValueException("parser error: '$input_selector' is not valid selector.(missing element)");
	            }
	            $element = false;
	        }
	        // IDとClassの指定を取得
	        if($pregMatchDelete($regex['id_class'], $selector, $e)) {
	            switch ($e[1]){
	                case '.':
	                    $parts[] = '[contains(concat( " ", @class, " "), " ' . $e[2] . ' ")]';
	                    break;
	                case '#':
	                    $parts[] = '[@id="' . $e[2] . '"]';
	                    break;
	                default:
	                    if($throw_exception) throw new LogicException("Unexpected flow occured. please conntact authors.");
	                    break;
	            }
	        }

	        // atribauteを取得
	        if($pregMatchDelete($regex['attribute'], $selector, $e)) {
	            switch ($e[2]){ // 二項(比較)
	                case '!=':
	                    $parts[] = '[@' . $e[1] . '!=' . $e[3] . ']';
	                    break;
	                case '~=':
	                    $parts[] = '[contains(concat( " ", @' . $e[1] . ', " "), " ' . $e[3] . ' ")]';
	                    break;
	                case '|=':
	                    $parts[] = '[@' . $e[1] . '="' . $e[3] . '" or starts-with(@' . $e[1] . ', concat( "' . $e[3] . '", "-"))]';
	                    break;
									case '*=':
											//201225 add wired
	                    $parts[] = '[contains(@' . $e[1] . ',"' . $e[3] . '")]';
	                    break;
	                default:
	                    $parts[] = '[@' . $e[1] . '="' . $e[3] . '"]';
	                    break;
	            }
	        }
	        else if ($pregMatchDelete($regex['attr_box'], $selector, $e)) {
	            $parts[] = '[@' . $e[1] . ']';  // 単項(存在性)
	        }

	        // notつきのattribute処理
	        if ($pregMatchDelete($regex['attr_not'], $selector, $e)) {
	            if ($pregMatchDelete($regex['attribute'], $e[1], $sub_e)) {
	                switch ($sub_e[2]){ // 二項(比較)
	                    case '=':
	                        $parts[] = '[@' . $sub_e[1] . '!=' . $sub_e[3] . ']';
	                        break;
	                    case '~=':
	                        $parts[] = '[not(contains(concat( " ", @' . $sub_e[1] . ', " "), " ' . $sub_e[3] . ' "))]';
	                        break;
	                    case '|=':
	                        $parts[] = '[not(@' . $sub_e[1] . '="' . $sub_e[3] . '" or starts-with(@' . $sub_e[1] . ', concat( "' . $sub_e[3] . '", "-")))]';
	                        break;
	                    default:
	                        break;
	                }
	            }
	            else if ($pregMatchDelete($regex['attr_box'], $e[1], $e)) {
	                $parts[] = '[not(@' . $e[1] . ')]'; // 単項(存在性)
	            }
	        }

	        // 疑似セレクタを処理
	        if ($pregMatchDelete($regex['pseudo'], $selector, $e)) {
	            switch ($e[1]) {
	                case 'first-child':
	                    $parts[] = '[not(preceding-sibling::*)]';
	                    break;
	                case 'last-child':
	                    $parts[] = '[not(following-sibling::*)]';
	                    break;
	                case 'nth-child':
	                    // CSS3
	                    if (is_numeric($e[3])) {
	                        $parts[] = '[count(preceding-sibling::*) = ' . $e[3] . ' - 1]';
	                    }
	                    else if ($e[3] == 'odd') {
	                        $parts[] = '[count(preceding-sibling::*) mod 2 = 0]';
	                    }
	                    else if ($e[3] == 'even') {
	                        $parts[] = '[count(preceding-sibling::*) mod 2 = 1]';
	                    }
	                    else if (preg_match('/^([+-]?)(\d*)n(\s*([+-])\s*(\d+))?\s*$/i', $e[3], $sub_e)) {
	                        $coefficient = $sub_e[2]==='' ? 1 : intval($sub_e[2]);
	                        $constant_term = array_key_exists(3, $sub_e) ?  intval($sub_e[4]==='+' ? $sub_e[5] : -1 * $sub_e[5]) : 0;
	                        if($sub_e[1]==='-') {
	                            $parts[] = '[(count(preceding-sibling::*) + 1) * ' . $coefficient . ' <= ' . $constant_term . ']';
	                        }
	                        else { // '+' or ''
	                            $parts[] = '[(count(preceding-sibling::*) + 1) ' . ($coefficient===0 ? '': 'mod ' . $coefficient . ' ') . '= ' . ($constant_term>=0 ? $constant_term : $coefficient + $constant_term) . ']';
	                        }
	                    }
	                    break;
	                case 'lang':
	                    $parts[] = '[@xml:lang="' . $e[3] . '" or starts-with(@xml:lang, "' . $e[3] . '-")]';
	                    break;
	                default:
	                    break;
	            }
	        }

	         // combinatorとカンマがあったら、区切りを追加。また、次は型選択子又は汎用選択子でなければならない
	        if ($pregMatchDelete($regex['combinator_or_comma'], $selector, $e)) {
	            switch (trim($e[1])) {
	                case ',':
	                    $parts[] = ' | //*';
	                    break;
	                case '>':
	                    $parts[] = '/';
	                    break;
	                case '+':
	                    $parts[] = '/following-sibling::*[1]/self::';
	                    break;
	                case '~': // CSS3
	                    $parts[] = '/following-sibling::';
	                    break;
	              //case '':
	                default:
	                    $parts[] = '//';
	                    break;
	            }
	            $element = true;
	        }
	    }

	    $return = implode('', $parts);
	    return $return;
	}

}

?>
