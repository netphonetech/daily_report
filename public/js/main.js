$(document).ready(function () {
    $('#many').DataTable();
})

function password_confirm() {
    var password = $('#password').val();
    var confirm_password = $('#confirm_password').val();
    if (password.length < 4) {
        return alert('Password too short')
    } else if (password.length > 20) {
        return alert('Password too long')
    } else if (password == confirm_password) {
        var data = { password: password }
        $.post('password_change.php', data, function (data, status) {
            if (data == 'fail') {
                window.location.href = 'index.php?message=something went wrong, Password not changed&type=fail'
            } else if (data == 'success') {
                window.location.href = 'index.php?message=Password changed successfully&type=success'
            } else {
                alert('something went wrong, refresh and try again')
            }
        })
    } else {
        return alert('Password do not match!')
    }
}