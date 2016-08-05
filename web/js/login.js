$(function() {

    function toastSuccess(message) {
        $.toaster({message : message, title : '', priority : 'success'});
    }

    function toastError(message) {
        $.toaster({message : message, title : '', priority : 'danger'});
    }

    var mobileLoginFrom = $('#mobile-login-form');
    var accountLoginForm = $('#account-login-form');

    ////////////////////////////////////////////
    ////////       切换登录方式      /////////////
    ////////////////////////////////////////////
    var speed = 400;
    $('#to-account-login').click(function(){
        mobileLoginFrom.fadeTo(speed, 0.01).hide();
        accountLoginForm.fadeTo(speed, 1).show();
    });
    $('#to-mobile-login').click(function(){
        accountLoginForm.fadeTo(speed, 0.01).hide()
        mobileLoginFrom.fadeTo(speed, 1).show();
    });


    ////////////////////////////////////////////
    ////////       发送验证码      /////////////
    ////////////////////////////////////////////
    $('button[name="send-sms-btn"]', mobileLoginFrom).click(function() {
        var phone = $('input[name="phone"]', mobileLoginFrom).val();
        if (phone == '') {
            toastError('手机号不能为空');
            return false;
        }

        var $this = $(this);

        // 发送验证码的 url
        var sendSmsUrl = $(this).attr('data-url');

        // 通过ajax发送短信验证码
        $.ajax({
            url : sendSmsUrl,
            type : 'post',
            data : {phone : phone},
            dataType : 'json',
            beforeSend : function() {
                // 避免重复点击
                $this.attr('disabled', true);
            }
        }).done(function (data) {
            if (data.status === 'ok') {
                toastSuccess('短信发送成功,请注意查收...');

                $this.html('<em>60</em> 秒后可重发');
                $this.find('em').countdown((new Date()).getTime() + 59000, function (event) {
                    $(this).text(event.strftime('%S'));
                }).on('finish.countdown', function(event) {
                    $this.attr('disabled', false);
                    $this.text('重新发送');
                });
            } else {
                $this.attr('disabled', false);

                toastError(data.message);
            }
        });
    });


    ////////////////////////////////////////////
    ////////       使用手机号登录      ////////////
    ////////////////////////////////////////////
    $('button[name="login-btn"]', mobileLoginFrom).click(function() {
        var phone = $('input[name="phone"]', mobileLoginFrom).val();
        var code = $('input[name="code"]', mobileLoginFrom).val();
        var remember = $('input[name="remember"]', mobileLoginFrom).is(':checked') ? 1 : 0;
        if (phone == '') {
            toastError('手机号不能为空');
            return false;
        }
        if (code == '') {
            toastError('验证码不能为空');
            return false;
        }

        var $this = $(this);

        // 验证手机号登录的url
        var loginUrl = $(this).attr('data-url');

        // 通过ajax验证手机号登录
        $.ajax({
            url : loginUrl,
            type : 'post',
            data : {
                phone : phone,
                code : code,
                remember : remember
            },
            dataType : 'json',
            beforeSend : function() {   // 避免重复点击
                $this.attr('disabled', true);
            }
        }).done(function (data) {
            $this.attr('disabled', false);

            // 只有登录失败才会返回数据
            toastError(data.message);
        });
    });


    ////////////////////////////////////////////
    ////////       使用账户登录      /////////////
    ////////////////////////////////////////////
    $('button[name="login-btn"]', accountLoginForm).click(function() {
        var username = $('input[name="username"]', accountLoginForm).val();
        var password = $('input[name="password"]', accountLoginForm).val();
        var remember = $('input[name="remember"]', accountLoginForm).is(':checked') ? 1 : 0;
        if (username == '') {
            toastError('帐号不能为空');
            return false;
        }
        if (password == '') {
            toastError('密码不能为空');
            return false;
        }

        var $this = $(this);

        // 验证手机号登录的url
        var loginUrl = $(this).attr('data-url');

        // 通过ajax验证手机号登录
        $.ajax({
            url : loginUrl,
            type : 'post',
            data : {
                username : username,
                password : password,
                remember : remember
            },
            dataType : 'json',
            beforeSend : function() {   // 避免重复点击
                $this.attr('disabled', true);
            }
        }).done(function (data) {
            $this.attr('disabled', false);

            // 只有登录失败才会返回数据
            toastError(data.message);
        });
    });
});