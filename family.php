<?php
require_once('creature.php');

interface FamilyInterface
{
    public function getAge();
    public function getSex();
    public function getVal();
}
class Family extends Creature implements FamilyInterface
{
    //モンスターカウントを変数に格納する

    public static $familyCount = 0;
    protected $age;
    protected $sex;
    protected $val;
    public function __construct($name, $hp, $skills, $img, $age, $sex, $val)
    {
        // インスタンスが生成される度にカウントする
        self::$familyCount++;
        parent::__construct($name, $hp, $skills, $img);
        $this->age = $age;
        $this->sex = $sex;
        $this->val = $val;
    }

    function getAge()
    {
        return $this->age;
    }

    function getSex()
    {
        return $this->sex;
    }

    function getVal()
    {
        return $this->val;
    }
}
