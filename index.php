<?php
require_once('function.php');


//モンスターを生成し格納するための変数
$monsters = array();
// 残りの味方の数
$familyCount = (!empty($_SESSION['selected_family'][0])) ? count($_SESSION['selected_family']) : 0;
// 戦う家族
$battleFamily = (!empty($_SESSION['battle_family'])) ? $_SESSION['battle_family'] : '';
//残りのモンスターの数
$monsterCount = (!empty($_SESSION['monster'])) ? count($_SESSION['monster']) : 0;
// 戦うモンスター
$battleMonster = (!empty($_SESSION['monster'][0])) ? ($_SESSION['monster'][0]) : '';
$endMsg = (!empty($_SESSION['end_msg'])) ? $_SESSION['end_msg'] : '';

// スタートボタンが押された時の処理
if (!empty($_POST['start']) && isset($_POST['check_flg'])) {
    debug('スタートボタンが押されました');
    // select.php用の認証トークンを生成
    $_SESSION['check_flg'] = $_POST['check_flg'];
    $_POST = array();
    header('Location:select.php');
    exit();
}



// 技ボタンのどれかが押された時の処理
if (isset($_POST['skill'])) {
    // 履歴を残しておくための配列
    $_SESSION['history'] = array();
    // 押された技はインデックスとして帰ってくるのでそのキーを使い、$battleFamilyから取り出し変数に格納する
    $key = $_POST['skill'];
    // 格納したキーを使い、技から該当するキーの技を取得し、変数に格納
    $attackSKill = $battleFamily->getSkills()[$key];
    debug('選択された技', $attackSKill);

    // 攻撃する
    $battleFamily->attack($battleMonster, $attackSKill);
    // 敵のHPが０になったら
    if ($battleMonster->getHp() <= 0) {
        debug('敵のHPがゼロになった');
        History::set($battleMonster->getName() . 'を倒した！');
        mergeArray($battleMonster, 'monster');
        $monsterCount = (!empty($_SESSION['monster'])) ? count($_SESSION['monster']) : 0;
        // 敵の残り数も０になった時
        if ($monsterCount <= 0) {
            debug('敵の数が０になりました');
            gameOver();
            $_SESSION['end_msg'] = 'あなたの勝ちです';
            header('Location:index.php');
            exit();
        }
        $battleMonster = (!empty($_SESSION['monster'][0])) ? ($_SESSION['monster'][0]) : '';
        $_SESSION['monsterMaxHp'] = $battleMonster->getHp();
        History::set($battleMonster->getName() . 'があらわれた！');
        header('Location:index.php');
        exit();
    }
    //敵の技をランダムに取得する
    $battleMonster->selectSkill($battleMonster->getSkills());
    // 敵の攻撃
    $battleMonster->attack($battleFamily, $_SESSION['monster_skill']);
    // 自分のHPが０になったら
    if ($battleFamily->getHp() <= 0) {
        debug($battleFamily->getName() . 'は倒れた!');
        $_SESSION['death_msg'] = $battleFamily->getName() . 'は倒れた!';
        $_SESSION['check_flg'] = (!empty($_POST['check_flg'])) ? $_POST['check_flg'] : '';
        mergeArray($battleFamily, 'selected_family');
        $familyCount = (!empty($_SESSION['selected_family'][0])) ? count($_SESSION['selected_family']) : 0;
        // 戦える家族がいない場合
        if ($familyCount <= 0) {
            debug('敵の数が０になりました');
            gameOver();
            $_SESSION['end_msg'] = 'あなたの負けです';
            header('Location:index.php');
            exit();
        }
        header('Location:select.php');
        exit();
    }
    $_POST = array();
}



