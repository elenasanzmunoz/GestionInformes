## script to detect new files
WATCHDIR='/root/watch/inf_kaspersky/input/';
OUTDIR='/root/watch/inf_kaspersky/procesado';
inotifywait -m -q -e close_write --format %f ${WATCHDIR} | while IFS= read -r file; do
##cp -p ${WATCHDIR}/"$file" ${OUTDIR}/
mv ${WATCHDIR}/"$file" ${OUTDIR}/
## PROCESAR XML Y CARGAR EN LA TABLA MySQL
php /var/www/html/watch/php_procesar_inf_kaspersky.php ${OUTDIR}/"$file"
php /var/www/html/watch/ejemplo_Kaspersky.php ${OUTDIR}/"$file"
done

