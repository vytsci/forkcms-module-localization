if (typeof jsBackend.Localization == 'undefined') {
    jsBackend.Localization = {};
}

jsBackend.Localization.Meta = {
    init: function() {
        var $baseFields = $('input[data-meta-base-field][data-meta-language]');
        $baseFields.doMeta();
    }
};

$(jsBackend.Localization.Meta.init);
