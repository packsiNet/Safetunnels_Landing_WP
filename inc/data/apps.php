<?php
/**
 * The Iranian app catalogue shown in the "Download Iranian Apps" carousel.
 *
 * Ported verbatim from ROW1_APPS / ROW2_APPS / ROW3_APPS in
 * `src/features/tunnels/pages/TunnelNetworkPage.tsx`. Each `key` matches an
 * icon file in assets/img/apps/<key>.webp; an empty key falls back to a
 * generic bank glyph, exactly like the React version. `url` is the official
 * publisher download page — entries without one render a disabled-looking
 * button that still records the click, matching the original behaviour.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * The three carousel rows.
 *
 * @return array<int, array{label:string, slug:string, apps:array<int, array<string,string>>}>
 */
function safetunnels_app_rows() {
	$rows = array(
		array(
			'label'    => 'بانک و مالی',
			'label_en' => 'Banking &amp; Finance',
			'slug'     => 'banking',
			'apps'     => array(
				array( 'key' => 'appicon_mellat', 'name' => 'بانک ملت', 'cat' => 'بانک', 'url' => 'https://mobile.bankmellat.ir/mobile/' ),
				array( 'key' => 'appicon_ir_bmi_bam_nativeweb', 'name' => 'بانک ملی', 'cat' => 'بانک', 'url' => 'https://bmi.ir/landing/hamrahbank' ),
				array( 'key' => 'appicon_com_isc_bsinew', 'name' => 'بانک صادرات', 'cat' => 'بانک', 'url' => 'https://apps.bsi.ir/hamrahbank.html' ),
				array( 'key' => 'appicon_com_saman_singlewindow', 'name' => 'بانک سامان', 'cat' => 'بانک', 'url' => 'https://mobillet.ir/' ),
				array( 'key' => 'appicon_mob_banking_android_pasargad', 'name' => 'بانک پاسارگاد', 'cat' => 'بانک', 'url' => 'https://hrm.bpi.ir/mobileBanking' ),
				array( 'key' => 'appicon_ir_tejaratbank_tata_mobile_android_tejarat', 'name' => 'بانک تجارت', 'cat' => 'بانک', 'url' => 'https://mobilebank.tejaratbank.ir/' ),
				array( 'key' => 'appicon_com_refahbank_dpi_android', 'name' => 'بانک رفاه', 'cat' => 'بانک', 'url' => 'https://cafebazaar.ir/app/refahmobilepayment.arnaa.com.refahmobilepayment' ),
				array( 'key' => 'appicon_mob_banking_android_resalat', 'name' => 'بانک رسالت', 'cat' => 'بانک', 'url' => '' ),
				array( 'key' => 'appicon_mob_banking_android_taavon', 'name' => 'توسعه تعاون', 'cat' => 'بانک', 'url' => 'https://ttbank.ir/fa/general_content/337105-' ),
				array( 'key' => 'appicon_ir_omidbank', 'name' => 'بانک امید', 'cat' => 'بانک', 'url' => '' ),
				array( 'key' => 'appicon_com_ada_mbank_mehr', 'name' => 'بانک مهر', 'cat' => 'بانک', 'url' => '' ),
				array( 'key' => 'appicon_mob_banking_android_gardesh', 'name' => 'بانک گردشگری', 'cat' => 'بانک', 'url' => 'https://www.tourismbank.ir/fa/sis_products_services/' ),
				array( 'key' => 'appicon_com_tosan_dara_day', 'name' => 'بانک دی', 'cat' => 'بانک', 'url' => 'https://day24.ir/Dayjet' ),
				array( 'key' => 'appicon_com_tosan_dara_sina', 'name' => 'بانک سینا', 'cat' => 'بانک', 'url' => 'https://www.sinabank.ir/internet_banking/397-' ),
				array( 'key' => 'appicon_ir_izbank_omnichannel', 'name' => 'ایران‌زمین', 'cat' => 'بانک', 'url' => 'https://getapp.izbank.ir/' ),
				array( 'key' => 'appicon_com_farazpardazan_enbank', 'name' => 'اقتصاد نوین', 'cat' => 'بانک', 'url' => 'https://enbank.ir/fa/general_content/43324-' ),
				array( 'key' => 'appicon_com_bki_mobilebanking_android', 'name' => 'بانک کشاورزی', 'cat' => 'بانک', 'url' => 'https://www.bki.ir/BankingServices/Ebanking/Systems/MobileBanking/MobileBankingHelp' ),
				array( 'key' => 'appicon_co_redbank_app', 'name' => 'رد بانک', 'cat' => 'بانک', 'url' => '' ),
				array( 'key' => '', 'name' => 'بانک پارسیان', 'cat' => 'بانک', 'url' => 'https://www.parsian-bank.ir/web_directory/64092-' ),
				array( 'key' => '', 'name' => 'بانک سپه', 'cat' => 'بانک', 'url' => 'https://mobile.ebanksepah.ir/' ),
				array( 'key' => '', 'name' => 'بانک مسکن', 'cat' => 'بانک', 'url' => 'https://www.bank-maskan.ir/hamrah-bank-system' ),
				array( 'key' => '', 'name' => 'پست‌بانک', 'cat' => 'بانک', 'url' => 'https://www.postbank.ir/hamrah-bank' ),
				array( 'key' => '', 'name' => 'بانک کارآفرین', 'cat' => 'بانک', 'url' => 'https://www.karafarinbank.ir/fa/home/electronicbanking/' ),
				array( 'key' => '', 'name' => 'بانک خاورمیانه', 'cat' => 'بانک', 'url' => 'https://www.middleeastbank.ir/page/mobilebank' ),
				array( 'key' => '', 'name' => 'بانک شهر', 'cat' => 'بانک', 'url' => 'https://www.shahr-bank.ir/general_content/76988/76988.htm' ),
				array( 'key' => '', 'name' => 'بانک سرمایه', 'cat' => 'بانک', 'url' => 'https://www.sbank.ir/general_content/170548/170548.htm' ),
				array( 'key' => 'appicon_blu', 'name' => 'بلو', 'cat' => 'نئوبانک', 'url' => '' ),
				array( 'key' => 'appicon_digital_neobank', 'name' => 'نئوبانک', 'cat' => 'نئوبانک', 'url' => '' ),
				array( 'key' => 'appicon_com_samanpr_blujr', 'name' => 'بلو جونیور', 'cat' => 'نئوبانک', 'url' => '' ),
				array( 'key' => 'appicon_tara', 'name' => 'تارا', 'cat' => 'نئوبانک', 'url' => '' ),
				array( 'key' => 'appicon_com_mydigipay_app_android', 'name' => 'دیجی‌پی', 'cat' => 'پرداخت', 'url' => '' ),
				array( 'key' => 'appicon_com_hamidrezabashiri_ezcard', 'name' => 'ای‌زکارت', 'cat' => 'پرداخت', 'url' => '' ),
				array( 'key' => '', 'name' => 'آسان‌پرداخت', 'cat' => 'پرداخت', 'url' => 'https://asanpardakht.ir/landings/download' ),
				array( 'key' => 'appicon_com_emofid_rnmofid', 'name' => 'مفید', 'cat' => 'بورس', 'url' => '' ),
				array( 'key' => 'appicon_ir_easytrader_orbis_m_twa', 'name' => 'ایزی تریدر', 'cat' => 'بورس', 'url' => '' ),
				array( 'key' => 'appicon_ir_stts_bjt', 'name' => 'سجام / بورس', 'cat' => 'بورس', 'url' => '' ),
				array( 'key' => 'appicon_ir_hafhashtad_android780', 'name' => '۷۸۰', 'cat' => 'بورس', 'url' => '' ),
				array( 'key' => 'appicon_com_bimebazar_bimebazar', 'name' => 'بیمه بازار', 'cat' => 'بیمه', 'url' => '' ),
				array( 'key' => 'appicon_com_nar_bimito', 'name' => 'بیمیتو', 'cat' => 'بیمه', 'url' => '' ),
				array( 'key' => 'appicon_app_rbmain_a', 'name' => 'ربع میلیون', 'cat' => 'مالی', 'url' => '' ),
			),
		),
		array(
			'label'    => 'خرید و ارتباط',
			'label_en' => 'Shopping &amp; Connectivity',
			'slug'     => 'shopping',
			'apps'     => array(
				array( 'key' => 'appicon_com_digikala', 'name' => 'دیجیکالا', 'cat' => 'فروشگاه', 'url' => '' ),
				array( 'key' => 'appicon_ir_basalam_app', 'name' => 'باسلام', 'cat' => 'فروشگاه', 'url' => '' ),
				array( 'key' => 'appicon_com_okala', 'name' => 'اوکالا', 'cat' => 'فروشگاه', 'url' => '' ),
				array( 'key' => 'appicon_ir_divar', 'name' => 'دیوار', 'cat' => 'آگهی', 'url' => '' ),
				array( 'key' => 'appicon_com_sheypoor_mobile', 'name' => 'شیپور', 'cat' => 'آگهی', 'url' => '' ),
				array( 'key' => 'appicon_ir_torob', 'name' => 'ترب', 'cat' => 'قیمت‌یابی', 'url' => '' ),
				array( 'key' => 'appicon_com_farsitel_bazaar', 'name' => 'کافه بازار', 'cat' => 'اپ استور', 'url' => '' ),
				array( 'key' => 'appicon_com_sibche_aspardproject_app', 'name' => 'سیبچه', 'cat' => 'اپ استور', 'url' => '' ),
				array( 'key' => 'appicon_ir_mci_ecareapp', 'name' => 'همراه اول', 'cat' => 'اپراتور', 'url' => '' ),
				array( 'key' => 'appicon_com_myirancell', 'name' => 'ایرانسل', 'cat' => 'اپراتور', 'url' => '' ),
				array( 'key' => 'appicon_ir_rightel_myrightel', 'name' => 'رایتل', 'cat' => 'اپراتور', 'url' => '' ),
				array( 'key' => 'appicon_ir_eitaa_messenger', 'name' => 'ایتا', 'cat' => 'پیام‌رسان', 'url' => '' ),
			),
		),
		array(
			'label'    => 'خدمات و سرگرمی',
			'label_en' => 'Services &amp; Entertainment',
			'slug'     => 'services',
			'apps'     => array(
				array( 'key' => 'appicon_ir_ayantech_ghabzino', 'name' => 'قبضینو', 'cat' => 'قبض', 'url' => '' ),
				array( 'key' => 'appicon_ir_mobillet_app', 'name' => 'موبیلت', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_com_dotin_wepod', 'name' => 'وی‌پاد', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_wpad', 'name' => 'وپاد', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_ir_zypod_app', 'name' => 'زی‌پاد', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_com_fam_fam', 'name' => 'فام', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_com_citydi_hplus', 'name' => 'حامیار پلاس', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_co_nilin_faraznative', 'name' => 'فراز', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_com_parsmobapp', 'name' => 'پارس‌اپ', 'cat' => 'خدمات', 'url' => '' ),
				array( 'key' => 'appicon_mobi_mmdt_ottplus', 'name' => 'OTT Plus', 'cat' => 'سرگرمی', 'url' => '' ),
			),
		),
	);

	/**
	 * Filters the Iranian app catalogue.
	 *
	 * @param array $rows Carousel rows.
	 */
	return apply_filters( 'safetunnels_app_rows', $rows );
}

/**
 * Flattened catalogue — used by the ItemList schema and the /apps page.
 *
 * @return array<int, array<string,string>>
 */
function safetunnels_all_apps() {
	$apps = array();
	foreach ( safetunnels_app_rows() as $row ) {
		foreach ( $row['apps'] as $app ) {
			$app['row'] = $row['slug'];
			$apps[]     = $app;
		}
	}

	return $apps;
}

/**
 * Total number of catalogued apps (drives the "62+ Apps Available" counter).
 *
 * @return int
 */
function safetunnels_app_count() {
	return count( safetunnels_all_apps() );
}

/**
 * Distinct category count.
 *
 * @return int
 */
function safetunnels_app_category_count() {
	$cats = array();
	foreach ( safetunnels_all_apps() as $app ) {
		if ( '' !== $app['cat'] ) {
			$cats[ $app['cat'] ] = true;
		}
	}

	return count( $cats );
}

/**
 * Resolves an app icon URL, or an empty string when no artwork exists.
 *
 * @param string $key Icon key.
 * @return string
 */
function safetunnels_app_icon( $key ) {
	if ( '' === $key ) {
		return '';
	}

	$file = get_theme_file_path( 'assets/img/apps/' . $key . '.webp' );
	if ( ! file_exists( $file ) ) {
		return '';
	}

	return safetunnels_asset( 'img/apps/' . $key . '.webp' );
}

/**
 * Derives the dot-separated package name the stats endpoint expects — the PHP
 * twin of `toPackageName()` in src/lib/analytics.ts.
 *
 * @param string $key  Icon key.
 * @param string $name Display name, used when there is no key.
 * @return string
 */
function safetunnels_package_name( $key, $name ) {
	if ( '' === $key || 'apk' === $key ) {
		$slug = strtolower( $name );
		$slug = preg_replace( '/[^a-z0-9]/', '.', $slug );
		$slug = preg_replace( '/\.{2,}/', '.', $slug );

		return trim( (string) $slug, '.' );
	}

	if ( false !== strpos( $key, '.' ) ) {
		return $key;
	}

	return str_replace( '_', '.', preg_replace( '/^appicon_/', '', $key ) );
}