// にげるボタンが押された時
if (!empty($_POST['run'])) {
    gameOver();
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
</head>

<body>
    <div class="screen-width">
        <?php if (empty($_SESSION['monster'])) : ?>
            <div class="start-screen">
                <div style="color:white;"><?php echo (!empty($endMsg)) ? $endMsg : ''; ?></div>
                <form action="" method="post" name="post">
                    <input type="hidden" name="check_flg" value="ok">
                    <input type="submit" value="CLICKでゲームスタート" name="start" class="start">
                </form>
            </div>
        <?php else : ?>
            <div class="enemy-status">
                <div class="monster-count">モンスターの数 × <?php echo $monsterCount; ?></div>
                <span class="enemy-name"><?php echo $battleMonster->getName(); ?></span>
                <div class="enemy-health">
                    <div class="enemy-hp">HP</div>
                    <meter min=0 max=<?php echo $_SESSION['monsterMaxHp']; ?> value=<?php echo $battleMonster->getHp(); ?> low=<?php echo lowHp($_SESSION['monsterMaxHp']); ?> high=<?php echo highHp($_SESSION['monsterMaxHp']); ?> optimum=<?php echo optimum($_SESSION['monsterMaxHp']); ?>></meter>
                </div>
            </div>
            <div class="grass-field1"></div>
            <div class="enemy-img">
                <img src="<?php echo $battleMonster->getImg(); ?>" />
            </div>
            <div class="my-status">
                <div class="family-count">家族の数 × <?php echo $familyCount; ?></div>
                <span class="my-name"><?php echo $battleFamily->getName(); ?></span>
                <div class="my-health">
                    <div class="my-hp">HP</div>
                    <meter min=0 max=<?php echo $_SESSION['familyMaxHp']; ?> value=<?php echo $battleFamily->getHp(); ?> low=<?php echo lowHp($_SESSION['familyMaxHp']); ?> high=<?php echo highHp($_SESSION['familyMaxHp']); ?> optimum=<?php echo optimum($_SESSION['familyMaxHp']); ?>></meter>
                </div>
                <div class="health-point">
                    <span class="health100"><?php echo $battleFamily->getHp(); ?> </span>/ <?php echo $_SESSION['familyMaxHp']; ?>
                </div>
            </div>
            <div class="grass-field2"></div>
            <div class="family"><img src="<?php echo $battleFamily->getImg(); ?>" /></div>
            <div class="js-select-msg">
                <p><?php echo $battleFamily->getName(); ?>はどうする?</p>
            </div>
            <form action="" method="post" class="js-skills">
                <input type="hidden" name="family-hp" value="<?php echo $battleFamily->getHp(); ?>">
                <input type="hidden" name="monster-hp" value="<?php echo $battleFamily->getHp(); ?>" class="superbeaver">
                <input type="hidden" name="check_flg" value="ok">
                <button type="submit" value="0" class="skill" name="skill" <?php echo (!empty($battleFamily->getSkills()[0]['name'])) ? $battleFamily->getSkills()[0]['name'] : 'ーーーーーー'; ?> <?php if (empty($battleFamily->getSkills()[0])) echo 'disabled'; ?>><?php echo $battleFamily->getSkills()[0]['name']; ?></button>
                <button type="submit" value="1" class="skill" name="skill" <?php echo (!empty($battleFamily->getSkills()[1]['name'])) ? $battleFamily->getSkills()[1]['name'] : 'ーーーーーー'; ?> <?php if (empty($battleFamily->getSkills()[1])) echo 'disabled'; ?>><?php echo $battleFamily->getSkills()[1]['name']; ?></button>
                <button type="submit" value="2" class="skill" name="skill" <?php if (empty($battleFamily->getSkills()[2])) echo 'disabled'; ?>><?php echo (!empty($battleFamily->getSkills()[2]['name'])) ? $battleFamily->getSkills()[2]['name'] : 'ーーーーーー'; ?></button>
                <button type="submit" value="3" class="skill" name="skill" <?php if (empty($battleFamily->getSkills()[3])) echo 'disabled'; ?>><?php echo (!empty($battleFamily->getSKills()[3]['name'])) ? $battleFamily->getSkills()[3]['name'] : 'ーーーーーー'; ?></button>
            </form>
            <!-- <div class="js-bag-items">
        <ul>
          <li class="bag-item">マスターボール</li>
          <li class="bag-item">鞭</li>
          <li class="bag-item">同人誌</li>
        </ul>
      </div> -->
            <form action="" method="post" class="select-box">
                <div>
                    <span class="triangle"><i class="fas fa-caret-right" aria-hidden="true"></i>
                    </span>
                    <input type="button" value="攻撃" name="attack">
                </div>
                <div>
                    <span class="triangle"><i class="fas fa-caret-right" aria-hidden="true"></i>
                    </span>
                    <input type="button" value="持ち物" name="item">
                </div>
                <div>
                    <span class="triangle"><i class="fas fa-caret-right" aria-hidden="true"></i>
                    </span>
                    <input type="submit" value="交代" name="change">
                </div>
                <div>
                    <span class="triangle"><i class="fas fa-caret-right" aria-hidden="true"></i>
                    </span>
                    <input type="submit" value="にげる" name="run">
                </div>
            </form>
    </div>
    <div class="js-history-box">
        <?php
            if (!empty($_SESSION['history'][0])) :
                foreach ($_SESSION['history'] as $key) :
                    ?>
                <p class="js-history-msg"><?php echo $key; ?></p>
        <?php endforeach;
            endif;
            ?>
    </div>
<?php endif; ?>
<script src="https://kit.fontawesome.com/39cb22de6d.js" crossorigin="anonymous"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<script src="./js/main.js"></script>
</body>

</html>