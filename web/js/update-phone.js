$(function() {

    function toastSuccess(message) {
        $.toaster({message : message, title : '', priority : 'success'});
    }

    function toastError(message) {
        $.toaster({message : message, title : '', priority : 'danger'});
    }

    ////////////////////////////////////////////
    ////////       发送验证码      /////////////
    ////////////////////////////////////////////
    $('#send-sms-btn').click(function() {
        var phone = $('#changephoneform-phone').val();
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
});