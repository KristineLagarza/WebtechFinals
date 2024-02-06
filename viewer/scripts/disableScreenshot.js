// Javascript to disable screenshot
document.addEventListener('keydown', function (e) {
    if (e.key === 'PrintScreen') {
        e.preventDefault();
        alert("Screenshot is disabled.");
    }
});

window.addEventListener('keyup', function (e) {
    if (e.key === 'PrintScreen') {
        e.preventDefault();
        alert("Screenshot is disabled.");
    }
});