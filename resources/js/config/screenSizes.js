function ScreenSize(size, maxSize) {
    this.screensize = size;
    this.maxSize = maxSize;
    this.formatString = "";
}

ScreenSize.prototype.format = function(formatString) {
    this.formatString = formatString;
    return this;
};

ScreenSize.prototype.max = function() {
    let maxSize = this.maxSize - 0.02;
    return maxSize + this.formatString;
};

ScreenSize.prototype.min = function() {
    return this.toString();
};

ScreenSize.prototype.toString = function() {
    return this.screensize + this.formatString;
};

module.exports = {
    IPHONE5: new ScreenSize(360, 360),
    XXS: new ScreenSize(0, 420),
    XS: new ScreenSize(420, 576),
    SM: new ScreenSize(576, 768),
    MD: new ScreenSize(768, 992),
    LG: new ScreenSize(992, 1330),
    XL: new ScreenSize(1330, 0),
};
