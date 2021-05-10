function outputDeletePopup() {   
    var res = confirm('本当に削除しますか');
    
    if (res === true) {
        return true;
    } else {
        return false;
    }
}