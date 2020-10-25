<?php
require_once('function.php');
require_once('history.php');

interface CreatureInterface
{
    public function getName();
    public function setHp($num);
    public function getHp();
    public function getImg();
    public function getSkills();
    public function attack($target, $skill);
}

abstract class Creature implements CreatureInterface
{
    protected $name;
    protected $hp;
    protected $skills;
    protected $img;

    public function __construct($name, $hp, $skills, $img)
    {
        $this->name = $name;
        $this->hp = $hp;
        $this->skills = $skills;
        $this->img = $img;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($str)
    {
        $this->name = $str;
    }

    public function getHp()
    {
        return $this->hp;
    }

    public function setHp($num)
    {
        $this->hp = $num;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function getSkills()
    {
        return $this->skills;
    }

    function attack($target, $skill)
    {
        // 技名、ダメージ数、確率をそれぞれ変数に格納
        $skillName = $skill['name'];
        $skillDamage = $skill['damage'];
        $skillProb = $skill['prob'];
        // 確率で技が外れるようにする
        History::set($this->name . 'の' . $skillName . '!');
        //技の確率がランダムで生成される数字より大きければ攻撃を行う
        if ($skillProb >= mt_rand(0, 99)) {
            // 10分の1の確率でクリティカルヒットを行う
            if (empty(mt_rand(0, 9))) {
                History::set('急所にあたった！');
                $skillDamage = round($skillDamage * 1.5);
            }
            History::set($target->getName() . 'は' . $skillDamage . 'ダメージを受けた！');
            $target->setHp($target->getHp() - $skillDamage);
            // 技が外れた時の処理
        } else {
            debug('技が外れた');
            History::set('しかし技が外れた！');
        }
    }
}
