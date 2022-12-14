const itemWork = require('./item');

exports.db = {
    model: {
        between: function (start, end, includingStart = false, includingEnd = false, collectWith = '') {
            let result = [];
            if (parseInt(end) > parseInt(start)) {
                let range = parseInt(end) - parseInt(start);
                let startPoint = includingStart ? 0 : 1;
                let endPoint = includingEnd ? range + 1 : range;

                for (let i = startPoint; i < endPoint; i++) {
                    let number = parseInt(start) + i;
                    result.push(number);
                }

            }

            return result;
        },
        exists: function (arr, value) {
            return arr.includes(value);
        },
        contain: function (arr, id) {
            if (arr.some(obj => obj.id === id)) {
                return true;
            }
            return false;
        },
        in_array: function (arr, value, type = null) {
            let len = arr.length;
            for (var i = 0; i < len; i++) {
                let checker = arr[i];
                if (type === 'int')
                    checker = parseInt(arr[i]);

                if (checker == value)
                    return true;

            }

            return false;
        },
        find: function (arr, id) {
            let len = arr.length;
            for (var i = 0; i < len; i++) {
                if (arr[i].id === id) {
                    return arr[i];
                }
            }
            return null;
        },
        findByIndex: function (arr, index) {
            var len = arr.length;
            for (var i = 0; i < len; i++) {
                if (i == index) {
                    return arr[i];
                }
            }
            return null;
        },
        sum: function (arr, col) {
            var sum = 0;
            for (var i = arr.length - 1; i >= 0; i--) {

                sum = parseFloat(sum) + parseFloat(arr[i][col]);
            }


            return sum;

        },
        index: function (arr, id) {
            var len = arr.length;
            for (var i = 0; i < len; i++) {
                if (arr[i].id === id) {
                    return i;
                }
            }
            return -1;
        },
        delete: function (array, id) {
            array.splice(this.index(array, id), 1);
            return array;
        },
        replace: function (arr, index, value) {
            arr.splice(index, 1, value);
            return arr;
        },
        deleteByIndex: function (array, index) {
            array.splice(index, 1);
            return array;
        },
        createUnique: function (arr, value) {
            if (!this.exists(arr, value)) {
                arr.push(value);
            }

            return arr;
        },
        count: function (arr) {
            return arr.length();
        },
        pluck: function (arr, col, condition_col = null, condition_value = null) {
            var len = arr.length;
            var resutl = [];
            for (var i = 0; i < len; i++) {
                var item = arr[i];
                if (condition_col != null) {
                    if (item[condition_col] == condition_value) {
                        resutl.push(item[col]);
                    }
                } else {
                    resutl.push(item[col]);
                }
            }
            return resutl;
        },
        addColumn: function (arr, col, default_value) {
            let len = arr.length;
            let result = [];
            for (let i = 0; i < len; i++) {
                let item = arr[i];
                item[col] = default_value;
                result.push(item);
            }
            return result;
        },
        update: function (arr, index, col, value) {
            arr[index][col] = value;
            return arr;

        },
        validateAmounts(arr, cols = []) {
            let len = arr.length;
            let colLen = cols.length;
            let result = true;
            for (let i = 0; i < len; i++) {
                let item = arr[i];
                for (let x = 0; x < colLen; x++) {
                    result = itemWork.validator.validateAmount(item[cols[x]]);
                }
            }

            return result;
        },
        search: function (arr, userValue) {
            let result = [];
            let len = arr.length;
            for (let i = 0; i < len; i++) {
                if (String(arr[i].name_en).search(userValue)!==-1 || String(arr[i].name).search(userValue)!==-1 || String(arr[i].ar_name).search(userValue)!==-1 || String(arr[i].name_ar).search(userValue)!==-1) {
                    result.push(arr[i]);
                }
            }

            return result;
        }

    },

    convert: {
        enNumberToArabic(en) {
            var response = [];
            var en_arr = en.split('');
            for (var i = 0; i < en_arr.length; i++) {
                var num = en_arr[i];

                if (num == 0) {
                    response.push('??');
                } else if (num == 1) {
                    response.push('??');
                } else if (num == 2) {
                    response.push('??');
                } else if (num == 3) {
                    response.push('??');
                } else if (num == 4) {
                    response.push('??');
                } else if (num == 5) {
                    response.push('??');
                } else if (num == 6) {
                    response.push('??');
                } else if (num == 7) {
                    response.push('??');
                } else if (num == 8) {
                    response.push('??');
                } else if (num == 9) {
                    response.push('??');
                } else {
                    response.push(num);
                }


            }


            return response.join('');
        }
    }
};

