<?php
/** Inert Fluent Forms notification drafts: returns data; never writes or sends. */
$base = array(
	'enabled' => false,
	'fromName' => 'Koinobori House',
	'fromEmail' => 'contact@koinoborihouse.com',
	'replyTo' => '', 'cc' => '', 'bcc' => '',
	'asPlainText' => 'no', 'email_template' => '', 'attachments' => array(),
	'conditionals' => array( 'status' => false, 'type' => 'all', 'conditions' => array() ),
);
$result = array();
foreach ( array(
	'contact' => array( 'fr', 'Contact' ), 'contact-us' => array( 'en', 'Contact' ),
	'entreprises' => array( 'fr', 'Entreprises' ), 'business' => array( 'en', 'Business' ),
	'collectivites' => array( 'fr', 'Collectivités' ), 'institutions' => array( 'en', 'Institutions' ),
) as $slug => $context ) {
	list( $language, $label ) = $context;
	$fr = $language === 'fr';
	$admin = array_replace( $base, array(
		'name' => 'KH · ' . $label . ' · ' . strtoupper( $language ) . ' · équipe',
		'sendTo' => array( 'type' => 'email', 'email' => 'contact@koinoborihouse.com', 'field' => '', 'routing' => array() ),
		'replyTo' => '{inputs.email}',
		'subject' => '[Koinobori House] ' . ( $fr ? 'Nouvelle demande — ' : 'New enquiry — ' ) . $label,
		'message' => '<h2>' . ( $fr ? 'Nouvelle demande' : 'New enquiry' ) . ' · ' . $label . '</h2><p>'
			. ( $fr ? 'Une demande a été reçue depuis le site. Vous pouvez répondre directement à cet email.'
				: 'An enquiry has been received from the website. You can reply directly to this email.' )
			. '</p>{all_data}',
	) );
	$ack = array_replace( $base, array(
		'name' => 'KH · ' . $label . ' · ' . strtoupper( $language ) . ' · confirmation',
		'sendTo' => array( 'type' => 'field', 'email' => '', 'field' => 'email', 'routing' => array() ),
		'replyTo' => 'contact@koinoborihouse.com',
		'subject' => $fr ? 'Koinobori House — Nous avons reçu votre demande' : 'Koinobori House — We have received your enquiry',
		'message' => $fr
			? '<p>Bonjour,</p><p>Merci de nous avoir écrit. Votre demande a bien été reçue par Koinobori House.</p><p>Nous la lirons avec attention et reviendrons vers vous sous 1 à 2 jours ouvrés. Pour ajouter une précision, vous pouvez répondre à cet email.</p><p>À bientôt,<br>Koinobori House<br>Créations BCDG</p>'
			: '<p>Hello,</p><p>Thank you for getting in touch. Koinobori House has received your enquiry.</p><p>We will read it carefully and get back to you within 1 to 2 working days. You can reply to this email if you would like to add anything.</p><p>Kind regards,<br>Koinobori House<br>BCDG creations</p>',
	) );
	$result[ $slug ] = array(
		'language' => $language,
		'confirmation_message' => $fr
			? 'Merci, votre demande a bien été enregistrée. Nous vous répondrons sous 1 à 2 jours ouvrés.'
			: 'Thank you, your enquiry has been recorded. We will reply within 1 to 2 working days.',
		'notifications' => array( $admin, $ack ),
	);
}
return $result;
