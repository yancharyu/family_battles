<?php
require_once('creature.php');

interface MonsterInterface
{ }

class Monster extends Creature implements MonsterInterface
{
    //モンスターカウントを変数に格納する
    public static $monsterCount = 0;
    public function __construct($name, $hp, $skills, $img)
    {
        parent::__construct($name, $hp, $skills, $img);
        // インスタンスが生成される度にカウントする
        self::$monsterCount++;
    }

    function selectSkill($skills)
    {
        // /技の中からランダムに一つキーを取得
        $key = array_rand($skills, 1);
        // 取得したキーの技を$skillに代入
        $_SESSION['monster_skill'] = $skills[$key];
    }
}
