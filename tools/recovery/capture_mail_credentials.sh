#!/bin/sh
set -eu
umask 077

secret_dir='/home3/sc3heal3867/kh2027-private/web-control/secrets'
secret_file="$secret_dir/fluent-smtp-credentials.php"
mkdir -p "$secret_dir"
chmod 700 "$secret_dir"

restore_echo() {
	stty echo 2>/dev/null || true
}
trap restore_echo EXIT HUP INT TERM

printf 'Identifiant SMTP Brevo : '
IFS= read -r smtp_login
printf 'Cle SMTP Brevo (saisie masquee) : '
stty -echo
IFS= read -r smtp_key
stty echo
printf '\n'
trap - EXIT HUP INT TERM

case "$smtp_login" in
	''|*[!A-Za-z0-9@._+-]*) printf 'Identifiant invalide.\n' >&2; exit 1 ;;
esac
case "$smtp_key" in
	''|*[!A-Za-z0-9._+-]*) printf 'Cle invalide.\n' >&2; exit 1 ;;
esac

temp_file="$secret_dir/.fluent-smtp-credentials.tmp"
{
	printf '%s\n' '<?php'
	printf '%s\n' "if ( ! defined( 'KH2027_SANDBOX' ) || KH2027_SANDBOX !== true ) { exit; }"
	printf "define( 'FLUENTMAIL_SMTP_USERNAME', '%s' );\n" "$smtp_login"
	printf "define( 'FLUENTMAIL_SMTP_PASSWORD', '%s' );\n" "$smtp_key"
} > "$temp_file"
chmod 600 "$temp_file"
mv -f "$temp_file" "$secret_file"
chmod 600 "$secret_file"

unset smtp_login smtp_key
printf 'Identifiants enregistres hors de la racine web.\n'
