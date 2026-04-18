<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
/**
*	
*/
echo '<header>';
if ( is_login() ) {
	echo '<marquee scrollamount="2" scrolldelay="10" onMouseOver=stop() onMouseOut=start()>
			Selamat Datang Di Website Praktik Lapangan Terpadu (PLT)
		</marquee>';
} else { 
	echo	'<marquee scrollamount="2" scrolldelay="10" onMouseOver=stop() onMouseOut=start()>
			Selamat Datang Di Website Praktik Lapangan Terpadu (PLT), Silahkan Login Untuk Mengurus Kegiatan PPK Anda
		</marquee>';
}
echo '</header>';