const helpers = {

  isNumber (number) {
    return parseFloat(number) != 'NaN' && parseFloat(number) != NaN
  },
  convertEnToArabicNumber (en) {
    const response = []
    const en_arr = en.split('')
    for (let i = 0; i < en_arr.length; i++) {
      const num = en_arr[i]

      if (num == 0) {
        response.push('٠')
      } else if (num == 1) {
        response.push('١')
      } else if (num == 2) {
        response.push('٢')
      } else if (num == 3) {
        response.push('٣')
      } else if (num == 4) {
        response.push('٤')
      } else if (num == 5) {
        response.push('٥')
      } else if (num == 6) {
        response.push('٦')
      } else if (num == 7) {
        response.push('٧')
      } else if (num == 8) {
        response.push('٨')
      } else if (num == 9) {
        response.push('٩')
      } else {
        response.push(num)
      }
    }

    return response.join('')
  },
  searchInArrayByArOrEnName (name, arr) {
    const result = []

    const len = arr.length; const str = String(name).toLowerCase()
    const myReg = new RegExp(str)
    for (let i = 0; i < len; i++) {
      if (String(arr[i].name).toLowerCase().match(myReg) || String(arr[i].ar_name).toLowerCase().match(myReg)) {
        result.push(arr[i])
        console.log(arr[i].ar_name)
      }
    }

    return result
  },
  getFullDateAndTime () {
    const today = new Date()
    const date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds()
    return date.toString()
  },

  getColumnSumationFromArrayOfObjects (arr = [], column) {
    let sum = 0
    for (let i = arr.length - 1; i >= 0; i--) {
      sum = parseFloat(sum) + parseFloat(arr[i][column])
    }

    if (column == 'net') { return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(sum) }

    return sum
    return helpers.showOnlyTwoAfterComma(sum)
  },
  // round  the float value into value with only two 2 digit
  // after the comma
  roundTheFloatValueTo2DigitOnlyAfterComma: function (val) {
    return parseFloat(val).toFixed(2)
  },

  showOnlyTwoAfterComma (val) {
    const num = val
    const with2Decimals = num.toString().match(/^-?\d+(?:\.\d{0,4})?/)[0]

    return with2Decimals
  },

  generateRandomNumberWithSize: function () {
    return Math.floor(Math.random() * (9000000000000 - 1000000000000) + 1000000000000) + ''
  },

  getDataFromArrayById (arr = [], id) {
    const len = arr.length
    for (let i = 0; i < len; i++) {
      if (parseInt(arr[i].id) === parseInt(id)) {
        return arr[i]
      }
    }
  },
  getItemIndex (arr = [], item) {
    const len = arr.length
    for (let i = 0; i < len; i++) {
      if (arr[i] === item) {
        return i
      }
    }
  },

  checkIfObjectExistsOnArrayBYIdentifer (arr = [], value) {
    // Find if the array contains an object by comparing the property value
    if (arr.some(obj => obj.id === value)) {
      return true
    } else {
      return false
    }
  }

}

exports.helpers = helpers
