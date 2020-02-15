$(document).ready(function ($) {
    if (window.DevelopxBackform)
        return;

    window.DevelopxBackform = function (useCaptcha, captchaKey) {
        this.useCaptcha = useCaptcha;
        this.captchaKey = captchaKey;
        this.captchaInterval = false;
        this.initCaptcha();
        this.initButtons();
        this.initLoader();
    };

    window.DevelopxBackform.prototype = {
        initCaptcha: function () {
            var $this = this;
            if (
                typeof grecaptcha == 'undefined' ||
                $this.useCaptcha != 'Y'
            ) {
                return;
            }
            grecaptcha.ready(function () {
                $this.resetCaptcha();

                $this.captchaInterval = setInterval(
                    function () {
                        $this.resetCaptcha();
                    },
                    150000
                );
            });
        },
        clearCaptchaInterval: function () {
            var $this = this;
            if ($this.captchaInterval) {
                $this.captchaInterval.clearInterval();
            }
        },
        resetCaptcha: function () {
            var $this = this;
            grecaptcha.execute($this.captchaKey, {action: 'backFormSent'})
                .then(function (token) {
                    console.log(token);
                    $('.back-form input[name=token]').val(token);
                });
        },
        initButtons: function () {
            var $this = this;
            $('body').on('click', '.backFormShowJs', function () {
                $('body').addClass('back-form-show');
                return false;
            });
            $('body').on('click', '.backFormCloseJs, .overflowJs', function () {
                $('body').removeClass('back-form-show');
                return false;
            });

            $('body').on('keyup', '.back-form__form input, .back-form__form textarea', function () {
                if ($(this).val().length > 0) {
                    $(this).parent().addClass('not-empty').removeClass('back-form__error');
                } else {
                    $(this).parent().removeClass('not-empty');
                }
            });
        },
        initLoader: function () {
            if (typeof BX == 'undefined'){
                return;
            }
            BX.ready(function(){
                var loader = '<div class="sk-fading-circle ajax loaderJs">\n' +
                    '    <div class="sk-circle sk-circle-1"></div>\n' +
                    '    <div class="sk-circle sk-circle-2"></div>\n' +
                    '    <div class="sk-circle sk-circle-3"></div>\n' +
                    '    <div class="sk-circle sk-circle-4"></div>\n' +
                    '    <div class="sk-circle sk-circle-5"></div>\n' +
                    '    <div class="sk-circle sk-circle-6"></div>\n' +
                    '    <div class="sk-circle sk-circle-7"></div>\n' +
                    '    <div class="sk-circle sk-circle-8"></div>\n' +
                    '    <div class="sk-circle sk-circle-9"></div>\n' +
                    '    <div class="sk-circle sk-circle-10"></div>\n' +
                    '    <div class="sk-circle sk-circle-11"></div>\n' +
                    '    <div class="sk-circle sk-circle-12"></div>\n' +
                    '  </div';
                BX.showWait = function(node, msg) {
                    $('#' + node + ' .loadJs').parent().append(loader);
                    $('#' + node + ' .loadJs').addClass('loading');
                };
                BX.closeWait = function(node, obMsg) {
                    $('#' + node + ' .loadJs').parent().remove();
                    $('#' + node + ' .loadJs').removeClass('loading');
                };
            })
        },
    }
});
