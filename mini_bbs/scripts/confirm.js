function Confirm() {
    var res = confirm("本当に削除しますか？");
    if (res == true) {
        window.location.href = document.getElementById('delete');
    }
}