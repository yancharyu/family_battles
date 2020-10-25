$(function () {

  // 画面がロードされてから2秒はsubmitできなくする
  var $submit = $('button[type="submit"]');
  var $selectMsgChi = $('.js-select-msg').children('p');
  var $selectMsg = $('.js-select-msg');
  // 連打防止のため画面がリロードされてから2秒はsubmitさせなくする
  $submit.prop('disabled', true);
  // 2秒後に処理をセット
  setTimeout(() => {
    $selectMsgChi.css('opacity', '1')
    $selectMsg.css('width', '50%');
    $submit.prop('disabled', false);
  }, 1600);


  var $btnAttack = $('input[name="attack"]');
  $btnAttack.on('click', function () {
    console.log('施工');
    $selectMsg.toggle();
  });

  // 文字にカーソルがあったら横に矢印を表示する
  $('.select-box').find('input').hover(function () {
    $(this).siblings('span').find('.fa-caret-right').css('opacity', '1');
  }, function () {
    $(this).siblings('span').find('.fa-caret-right').css('opacity', '0');
  });


  //チェックボックスがチェックされたら背景色を変更する
  var $checkbox = $('.character input[type="checkbox"]');
  $checkbox.on('click', function () {
    if ($(this).prop('checked') === true) {
      $(this).parents('.character').css({ 'background': '#0000cd' });
    } else {
      $(this).parents('.character').css({ 'background': '#000' });
    }
  });
  //キャラクター選択画面で三体以上選択できないようにする
  //チェックボックスをクリックするとイベント発火
  $(".character input[type=checkbox]").click(function () {
    var $count = $(".character input[type=checkbox]:checked").length;
    var $not = $('.character input[type=checkbox]').not(':checked');
    var $notParent = $('.character input[type=checkbox]').not(':checked').parents('.character');
    var $submit = $('.character-submit');

    //チェックが3つ付いたら、チェックされてないチェックボックスにdisabledを加える
    if ($count >= 3) {
      $not.attr("disabled", true);
      $notParent.css({ 'background': '#808080', 'opacity': '.3' });
      $submit.val('決定する');
      $submit.prop('disabled', false);
    } else {
      //3つ以下ならdisabledを外す
      $not.attr("disabled", false);
      $notParent.css({ 'background': '#000', 'opacity': '1' });
      $submit.val('3名選択してください');
      $submit.prop('disabled', true);


    }
  });

  // 上の処理の選択画面バージョン（処理はほぼ同じ）
  // 一人しか選択できない
  //チェックボックスがチェックされたら背景色を変更する
  var $checkbox = $('.remain-character input[type="checkbox"]');
  // チェックボックスが押されたとき
  $checkbox.on('click', function () {
    // チェックが入ったときと外れた時で背景色をかえる
    if ($(this).prop('checked') === true) {
      $(this).parents('.remain-character').css({ 'background': '#0000cd' });
    } else {
      $(this).parents('.remain-character').css({ 'background': '#000' });
    }
  });
  $(".remain-character input[type=checkbox]").click(function () {
    var $count = $(".remain-character input[type=checkbox]:checked").length;
    var $not = $('.remain-character input[type=checkbox]').not(':checked');
    var $notParent = $('.remain-character input[type=checkbox]').not(':checked').parents('.remain-character');
    var $submit = $('.remain-character-submit');

    //チェックが1つ付いたら、チェックされてないチェックボックスにdisabledを加える
    if ($count >= 1) {
      $not.attr("disabled", true);
      // 背景色変更
      $notParent.css({ 'background': '#808080', 'opacity': '.3' });
      $submit.val('決定する');
      $submit.prop('disabled', false);

    } else {
      //1つ以下ならdisabledを外す
      $not.attr("disabled", false);
      $notParent.css({ 'background': '#000', 'opacity': '1' });
      $submit.val('選択してください');
      $submit.prop('disabled', true);
    }
  });


  var $input = $('.character input');
  var $hidden1 = $('.select-wrapper .hidden1');
  var $hidden2 = $('.select-wrapper .hidden2');
  var $hidden3 = $('.select-wrapper .hidden3');
  // チェックが入ったcheckboxの値を格納するselect.phpに送信するための配列
  var orders = [];
  // チェックが入ったcheckboxのDOM自身を格納する配列
  var $inputSelected = [];


  // 選んだ順をキャラクター画面に数字として表示したいけど今は無理やからリファクタリング待ち
  // チェックボックスの値が変更された時
  $input.on('change', function (e) {
    var $selectedNum = $(this).siblings('.selected-number');
    //チェックが入ったらordersにチェックされたvalue値を格納
    if (e.target.checked) {
      orders.push(e.target.value);
      $inputSelected.push($(this));
      //チェックが外れたらordersから削除する
    } else {
      $selectedNum.text('');
      for (var k = 0; k < orders.length; k++) {
        if (orders[k] == e.target.value) {
          orders.splice(k, 1);
        }
      }

      for (var k = 0; k < $inputSelected.length; k++) {
        if ($inputSelected[k][0].value == e.target.value) {
          $inputSelected.splice(k, 1);
        }
      }
    }

    if ($inputSelected[0]) {

      for (key in $inputSelected) {
        $selectedNum = $inputSelected[key].siblings('.selected-number');
        $selectedNum.text(orders.indexOf($inputSelected[key][0].value) + 1);
      }
    }

  });

  // submitされたらorders配列をクッキーに保存する
  var $submitForm = $('.character form');
  $submitForm.on('submit', function (e) {
    $.cookie('selected_family', orders);
  });


  // var $historyBox = $('.js-history-box')
  // // テキストを<span></span>で囲む
  // $historyMsg.children().addBack().contents().each(function () {
  //   if (this.nodeType == 3) {
  //     $(this).replaceWith($(this).text().replace(/(\S)/g, '<span>$1</span>'));
  //   }
  // });
  // // 一文字ずつフェードインさせる
  // $historyMsg.css({ 'opacity': 1 });
  // for (var i = 0; i <= $historyMsg.children().length; i++) {
  //   $historyMsg.children('span:eq(' + i + ')').delay(30 * i).animate({ 'opacity': 1 }, 0);
  // };
  // テキストを<span></span>で囲む
  var $historyMsg = $('.js-history-msg');
  $historyMsg.children().addBack().contents().each(function () {
    if (this.nodeType == 3) {
      $(this).replaceWith($(this).text().replace(/(\S)/g, '<span>$1</span>'));
    }
  });
  // 一文字ずつフェードインさせる
  $historyMsg.css({ 'opacity': 1 });
  for (var i = 0; i <= $historyMsg.children().length; i++) {
    $historyMsg.children('span:eq(' + i + ')').delay(30 * i).animate({ 'opacity': 1 }, 0);
  };
});

//   return function () {
//   }
// }