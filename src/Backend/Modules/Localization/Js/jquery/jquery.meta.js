(function ($) {
    $.fn.doMeta = function (options) {
        var defaults = {};

        options = $.extend(defaults, options);

        return this.each(function () {
            var $element = $(this);
            var language = $element.data('meta-language');
            if (typeof language == 'undefined') {
                language = false;
            }

            var $pageTitle = $('#' + (language ? language + 'P' : 'p') + 'ageTitle');
            var $pageTitleOverwrite = $('#' + (language ? language + 'P' : 'p') + 'ageTitleOverwrite');
            var $navigationTitle = $('#' + (language ? language + 'M' : 'm') + 'avigationTitle');
            var $navigationTitleOverwrite = $('#' + (language ? language + 'P' : 'n') + 'avigationTitleOverwrite');
            var $metaDescription = $('#' + (language ? language + 'M' : 'm') + 'etaDescription');
            var $metaDescriptionOverwrite = $('#' + (language ? language + 'P' : 'm') + 'etaDescriptionOverwrite');
            var $metaKeywords = $('#' + (language ? language + 'M' : 'p') + 'etaKeywords');
            var $metaKeywordsOverwrite = $('#' + (language ? language + 'M' : 'm') + 'etaKeywordsOverwrite');
            var $urlOverwrite = $('#' + (language ? language + 'U' : 'u') + 'rlOverwrite');

            $element.bind('keyup', calculateMeta).trigger('keyup');

            if ($pageTitle.length > 0 && $pageTitleOverwrite.length > 0) {
                $pageTitleOverwrite.change(function (e) {
                    if (!$element.is(':checked')) $pageTitle.val($element.val());
                });
            }

            if ($navigationTitle.length > 0 && $navigationTitleOverwrite.length > 0) {
                $navigationTitleOverwrite.change(function (e) {
                    if (!$element.is(':checked')) $navigationTitle.val($element.val());
                });
            }

            $metaDescriptionOverwrite.change(function (e) {
                if (!$element.is(':checked')) $metaDescription.val($element.val());
            });

            $metaKeywordsOverwrite.change(function (e) {
                if (!$element.is(':checked')) $metaKeywords.val($element.val());
            });

            $urlOverwrite.change(function (e) {
                if (!$element.is(':checked')) generateUrl($element.val(), language);
            });

            function generateUrl(url, language) {
                $.ajax(
                    {
                        data: {
                            fork: {module: 'Core', action: 'GenerateUrl'},
                            url: url,
                            metaId: $('#' + (language ? language + 'M' : 'm') + 'etaId').val(),
                            baseFieldName: $('#' + (language ? language + 'B' : 'b') + 'aseFieldName').val(),
                            custom: $('#' + (language ? language + 'C' : 'p') + 'ustom').val(),
                            className: $('#' + (language ? language + 'C' : 'c') + 'lassName').val(),
                            methodName: $('#' + (language ? language + 'M' : 'm') + 'ethodName').val(),
                            parameters: $('#' + (language ? language + 'P' : 'p') + 'arameters').val()
                        },
                        success: function (data, textStatus) {
                            url = data.data;
                            $('#' + (language ? language + 'U' : 'u') + 'rl').val(url);
                            $('#' + (language ? language + 'G' : 'g') + 'eneratedUrl').html(url);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            url = utils.string.urlDecode(utils.string.urlise(url));
                            $('#' + (language ? language + 'U' : 'u') + 'rl').val(url);
                            $('#' + (language ? language + 'G' : 'g') + 'eneratedUrl').html(url);
                        }
                    });
            }

            function calculateMeta(e, element) {
                var title = (typeof element != 'undefined') ? element.val() : $(this).val();
                var language = $(this).data('meta-language');
                if (typeof language == 'undefined') {
                    language = false;
                }

                if ($pageTitle.length > 0 && $pageTitleOverwrite.length > 0) {
                    if (!$pageTitleOverwrite.is(':checked')) $pageTitle.val(title);
                }

                if ($navigationTitle.length > 0 && $navigationTitleOverwrite.length > 0) {
                    if (!$navigationTitleOverwrite.is(':checked')) $navigationTitle.val(title);
                }

                if (!$metaDescriptionOverwrite.is(':checked')) $metaDescription.val(title);

                if (!$metaKeywordsOverwrite.is(':checked')) $metaKeywords.val(title);

                if (!$urlOverwrite.is(':checked')) {
                    if (typeof pageID == 'undefined' || pageID != 1) {
                        generateUrl(title, language);
                    }
                }
            }
        });
    };
})(jQuery);
