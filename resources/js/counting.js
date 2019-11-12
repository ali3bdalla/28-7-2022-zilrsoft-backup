

var counting = {
	 convertVatToValue:function(vat)
	 {
	 	if(validate.isEmpty(vat)){
	 		return 0;
	 	}
		if(validate.isNumber(parseFloat(vat)))
		{
			return 1 + vat / 100;
		}
		return 0;
	},




	calcPriceWithTaxFromPrice:function(price,vat){
		return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(price * this.convertVatToValue(vat));
	},
	calcPriceFromPriceWithTax:function(price,vat){
		return helpers.roundTheFloatValueTo2DigitOnlyAfterComma(price / this.convertVatToValue(vat));
	},


}


exports.counting = counting;
