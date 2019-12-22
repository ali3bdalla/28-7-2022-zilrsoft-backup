exports.metaHelper =
    {
        getContent: function (name) {
            var target = document.head.querySelector('meta[name="' + name + '"]');
            return target.content;
        }
    };


exports.trans = function (file) {
    var lang = metaHelper.getContent('lang-' + file);
    return JSON.parse(lang);
};


exports.route = function (route = "") {
    var lang = metaHelper.getContent('routes');
    return JSON.parse(lang);
};




