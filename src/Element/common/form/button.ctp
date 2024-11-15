<?php
echo "<button ";
echo "type=\"button\" ";
echo isset($_['id']) ? 'id="'.h($_['id'],true).'" ' : '';
echo "class=\"".h($_['class'],true)."\" ";

//POST先で操作を振り分ける
echo isset($_['operation'])?'data-operation="'.h($_['operation'],true).'" ':'';

//補助データ
echo isset($_['group-str'])?'data-group="'.h($_['group-str'],true).'" ':'';

//ポップアップから親に値を送信する
echo isset($_['parent_insert_id'])?'data-parent-insert-id="'.h($_['parent_insert_id'],true).'" ':'';
echo isset($_['parent_insert_data'])?'data-parent-insert-data="'.h($_['parent_insert_data'],true).'" ':'';
echo isset($_['parent_insert_meta'])?'data-parent-insert-meta="'.h($_['parent_insert_meta'],true).'" ':'';

echo ">";
if($_['icon']){
  echo "<span class=\"";
  echo $_['icon'];
  echo "\" aria-hidden=\"true\"></span>\n";
}
echo $_['value']?:'送信する';
echo "</button>".PHP_EOL;
?>
