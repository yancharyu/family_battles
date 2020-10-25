<?php
require('function.php');


// 不正にこのページに遷移してきたら強制的に前のページに戻す
if (!isset($_SESSION['check_flg']) && $_SESSION['check_flg'] !== 'ok') {
    debug('不正なアクセスです');
    debug('トップページに遷移します');
    header('Location:index.php');
    exit();
}



// 家族を生成して格納するための変数
$family = array();
// エラーメッセージの変数
$selectedFamily = array();

if (empty($_SESSION['death_msg'])) {
    // 家族を構成
    createFamily();
}

//POST送信されていた場合
if (!empty($_POST)) {
    debug('POSTされました');
    //キャラクター変更画面の処理
    if (!empty($_SESSION['death_msg']) && !empty($_POST['change_flg']) && isset($_POST['change_family'])) {
        $index = (!empty($_POST['change_family'])) ? $_POST['change_family'] : '';
        $_SESSION['battle_family'] = $_SESSION['family'][$index];
        $_SESSION['familyMaxHp'] = $_SESSION['battle_family']->getHp();
        unset($_SESSION['check_flg']);
        unset($_SESSION['death_msg']);
        $_SESSION['history'] = array();
        header('Location:index.php');
        exit();
    } else {
        debug('選択されていません');
    }

    // はじめのキャラクター選択画面の処理
    // ・値がセットされている
    // ・値が配列である
    // jsから保存されたクッキーの値が空ではないか
    if (isset($_POST['family']) && is_array($_POST['family'])  && !empty($_COOKIE['selected_family'])) {
        // jsで保存された配列が文字列になって入っているので変数に格納
        $keyString = $_COOKIE['selected_family'];
        // 保存された文字列を','で区切り配列にする
        $keyArray = explode(',', $keyString);
        // 取得したキーを順番に取り出し$familyに該当する値を$selectedFamilyに格納する
        foreach ($keyArray as $key) {
            $selectedFamily[] = $family[$key];
        }

        // セッションに戦う家族3人を保存する
        $_SESSION['selected_family'] = $selectedFamily;
        $_SESSION['battle_family'] = $selectedFamily[0];
        // 数字で表示用にセッションにHP最大値を格納
        $_SESSION['familyMaxHp'] = $selectedFamily[0]->getHp();
        createMonster();
        //認証用トークンを削除
        unset($_SESSION['check_flg']);
        // クッキーを削除
        setcookie('selected_family', "", time() - 1800);
        debug('戦闘画面に遷移します');
        header('Location:index.php');
        exit();
    } else {
        debug('3名選択されていません');
    }
    $_POST = array();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>My-game</title>
    <link rel="stylesheet" href="./css/style.css" />
    <style>
        .select-container {
            width: 100%;
            min-height: 900px;
            padding-bottom: 30px;
            background: #000;
            color: #fff;
        }

        .select-wrapper h1,
        .select-wrapper h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .select-wrapper {
            overflow: hidden;
        }

        .select-wrapper .character {
            width: 23%;
            margin-right: 1%;
            margin-left: 1%;
            font-size: 25px;
            margin-top: 30px;
            min-height: 300px;
            line-height: 50px;
            padding: 13px 0;
            border: 1px #fff solid;
            text-align: center;
            box-sizing: border-box;
            position: relative;
            float: left;
        }

        .character {
            position: relative;
        }

        .character .selected-number {
            font-size: 40px;
            position: absolute;
            top: 10%;
            right: 15%;
        }

        .character .img img {
            width: 30%;
            height: 70px;
        }

        .character label {
            width: 100%;
            height: 100%;
        }

        .character input {
            width: 100%;
            height: 100%;
            color: white;
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        .select-wrapper .remain-character {
            width: 23%;
            margin-right: 1%;
            margin-left: 1%;
            font-size: 25px;
            margin-top: 30px;
            min-height: 300px;
            line-height: 50px;
            padding: 13px 0;
            border: 1px #fff solid;
            text-align: center;
            box-sizing: border-box;
            position: relative;
            float: left;
        }

        .remain-character {
            position: relative;
        }

        .remain-character .selected-number {
            font-size: 40px;
            position: absolute;
            top: 10%;
            right: 15%;
        }

        .remain-character .img img {
            width: 30%;
            height: 70px;
        }

        .remain-character label {
            width: 100%;
            height: 100%;
        }

        .remain-character input {
            width: 100%;
            height: 100%;
            color: white;
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        .btn-container {
            text-align: center;
        }

        .btn-container .btn {
            width: 20%;
            font-size: 23px;

        }
    </style>
</head>

<body>
    <div class="select-container">
        <?php
        // $_SESSION['change_flg']がtrueなら
        if (!empty($_SESSION['death_msg']) && count($_SESSION['selected_family'])) : ?>
            <div class="select-wrapper">
                <h1><?php echo (!empty($_SESSION['death_msg'])) ? $_SESSION['death_msg'] : ''; ?></h1>
                <h2>次に戦う人を選んでください</h2>
                <?php foreach ($_SESSION['selected_family'] as $key) : ?>
                    <div class="remain-character">
                        <form action="" method="post">

                            <div class="img"><img src="<?php echo h($key->getImg()); ?>"></div>
                            <span class="selected-number"></span>
                            <!-- キャラクター詳細画面 -->
                            <div class="character-detail">
                                <p>名前： <?php echo h($key->getName()); ?></p>
                                <p>HP： <?php echo h($key->getHp()); ?></p>
                                <p>性別： <?php echo h($key->getSex()); ?></p>
                                <p>年齢： <?php echo h($key->getAge()); ?>歳</p>
                            </div>
                            <!-- チェックボックス -->
                            <input type="hidden" name="change_flg" value="change">
                            <input type="checkbox" name="change_family" value="<?php echo $key->getVal(); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="btn-container">
                <input type="submit" value="選択してください" class="btn remain-character-submit" name="submit" disabled>
            </div>
            </form>
            <!-- 家族の生成に成功していたら -->
        <?php elseif (!empty($family[0])) : ?>
            <div class="select-wrapper">
                <h1>キャラクター選択画面</h1>
                <h2>キャラクターを3名選択してください</h2>

                <!-- foreachで家族の情報を出力していく -->
                <?php foreach ($family as $key) : ?>
                    <div class="character">
                        <form action="" method="post">
                            <div class="img"><img src="<?php echo h($key->getImg()); ?>"></div>
                            <span class="selected-number"></span>
                            <!-- キャラクター詳細画面 -->
                            <div class="character-detail">
                                <p>名前： <?php echo h($key->getName()); ?></p>
                                <p>HP： <?php echo h($key->getHp()); ?></p>
                                <p>性別： <?php echo h($key->getSex()); ?></p>
                                <p>年齢： <?php echo h($key->getAge()); ?>歳</p>
                            </div>
                            <!-- チェックボックス -->
                            <input type="checkbox" name="family[]" value="<?php echo $key->getVal(); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="btn-container">
                <input type="submit" value="3名選択してください" class="btn character-submit" name="submit" disabled>
            </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script src="jquery-cookie-master/src/jquery.cookie.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>