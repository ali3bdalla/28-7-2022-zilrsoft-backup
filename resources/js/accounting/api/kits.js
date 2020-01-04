exports.sendGetKitAmountsRequest = function (kit_id = 0, qty = 1) {
    let link =
        metaHelper.getContent("BaseApiUrl")
        + 'kits/helper/get_kit_amounts/' + kit_id + "?qty=" + qty;
    return axios.get(link);
};
