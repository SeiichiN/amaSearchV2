// aboutMenu.js
// index.phpとリンク。

var htmldoc = document.getElementById('overlay-aboutMenu');

function openWin() {
    htmldoc.style.display = 'block';
    htmldoc.style.animation = 'showWin .5s ease 0s 1 normal';
}
function closeWin() {
    htmldoc.style.display = 'none';
}

document.getElementById('aboutMenu').onclick = openWin;
document.getElementById('overlay-aboutMenu').onclick = closeWin;
