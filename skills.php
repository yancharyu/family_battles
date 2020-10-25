<?php

class Skill
{
    // モンスターの技
    const ELEPHANT_SKILL = [
        array(
            'name' => '踏みつける',
            'damage' => 150,
            //確率
            'prob' => 80
        ),
        array(
            'name' => '鼻でしばく',
            'damage' => 120,
            'prob' => 90,
        ),
        array(
            'name' => '舐める',
            'damage' => 50,
            'prob' => 100,
        ),
    ];

    const LION_SKILL = [
        array(
            'name' => '威嚇',
            'damage' => 5,
            'prob' => 100,
        ),
        array(
            'name' => '噛みつく',
            'damage' => 130,
            'prob' => 80,
        ),
        array(
            'name' => '噛みこ○す',
            'damage' => 200,
            'prob' => 75,
        ),
    ];

    const SNAKE_SKILL = [
        array(
            'name' => 'しめつける',
            'damage' => 95,
            'prob' => 90,
        ),
        array(
            'name' => '噛みつく',
            'damage' => 130,
            'prob' => 85,
        ),
        array(
            'name' => '毒のキバ',
            'damage' => 180,
            'prob' => 75,
        ),
    ];

    const GORILLA_SKILL = [
        array(
            'name' => '殴る',
            'damage' => 130,
            'prob' => 80,
        ),
        array(
            'name' => 'にぎり潰す',
            'damage' => 140,
            'prob' => 70
        ),
        array(
            'name' => 'ける',
            'damage' => 88,
            'prob' => 95,
        ),
        array(
            'name' => '叩きつける',
            'damage' => 65,
            'prob' => 90,
        ),
    ];

    const SHARK_SKILL = [
        array(
            'name' => '食いちぎる',
            'damage' => 190,
            'prob' => 65,
        ),
        array(
            'name' => '尻尾アタック',
            'damage' => 87,
            'prob' => 90,
        ),
        array(
            'name' => 'とっしん',
            'damage' => 60,
            'prob' => 95,
        ),
    ];

    const ALLIGATOR_SKILL = [
        array(
            'name' => '噛みつく',
            'damage' => 106,
            'prob' => 90,
        ),
        array(
            'name' => '食いやぶる',
            'damage' => 180,
            'prob' => 70,
        ),
        array(
            'name' => '尻尾アタック',
            'damage' => 100,
            'prob' => 85,
        ),
    ];

    const BEAR_SKILL = [
        array(
            'name' => '引きちぎる',
            'damage' => 110,
            'prob' => 75,
        ),
        array(
            'name' => '噛みつく',
            'damage' => 125,
            'prob' => 95,
        ),
        array(
            'name' => 'ひっかく',
            'damage' => 90,
            'prob' => 100,
        ),
    ];

    const KANGAROO_SKILL = [
        array(
            'name' => 'ボコ殴り',
            'damage' => 105,
            'prob' => 85,
        ),
        array(
            'name' => 'アッパー',
            'damage' => 155,
            'prob' => 80,
        ),
        array(
            'name' => '右ストレート',
            'damage' => 110,
            'prob' => 100,
        ),
    ];

    //家族の技
    const MOTHER_SKILL = [
        array(
            'name' => '掃除機でなぐる',
            'damage' => 120,
            'prob' => 85,
        ),
        array(
            'name' => '食器を投げる',
            'damage' => 900,
            'prob' => 85,
        ),
        array(
            'name' => 'お尻を叩く',
            'damage' => 200,
            'prob' => 75,
        ),
    ];

    const FATHER_SKILL = [
        array(
            'name' => 'ドロップキック',
            'damage' => 110,
            'prob' => 70,
        ),
        array(
            'name' => '靴下を投げる',
            'damage' => 70,
            'prob' => 90,
        ),
        array(
            'name' => '一撃必殺',
            'damage' => 100000000,
            'prob' => 10,
        ),
        array(
            'name' => 'ぶん殴る',
            'damage' => 150,
            'prob' => 60,
        ),
    ];

    const ANI_SKILL = [
        array(
            'name' => '蹴る',
            'damage' => 120,
            'prob' => 85,
        ),
        array(
            'name' => 'バットで殴る',
            'damage' => 150,
            'prob' => 70,
        ),
        array(
            'name' => '叩く',
            'damage' => 90,
            'prob' => 95,
        ),
    ];

    const OTOUTO_SKILL = [
        array(
            'name' => 'ラケットで殴る',
            'damage' => 100,
            'prob' => 75,
        ),
        array(
            'name' => 'とっしん',
            'damage' => 80,
            'prob' => 90,
        ),
        array(
            'name' => 'ボールを投げる',
            'damage' => 150,
            'prob' => 80,
        ),
    ];

    const ANE_SKILL = [
        array(
            'name' => '思いっきりビンタ',
            'damage' => 120,
            'prob' => 65,
        ),
        array(
            'name' => '蹴る',
            'damage' => 90,
            'prob' => 80,
        ),
        array(
            'name' => '本を投げる',
            'damage' => 50,
            'prob' => 95,
        ),
        array(
            'name' => '包丁で刺す',
            'damage' => 150,
            'prob' => 75,
        ),
    ];

    const IMOUTO_SKILL = [
        array(
            'name' => 'おうふくビンタ',
            'damage' => 130,
            'prob' => 70,
        ),
        array(
            'name' => '一撃必殺',
            'damage' => 100000000000,
            'prob' => 10,
        ),
        array(
            'name' => '叩く',
            'damage' => 80,
            'prob' => 95,
        ),
    ];

    const GRANDMOTHER_SKILL = [
        array(
            'name' => 'おばあちゃんの味',
            'damage' => 100,
            'prob' => 95,
        ),
        array(
            'name' => '針でさす',
            'damage' => 120,
            'prob' => 90,
        ),
        array(
            'name' => 'ひっぱたく',
            'damage' => 95,
            'prob' => 85,
        ),
    ];

    const GRANDFATHER_SKILL = [
        array(
            'name' => '一撃必殺',
            'damage' => 10000000000,
            'prob' => 5,
        ),
        array(
            'name' => '杖で叩く',
            'damage' => 110,
            'prob' => 70,
        ),
        array(
            'name' => '入歯を投げる',
            'damage' => 150,
            'prob' => 100,
        ),
    ];
}
