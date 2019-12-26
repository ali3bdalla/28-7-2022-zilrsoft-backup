exports.db = {
    model: {
        exists: function (arr, value) {
            return arr.includes(value);
        },
        contain: function (arr, id) {
            if (arr.some(obj => obj.id === id)) {
                return true;
            } else {
                return false;
            }
        },

        find: function (arr, id) {
            var len = arr.length;
            for (var i = 0; i < len; i++) {
                if (arr[i].id == id) {
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
                if (arr[i].id == id) {
                    return i;
                }
            }
            return -1;
        },
        delete: function (array, id) {
            array.splice(this.index(id));
            return array;
        },
        replace: function (arr, index, value) {
            arr.splice(arr, index, value);
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
                }else
                {
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
            arr[i][col] = value;
            return arr;

        }

    },
    validator: {}

};

