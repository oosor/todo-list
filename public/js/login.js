(function () {
    document.getElementById('timezone').value = jstz.determine().name();
}());