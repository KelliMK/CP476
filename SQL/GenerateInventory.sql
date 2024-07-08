SELECT product_table.ProdID, product_table.ProdName, product_table.Quantity, product_table.Price, product_table.Status, supplier_table.SuppName
FROM product_table
INNER JOIN supplier_table ON product_table.SupplierID = supplier_table.SuppID