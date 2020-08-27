<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/path/semantic.min.css"/>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/components/icon.min.css'>
    <script src="/path/jquery.min.js"></script>
    <script src="/path/semantic.min.js"></script>

    <link rel="shortcut icon" href="logo.png" type="image/png">
    <title>Document</title>
</head>
<body style="background-image: url('/background.png');">
<br>
<div class="container ui">
    <?
    print_r($_COOKIE);
    $tabNumber = null;
    if(isset($_COOKIE['errorLogin']))
        $tabNumber = 0;
    elseif(isset($_COOKIE['errorRegistration']))
        $tabNumber = 1;
    else
        $tabNumber = 2;
    ?>
    <div class="ui two top attached tabular buttons">
        <div class="ui button orange <? if($tabNumber == 0 || $tabNumber == 2) echo "active"; ?>" data-tab="1" >Авторизация</div>
        <div class="ui button orange <? if($tabNumber == 1) echo "active"; ?>" data-tab="2" >Регистрация</div>
    </div>

    <? if(!isset($_COOKIE['userLogin'])) { ?>
        <form class="ui attached tab segment form <? if($tabNumber == 0 || $tabNumber == 2) echo "active"; ?>" data-tab="1" id="loginUser">

            <h4 class="ui dividing header">Данные авторизации</h4>
            <div class=" fields">
                <div class="eight wide field">
                    <label>Логин</label>
                    <input type="text" placeholder="Ваш логин" name="UserLogin" required>
                </div>
                <div class="eight wide field">
                    <label>Пароль</label>
                    <input type="password" placeholder="Ваш пароль" name="UserPassword" required>
                </div>
            </div>
            <div class="field inline">
                <div class="ui slider checkbox">
                    <input type="radio" name="whoAuthorization" value="0" checked="checked">
                    <label>Я студент</label>
                </div>
                <div class="ui slider checkbox">
                    <input type="radio" name="whoAuthorization" value="1">
                    <label>Я преподаватель</label>
                </div>
            </div>
            <? if(isset($_COOKIE['errorAuth'])) {?>
                <div class="ui negative message">
                    <i class="close icon"></i>
                    <div class="header">Ошибка авторизации</div>
                    <ul>
                        <li><p>Логин или пароль неверны</p></li>
                        <li><p>Возможно, вы выбрали не ту роль авторизации</p></li>
                        <li><p>Повторите авторизацию</p></li>
                    </ul>
                </div>
            <? } ?>
            <button type="submit" class="fluid ui blue basic button">Войти</button>

        </form>
    <? } else { ?>
        <div class="segment ui tab attached <? if($tabNumber == 0 || $tabNumber == 2) echo "active"; ?>" data-tab="1">
            <h2 class="ui center aligned icon header orange">
                <i class="circular users icon"></i>
                Вы можете перейти в личный кабинет, так как ваша сессия активна
                <div class="field">
                    <br>
                </div>
                <div class="field">
                    <div class="ui buttons">
                        <a href="<? if($_SESSION['userRole'] == '')?>" class ="ui basic button blue">В личный кабинет</a>
                        <button class="ui basic button red" onclick="callDeleteCookies()">Выйти</button>
                    </div>
                </div>
            </h2>
        </div>
    <? } ?>




    <form class="ui attached  tab segment form <? if($tabNumber == 1) echo "active"; ?>" id="registerUser" data-tab="2">
        <h4 class="ui dividing header">Личные данные</h4>
        <div class=" fields">
            <div class="eight wide field required">
                <label>Имя</label>
                <input type="text" placeholder="Ваше имя" name="UserName" required>
            </div>
            <div class="seven wide field required">
                <label>Фамилия</label>
                <input type="text" placeholder="Ваша фамилия" name="UserSurname" required>
            </div>
            <div class="three wide field required">
                <label>Группа</label>
                <div class="ui selection dropdown labeled icon button fluid" style="background: #2185d0">
                    <input type="hidden" name="UserGroup" required>
                    <i class="users icon" style="color: white"></i>
                    <span class="text" style="color: white">Выбор</span>
                    <div class="menu">
                        <div class="item" data-value="ПРО-318">ПРО-418</div>
                        <div class="item" data-value="ПРО-317">ПРО-417</div>
                        <div class="item" data-value="ПРО-316">ПРО-416</div>
                        <div class="item" data-value="ИБ">ИБ</div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="ui dividing header">Ваши параметры</h4>
        <div class="fields">
            <div class="two wide field required">
                <label>Рост</label>
                <input type="number" placeholder="В см" name="UserHeight" required>
            </div>
            <div class="two wide field required">
                <label>Вес</label>
                <input type="number" placeholder="В кг" name="UserWeight" required>
            </div>
        </div>

        <h4 class="ui dividing header">Данные авторизации</h4>
        <div class="fields">
            <div class="eight wide field required">
                <label>Логин</label>
                <input type="text" placeholder="Только на латинице от 6 до 20 символов" name="UserLogin" minlength="6" maxlength="20" pattern="^[a-zA-Z]+$" onkeyup="this.value=this.value.replace(/^[а-яА-Я\s]+$/,'')" required>
            </div>
            <div class="eight wide field required">
                <label>Пароль</label>
                <input type="password" name="UserPassword"  placeholder="От 6 до 20 символов" minlength="6" maxlength="20" required>
            </div>
        </div>
        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" name="UserAgree" required>
                <label>Я согласен с обработкой персональных данных</label>
            </div>
        </div>
        <? if(isset($_COOKIE['errorRegistration'])) {?>
            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">Ошибка регистрации</div>
                <ul>
                    <li><p>Пользователь с данным логином уже зарегистрирован</p></li>
                    <li><p>Используйте другой логин</p></li>
                </ul>
            </div>
        <? } ?>
        <button type="submit" class="fluid ui blue basic button">Зарегистрироваться</button>
    </form>



</div>



<div class="ui page dimmer" id="loadShow" >
    <div class="ui active dimmer" style="background: #f2711c">
        <h2 class="ui icon header" style="color: white;">
            <i class="lock icon"></i>
            <div class="content">
                Успешная регистрация!
                <div class="sub header">Вы успешно зарегистрировались и теперь можете войти в свой аккаунт</div>
            </div>
            <br>
            <button class="ui button blue" data-tab="1" onclick="hideShow()">Авторизация</button>
        </h2>
    </div>
</div>

</body>
<script>
    $('.message .close')
        .on('click', function() {
            $(this)
                .closest('.message')
                .transition('fade')
            ;
        })
    ;

    $('.buttons .button')
        .tab()
    ;
    $('.ui.dropdown')
        .dropdown()
    ;
    function hideShow(){
        $('#loadShow').dimmer('hide');
    }

    function callDeleteCookies() {
        delete_cookie("userLogin");
        delete_cookie("userRole");
        $(location).attr('href', '/');
    }
    
    function delete_cookie (cookie_name)
    {
        var cookie_date = new Date ( );  // Текущая дата и время
        cookie_date.setTime ( cookie_date.getTime() - 1 );
        document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
    }
    $(document).ready(function () {
        $("#registerUser").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/register.php",
                data: $(this).serialize()
            }).done(function() {
                //$('#loadShow').dimmer('show');
                $(location).attr('href', '/');
            });
            return false;
        });

        $("#loginUser").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/login.php",
                data: $(this).serialize()
            }).done(function() {
                $(location).attr('href', '/');
            });
            return false;
        });
    });
</script>
</html>
