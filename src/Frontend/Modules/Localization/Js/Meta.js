if (typeof jsFrontend.Localization == 'undefined') {
    jsFrontend.Localization = {};
}

jsFrontend.Localization.Meta = {
    init: function() {
        var $baseFields = $('input[data-meta-base-field][data-meta-language]');
        $baseFields.doMeta();
    }
};

$(jsFrontend.Localization.Meta.init);
