INSERT INTO `gateways`
 (`name`,`ar_name`,`is_default`,`is_need_banks`,`is_has_fields`)
 VALUES
 ('Cash','نقداً',true , false , false ),
 ('Transfer','تحويل',false , true , true ),
  ('Mada','مدى',false , false , false ),
 ('STC PAY','اس تي سي باي',false , false , true ),
 ('Cheque','شيك',false , true , true ),
 ('PayPal','باي بال',false , false , true ),

 ('Account Balance','رصيد الحساب',false , false , false );





 INSERT INTO `gateway_fields`
 (`placeholder`,`ar_placeholder`,`type`,`gateway_id`,`bind_vue_name`)VALUES

 ('bank account',' رقم الحساب البنكي','text',2,'account'),
 ('name in account',' اسم الحساب البنكي','text',2,'account_name'),
 ('PayPal Email Address',' ايميل الباي بال','text',6,'account'),
 ('bank account',' رقم الحساب البنكي','text',5,'account'),
 ('name in account',' الاسم','text',5,'account_name')
