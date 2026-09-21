#!/bin/sh
set -eu
umask 077

tool_dir='/home3/sc3heal3867/kh2027-private/web-control/notification-test-20260916'
bootstrap='/home3/sc3heal3867/kh2027-private/web-control/mail-test-20260915/mail-test-bootstrap.php'
site='/home3/sc3heal3867/public_html'

for php_file in \
	"$site/wp-content/mu-plugins/000-kh2027-web-review.php" \
	"$tool_dir/enquiry_notification_drafts.php" \
	"$tool_dir/enquiry_notification_test_runtime.php" \
	"$tool_dir/check_enquiry_notification_test_runtime.php" \
	"$tool_dir/check_rendered_enquiry_notifications.php" \
	"$tool_dir/send_enquiry_notification_tests.php"
do
	php -n -l "$php_file"
done

cd "$site"

run_wp_file() {
	php -n \
		-d extension=mysqlnd.so \
		-d extension=mysqli.so \
		-d extension=mbstring.so \
		-d extension=ctype.so \
		-d extension=dom.so \
		-d extension=xml.so \
		-d extension=simplexml.so \
		-d extension=phar.so \
		-d extension=tokenizer.so \
		-d extension=iconv.so \
		-d extension=fileinfo.so \
		-d extension=gd.so \
		-d extension=zip.so \
		-d extension=xmlreader.so \
		-d extension=xmlwriter.so \
		-d allow_url_fopen=0 \
		-d allow_url_include=0 \
		-d auto_prepend_file="$bootstrap" \
		-d auto_append_file= \
		-d disable_functions=mail,pfsockopen,stream_socket_server,stream_socket_accept,exec,shell_exec,system,passthru,popen,proc_open,pcntl_exec,dl,curl_exec,curl_multi_exec,socket_connect,socket_sendto,socket_create,socket_sendmsg,ftp_connect,ftp_ssl_connect \
		-d open_basedir="$site:/home3/sc3heal3867/kh2027-private/web-control:/usr/local/bin/wp:/tmp" \
		-d display_errors=0 \
		-d log_errors=1 \
		-d error_log=/home3/sc3heal3867/kh2027-private/web-control/php-errors.log \
		/usr/local/bin/wp --path="$site" eval-file "$1"
}

export KH2027_NOTIFICATION_TEST_APPROVED='twelve-notifications-once'
run_wp_file "$tool_dir/check_enquiry_notification_test_runtime.php"
run_wp_file "$tool_dir/check_rendered_enquiry_notifications.php"

export KH2027_NOTIFICATION_SEND_APPROVED='confirmed-twelve-once'
run_wp_file "$tool_dir/send_enquiry_notification_tests.php"
