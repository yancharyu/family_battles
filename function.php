<?php

use MyGame\Sex as FamilySex;
// ==========================================
//エラー

// E_STRICTレベル以外のエラーを報告する
error_reporting(E_ALL);
//画面にエラーを表示させるか
ini_set('display_errors', 'On');

// ===========================================
//ログ

//ログを取るかどうか
ini_set('log_errors', 'On');

//ログの出力先ファイルを指定
ini_set('error_log', '/Applications/MAMP/htdocs/my_objective_practice/log/php.log');


// ============================================
// デバッグ

// ＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜
// このデバッグフラグがtrueの時だけログを出力する（開発が終わったらfalseにする）
$debug_flg = true;
// ＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞


//デバッグログ関数
function debug($str, $data = null)
{
    global $debug_flg;

    //デバッグフラグがfalseの時
    if (empty($debug_flg)) {
        return false;
        //$dataの値がnullではない時（第二引数に値が渡されている）
    } elseif (isset($data)) {
        error_log($str . '： ' . print_r($data, true));
        //$dataの値がnullの時（第二引数に値が渡されていない）
    } else {
        error_log($str);
    }
}

// var_dump関数を見やすく表示させる関数
function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

// サニタイズ関数
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


//クラスファイル読み込み
//エラーが出るのでsession_start()より前にrequire_onceをかかないといけない
require_once('family.php');
require_once('monster.php');
require_once('skills.php');
require_once('sex.php');
require_once('history.php');
//セッションを開始
session_start();


//モンスターを生成するクラス
function createMonster()
{
    debug('モンスターを生成します');
    // $monstersはindex.phpで定義済み
    global $monsters, $randMonster;
    $monsters[] = new Monster('ゾウ', 800, Skill::ELEPHANT_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('ライオン', 600, Skill::LION_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('ヘビ', 450, Skill::SNAKE_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('ゴリラ', 350, Skill::GORILLA_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('サメ', 400, Skill::SHARK_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('ワニ', 500, Skill::ALLIGATOR_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('クマ', 400, Skill::BEAR_SKILL, 'img/noimage.png');
    $monsters[] = new Monster('カンガルー', 550, Skill::KANGAROO_SKILL, 'img/noimage.png');

    //配列を一度シャッフルする
    shuffle($monsters);
    // シャッフルした配列からランダムで動物を抜き出す
    $key = array_rand($monsters, 3);
    // 取り出したキーの動物を$monstersから抜き出し変数に格納
    foreach ($key as $key) {
        $randMonster[] = $monsters[$key];
    }

    //モンスターの作成に成功した場合
    if (!empty($randMonster[0])) {
        debug('モンスターの作成に成功');
        debug('作成したモンスター', $randMonster);
        $_SESSION['monster'] = $randMonster;
        $_SESSION['monsterMaxHp'] = $randMonster[0]->getHp();
        History::set($randMonster[0]->getName() . 'があらわれた！');
    } else {
        debug('モンスターの作成に失敗');
    }
}

function createFamily()
{
    debug('家族を構成します');
    global $family;
    $family[] = new Family('母', 600, Skill::MOTHER_SKILL, 'img/noimage.png', 48, FamilySex::WOMAN, 0);
    $family[] = new Family('父', 550, Skill::FATHER_SKILL, 'img/noimage.png', 46, FamilySex::MAN, 1);
    $family[] = new Family('兄', 500, Skill::ANI_SKILL, 'img/noimage.png', 24, FamilySex::MAN, 2);
    $family[] = new Family('弟', 450, Skill::OTOUTO_SKILL, 'img/noimage.png', 22, FamilySex::MAN, 3);
    $family[] = new Family('姉', 530, Skill::ANE_SKILL, 'img/noimage.png', 26, FamilySex::WOMAN, 4);
    $family[] = new Family('妹', 380, Skill::IMOUTO_SKILL, 'img/noimage.png', 20, FamilySex::WOMAN, 5);
    $family[] = new Family('祖母', 300, Skill::GRANDMOTHER_SKILL, 'img/noimage.png', 73, FamilySex::WOMAN, 6);
    $family[] = new Family('祖父', 500, Skill::GRANDFATHER_SKILL, 'img/noimage.png', 69, FamilySex::MAN, 7);

    $_SESSION['family'] = $family;
}

// HP最大値を取得する関数（体力ゲージ用）
function highHp($num)
{
    return $num * 0.48;
}

// HP最小値を取得する関数（体力ゲージ用）
function lowHp($num)
{
    return $num * 0.13;
}

// HP最適値を取得する関数（体力ゲージ用）
function optimum($num)
{
    return $num * 0.9;
}

// 自分のHPが０になった時の処理
function gameOver()
{
    $_SESSION = array();
    $_POST = array();
}

// $_SESSION配列の中の$dataに該当する配列を削除した後に、マージしてインデックスを詰める
function mergeArray($data, $key)
{
    $index = array_search($data, $_SESSION[$key]);
    unset($_SESSION[$key][$index]);
    // unset関数では配列を削除した後のインデックスが詰められないので、array_mergeでインデックスを詰める
    $_SESSION[$key] = array_merge($_SESSION[$key]);
}
