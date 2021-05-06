<?php
/* セッション利用の宣言
--------------------------------------------------*/
session_start();


/*変数の初期化
------------------------------------------------- */
//入力データ用
$sei  = '';
$mei  = '';
$no   = '';
$seibetsu = '';
$cook   = array();
$nendai = '';
$iken   = '';


//エラーメッセージ用
$sei_err  = '';
$mei_err  = '';
$no_err   = '';
$seibetsu_err = '';
$cook_err   = '';
$nendai_err = '';
$iken_err   = '';

//エラーフラグ
$errflg = 0;   //0:エラーなし   1:エラーあり

/* 入力データが送信されてきたとき
-------------------------------------------------*/
if($_SERVER['REQUEST_METHOD']==='POST'){
	//特殊文字対策をしながらデータの受け取り
	$sei = htmlspecialchars($_POST["sei"],ENT_QUOTES);
	$mei = htmlspecialchars($_POST["mei"],ENT_QUOTES);
	if(isset($_POST["seibetsu"])){
		$seibetsu = htmlspecialchars($_POST["seibetsu"],ENT_QUOTES);
	}
	$no     = htmlspecialchars($_POST["no"],ENT_QUOTES);

	if(isset($_POST["cook"])){
		$cook = $_POST["cook"];
		$cook_cnt = count($cook);
		for($i=0; $i<$cook_cnt;$i++){
			$cook[$i]   = htmlspecialchars($cook[$i],ENT_QUOTES);
		}
	}
	$nendai = htmlspecialchars($_POST["nendai"],ENT_QUOTES);
	$iken   = htmlspecialchars($_POST["iken"],ENT_QUOTES);

	//半角・全角の変換
	$sei  = mb_convert_kana($sei,'KV','UTF-8');
	$mei  = mb_convert_kana($mei,'KV','UTF-8');
	$no   = mb_convert_kana($no,'n','UTF-8');
	$iken = mb_convert_kana($iken,'KV','UTF-8');

	//エラーチェック
	//名前
	if(mb_strlen($sei) === 0){
		$sei_err = '<p class="err">名前（姓）を入力して下さい</p>';
		$errflg = 1;
	}

	if(mb_strlen($mei) === 0){
		$mei_err = '<p class="err">名前（名）を入力して下さい</p>';
		$errflg = 1;
	}
	
	//会員番号
	if(mb_strlen($no) === 0){
		$no_err = '<p class="err">会員番号を入力して下さい</p>';
		$errflg = 1;
	}elseif(is_numeric($no)=== false){
		$no_err = '<p class="err">会員番号を数値で入力してください</p>';
		$errflg = 1;
	}elseif(mb_strlen($no) !== 5){
		$no_err = '<p class="err">会員番号を5桁で入力してください</p>';
		$errflg = 1;
	}	
	
	//性別
	if($seibetsu === ""){
		$seibetsu_err = '<p class="err">性別を選択してください</p>';
		$errlg = 1;
	}
	
	
	//好きな料理
	$cook_cnt = count($cook);
	if($cook_cnt === 0){
		$cook_err = '<p class="err">好きな料理を選択してください</p>';
		$errflg = 1;	
	}

	//年代
	if($nendai === ""){
		$nendai_err = '<p class="err">年代を選択して下さい</p>';
		$errflg = 1;
	}
	
	//ご意見・ご感想
	if(mb_strlen($iken) === 0){
		$iken_err = '<p class="err">ご意見・ご感想を入力して下さい</p>';
		$errflg = 1;
	}
	
	//エラーがなければ、kakunin.phpへジャンプする
	if($errflg === 0){
		//sessionにデータを保存
		$_SESSION["sei"]      = $sei;
		$_SESSION["mei"]      = $mei;
		$_SESSION["no"]       = $no;
		$_SESSION["seibetsu"] = $seibetsu;
		$_SESSION["cook"]     = $cook;
		$_SESSION["nendai"]   = $nendai;
		$_SESSION["iken"]     = $iken;
		header('Location: kakunin.php');
		exit();
	}
}

