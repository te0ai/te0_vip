<?php

if((string)$all_count!=='0'){
	//$return_arr['paginator'] = '<div>'.$all_count.'件見つかりました</div>';
	$return_arr['paginator'] = '';
}
if($max_num > 1){

	//PC
	$return_arr['paginator'] .='<nav class="my-4">';
	$return_arr['paginator'] .='<ul class="pagination pagination-circle pg-blue mb-0">';

	if($now_page != 0){

		$prev = $now_page - 1;

		//PC
		$return_arr['paginator'] .='<li class="page-item">';
		$return_arr['paginator'] .='<a href="javascript:void(0);" onclick="$(\'#page_num\').val('.$prev.').change();" class="form-pagination page-link" aria-label="前のページへ">';
		$return_arr['paginator'] .='<span aria-hidden="true">&laquo;</span>';
		$return_arr['paginator'] .='</a>';
		$return_arr['paginator'] .='</li>';

	}


	if($now_page < 8){
		//現在のページが８未満だった場合

		$blocks = $max_num + 1;
		//検索結果が10以上存在する場合
		if( 10 < $max_num)$blocks = 10;

		for($i=1;$i<$blocks;$i++){

			if(((int)$now_page + 1) != $i ){

				$thisnum = $i - 1;

				//PC
				$return_arr['paginator'] .='<li class="page-item"><a href="javascript:void(0);" onclick="$(\'#page_num\').val('.$thisnum.').change();" class="form-pagination page-link">'.$i.'</a></li>';

			}else{

				//PC
				$return_arr['paginator'] .='<li class="page-item active"><a href="javascript:void(0);" class="page-link">'.$i.'</a></strong>';

			}
		}


	}else{

		//現在のページが８以上だった場合
		$past = (int)$now_page - 5;
		$future = (int)$now_page + 5;

		if($max_num < $future)
			$future = $max_num + 1;

		for($i=$past;$i<$future;$i++){

			if(((int)$now_page + 1) != $i ){

				$thisnum = $i - 1;

				//PC
				$return_arr['paginator'] .='<li class="page-item"><a href="javascript:void(0);" onclick="$(\'#page_num\').val('.$thisnum.').change();" class="form-pagination page-link">'.$i.'</a></li>';

			}else{

				//PC
				$return_arr['paginator'] .='<li class="page-item active"><a href="javascript:void(0);" class="page-link">'.$i.'</a></li>';

			}
		}

	}


	if($now_page < ($max_num-1)){

		$next = $now_page + 1;

		//PC
		$return_arr['paginator'] .='<li class="page-item">';
		$return_arr['paginator'] .='<a href="javascript:void(0);" onclick="$(\'#page_num\').val('.$next.').change();" class="page-link" aria-label="次のページへ">';
		$return_arr['paginator'] .='<span aria-hidden="true">&raquo;</span>';
		$return_arr['paginator'] .='</a>';
		$return_arr['paginator'] .='</li>';
	}

	//PC
	$return_arr['paginator'] .='</ul>';
	$return_arr['paginator'] .='</nav><!-- /.pagination -->';

}


?>
