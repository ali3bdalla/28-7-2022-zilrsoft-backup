-- select sum(total_credit_amount),sum(total_debit_amount) from accounts  where EXTRACT(YEAR FROM created_at) = '2020' group by account_id

-- // to extract un equaled monthed accounts
-- select round(cast(sum(credit_amount) as NUMERIC),2),round(cast(sum(debit_amount) as NUMERIC),2)  from account_snapshots
-- where  EXTRACT(YEAR FROM created_at) = '2020' and EXTRACT(MONTH FROM created_at) = '11' 
-- group by EXTRACT(DAY FROM created_at);



-- // to get un equaled entities

-- select c.totalCredit,c.totalDebit,c.container_id from (select round(cast(sum(CASE WHEN type = 'credit' THEN amount ELSE 0 END)as NUMERIC),2) as totalCredit,
-- round(cast(sum(CASE WHEN type = 'debit' THEN amount ELSE 0 END)as NUMERIC),2) as totalDebit,
-- 	container_id from public.transactions where
-- EXTRACT(YEAR FROM created_at) = '2020' 
-- 	and EXTRACT(MONTH FROM created_at) = '11'
--  	and EXTRACT(DAY FROM created_at) = '24'
-- 	GROUP BY container_id) as c where c.totalCredit != c.totalDebit



-- select * from transactions where container_id in (
-- select c.container_id from (select round(cast(sum(CASE WHEN type = 'credit' THEN amount ELSE 0 END)as NUMERIC),2) as totalCredit,
-- round(cast(sum(CASE WHEN type = 'debit' THEN amount ELSE 0 END)as NUMERIC),2) as totalDebit,
-- 	container_id from public.transactions where
-- EXTRACT(YEAR FROM created_at) = '2020' 
-- 	and EXTRACT(MONTH FROM created_at) = '11'
--  	and EXTRACT(DAY FROM created_at) = '24'
-- 	GROUP BY container_id) as c where c.totalCredit != c.totalDebit)