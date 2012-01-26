#!/bin/sh

mysqldump -h localhost -u zsource zsource_vandals | gzip -9 > ~/sqlbackup/`date +\%Y-\%m-\%d:\%H:\%M:\%S`_zsource_vandals.sql.gz
mysqldump -h localhost -u zsource zsource_dev | gzip -9 > ~/sqlbackup/`date +\%Y-\%m-\%d:\%H:\%M:\%S`_zsource_dev.sql.gz