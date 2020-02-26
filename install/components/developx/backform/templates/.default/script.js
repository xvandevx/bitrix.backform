$(document).ready(function ($) {
    if (window.DevelopxBackform)
        return;

    window.DevelopxBackform = function () {
        this.initButtons();
        this.initLoader();
    };

    window.DevelopxBackform.prototype = {
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

    DevelopxBackform_ = new DevelopxBackform();
});
