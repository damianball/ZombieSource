CREATE USER 'zsource_dev'@'127.0.0.1' IDENTIFIED BY ''; -- @TODO: put config password here
GRANT SELECT, INSERT, UPDATE, DELETE on `zsource_dev`.* to 'zsource_dev'@'127.0.0.1';