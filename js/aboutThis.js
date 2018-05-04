// aboutThis.js
// login.phpとリンク。

var htmldoc = document.getElementById('overlay-aboutThis');

function openWin() {
    htmldoc.style.display = 'block';
    htmldoc.style.animation = 'showWin .5s ease 0s 1 normal';
}
function closeWin() {
    htmldoc.style.display = 'none';
}

document.getElementById('aboutThis').onclick = openWin;
document.getElementById('overlay-aboutThis').onclick = closeWin;

