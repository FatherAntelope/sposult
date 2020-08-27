<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form id="myForm">
    <input type="hidden" name="myHidden" value="myHiddenValue">
    Ваше имя: <input type="text" name="myName" value="myNameValue"><br>
    Ваш возраст: <input type="text" name="myAge" value="myAgeValue"><br>
    <input type="button" name="myButton1" value="myButton1value">
    <input type="button" name="myButton2" value="myButton2value">
    <input type="button" name="myButton3" value="myButton3value">
</form>

<script>
    $('#myForm input[type=button]').click(function() {
        $.ajax({
            type: 'POST',
            url: '/deleteData.php',
            data: $('#myForm').serialize() + '&' + this.name + '=' + this.value,
            success: function(data) {
                console.log(data);
                $(location).attr('href', '/data.php');
            }
        });
    });
</script>

</body>
</html>