/* 修正の場合
-------------------------------------------------*/
if(isset($_GET["henkou"])){
	//セッションの値を変数に代入
	$sei      = $_SESSION["sei"];
	$mei      = $_SESSION["mei"];
	$no       = $_SESSION["no"];
	$seibetsu = $_SESSION["seibetsu"];
	$cook     = $_SESSION["cook"];
	$nendai   = $_SESSION["nendai"];
	$iken     = $_SESSION["iken"];
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>フォーム（入力）｜PHP演習</title>
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/common.css">
	</head>
	<body>
		<header>
			<h1>アンケート</h1>
		</header>
		
		<main>
			<div class="step">
				<img src="images/step1.png" alt="ステップ1：入力">
			</div>
		
			<h2>入力画面</h2>
			<p>以下のアンケートに回答の上、［入力内容を確認する］ボタンをクリックしてください。</p>
			<?php print($sei_err); ?>
			<?php print($mei_err); ?>
			<?php print($no_err); ?>
			<?php print($seibetsu_err); ?>
			<?php print($cook_err); ?>
			<?php print($nendai_err); ?>
			<?php print($iken_err); ?>
			
			<form method="post" action="input.php">
				<table border="1">
					<tr>
						<th>名前<span class="req">必須</span></th>
						<td><?php print($sei_err); ?>
								<?php print($mei_err); ?>
								姓&nbsp;<input type="text" name="sei" value="<?php print($sei); ?>">&nbsp;&nbsp;
								名&nbsp;<input type="text" name="mei" value="<?php print($mei); ?>">&nbsp;<全角文字></td>
					</tr>
					<tr>
						<th>会員番号<span class="req">必須</span></th>
						<td><?php print($no_err); ?>
								<input type="text" name="no" value="<?php print($no); ?>">&nbsp;<半角数字5文字></td>
					</tr>
					<tr>
						<th>性別<span class="req">必須</span></th>
						<td><?php print($seibetsu_err); ?>
								<input type="radio" name="seibetsu" value="M"<?php if($seibetsu === 'M'){print(' checked'); } ?>>男性
								<input type="radio" name="seibetsu" value="F"<?php if($seibetsu === 'F'){print(' checked'); } ?>>女性</td>
					</tr>
					<tr>
						<th>好きな料理（複数選択可）<br><span class="req">必須</span></th>
						<td><?php print($cook_err); ?>
								<input type="checkbox" name="cook[]" value="1"<?php foreach($cook as $value){if($value === "1"){print(' checked'); break; }} ?>>和食<br>
								<input type="checkbox" name="cook[]" value="2"<?php foreach($cook as $value){if($value === "2"){print(' checked'); break; }} ?>>中華<br>
								<input type="checkbox" name="cook[]" value="3"<?php foreach($cook as $value){if($value === "3"){print(' checked'); break; }} ?>>イタリアン<br>
								<input type="checkbox" name="cook[]" value="4"<?php foreach($cook as $value){if($value === "4"){print(' checked'); break; }} ?>>フレンチ</td>
					</tr>
					<tr>
						<th>年代<span class="req">必須</span></th>
						<td><?php print($nendai_err); ?>
							<select name="nendai">
								<option value=""<?php if($nendai===""){print(' selected'); } ?>>選択して下さい</option>
								<option value="1"<?php if($nendai==="1"){print(' selected'); } ?>>10～19歳</option>
								<option value="2"<?php if($nendai==="2"){print(' selected'); } ?>>20～29歳</option>
								<option value="3"<?php if($nendai==="3"){print(' selected'); } ?>>30～39歳</option>
								<option value="4"<?php if($nendai==="4"){print(' selected'); } ?>>40～49歳</option>
								<option value="5"<?php if($nendai==="5"){print(' selected'); } ?>>50～59歳</option>
								<option value="6"<?php if($nendai==="6"){print(' selected'); } ?>>60歳以上</option>
							</select></td>
					</tr>
					<tr>
						<th>ご意見<span class="req" >必須</span></th>
						<td><?php print($iken_err); ?><textarea name="iken"><?php print($iken); ?></textarea></td>
					</tr>
				</table>
				<div class="btnbox">
					<input type="submit" value="入力内容を確認する >">
				</div>
			</form>
		</main>
		
		<footer>
			<p class="copyright"><small>(C) 2020 自分の名前</small></p>
		</footer>
	</body>
</html>