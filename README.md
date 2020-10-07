# exportorder
M2 export order to CSV test task 

Ussage:

php bin/magento consumewithadele:exportorder --orderId 1  
Order 000000001 exported into file var/export/2dd0f6e2c1635e5b61ef21519dadeabbeb9789e8.csv

Sample order content:

increment_id,status,date,"Customer Name","Customer Email",subtotal,"grand total",shipping  
000000001,new,"2020-10-07 03:34:40","Jhon Doe",test@test.com,20.0000,10.0000,30.0000  
"Customer Name",Street,City,Region,Postcode,Country,Phone  
"Jhon Doe","6th Street, building 1",Boulder,Colorado,80304,US,911  
"Jhon Doe","6th Street, building 1",Boulder,Colorado,80304,US,911  
SKU,QTY,"Row Total"  
test-a,1.0000,10.0000  
test-b,1.0000,10.0000  


It's sample module. Doesn't process configurable products in proper way.



