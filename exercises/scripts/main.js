function outputConfirmationPopup() {   
    var res = confirm('本当に削除しますか');
    
    if (res === true) {
        return true;
    } else {
        return false;
    }
}

// // チェックボックスの取得
// const btn = document.querySelector("#btn-mode");

// // チェックした時の挙動
// btn.addEventListener("change", () => {
//   if (btn.checked == true) {
//     // ダークモード
//     document.body.classList.add("darkmode");
//   } else {
//     // ライトモード
//     document.body.classList.remove("darkmode");
//   }
// });