-- where INTERVAL corresponds to how far off local time is from gmt
UPDATE users SET last_login = last_login + INTERVAL 8 HOUR WHERE last_login !=0 LIMIT 1000;
UPDATE users SET created = created + INTERVAL 8 HOUR WHERE created !=0 LIMIT 1000;
UPDATE users SET modified = modified + INTERVAL 8 HOUR WHERE modified !=0 LIMIT 1000;
