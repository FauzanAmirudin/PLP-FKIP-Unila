<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
require_level('Mahasiswa');
?>
<style>
	.file-Field {
		padding: 15px 0px 15px 0px;
		text-align: center;
	}

	.file-Field .file {
		width: 120px;
		padding: 10px;
		display: inline-block;
		text-align: center;
		text-decoration: none;
	}

	.help-block {
		margin: 1px;
		font-size: small;
		font-weight: normal;
	}
</style>

<content>
	<section id="mainContent">
		<div class="content">
			<div class="header">
				<a>LAPORAN</a>
			</div>
			<?php if (isset($notification) && $notification != null) {
				echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
			} ?>
			<div class="field">
				<h1>Upload Laporan
					<span class="field-action action-right"> </span>
				</h1>
				<form action="<?= set_url('laporan/simpan/' . $user['ID']) ?>" method="post" enctype="multipart/form-data" class="form">
					<div class="form-group row">
						<div class="input-group col-md-3">
							<label for="laporan" class="top-label">Laporan yang akan di upload:</label>
							<select id="laporan" name="laporan" required="required">
								<?php
								$ming = 1;
								while ($ming <= get_dbconfig('MAXREPORT')) { ?>
									<option value="Laporan Minggu Ke-<?= $ming; ?>">Laporan Minggu Ke-<?= $ming; ?></option>
								<?php
									$ming++;
								} ?>
							</select>
						</div>
						<div class="input-group">
							<div class="checkbox">
								<label class=""><a class="help-block"> Perbaharui laporan</a>
									<input type="checkbox" name="timpa" value="1">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="inline-label">Pilih file laporan:</label>
						<div class="col-md-4">
							<input type="file" name="file"><br />
							<a class="help-block"><a style="color:#ff0000;">*</a> hanya file .doc, .docx atau file Word Document dengan ukuran maximum 5 MB.</a>
						</div>
					</div>
					<div class="form-group action-right">
						<button type="submit" value="uplodlaporan" name="action" class="btn btn-medium btn-ok">Upload File</button>
						<button type="reset" class="btn btn-medium btn-cancel">Reset</button>
					</div>
				</form>
			</div>
			<?php if (!empty($report)) { ?>
				<div class="field">
					<h1>Cek Laporan
						<span class="field-action action-right"> </span>
					</h1>
					<div class="file-Field">
						<?php
						foreach ($report as $r) {
							if ($r['RESPONSE'] != NULL | $r['RESPONSE'] != "") {
								if ($r['RESPONSE'] == "Cukup") {
									$respon = "btn-view";
									$prop = 'disabled="true"';
								} else {
									$respon = "btn-cancel";
									$prop = "";
								}
							} else {
								$respon = "btn-disable";
								$prop = 'disabled="true"';
							}
							if (isset($namaLaporan) && $namaLaporan == $r['FILENAME']) { /* $namaLaporan are from post action upload laporan */
								$mark = '<a style="color:red"> (update)</a>';
							} else {
								$mark = '';
							}
							/* echo '
					<div class="file">
							<div>
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 56 56" width="46.140167" height="44.892979" style="enable-background:new 0 0 56 56;" xml:space="preserve"><g><path style="fill:#E9E9E0;" d="M36.985,0H7.963C7.155,0,6.5,0.655,6.5,1.926V55c0,0.345,0.655,1,1.463,1h40.074   c0.808,0,1.463-0.655,1.463-1V12.978c0-0.696-0.093-0.92-0.257-1.085L37.607,0.257C37.442,0.093,37.218,0,36.985,0z"/><polygon style="fill:#D9D7CA;" points="37.5,0.151 37.5,12 49.349,12  "/><path style="fill:#CC4B4C;" d="M19.514,33.324L19.514,33.324c-0.348,0-0.682-0.113-0.967-0.326   c-1.041-0.781-1.181-1.65-1.115-2.242c0.182-1.628,2.195-3.332,5.985-5.068c1.504-3.296,2.935-7.357,3.788-10.75   c-0.998-2.172-1.968-4.99-1.261-6.643c0.248-0.579,0.557-1.023,1.134-1.215c0.228-0.076,0.804-0.172,1.016-0.172   c0.504,0,0.947,0.649,1.261,1.049c0.295,0.376,0.964,1.173-0.373,6.802c1.348,2.784,3.258,5.62,5.088,7.562   c1.311-0.237,2.439-0.358,3.358-0.358c1.566,0,2.515,0.365,2.902,1.117c0.32,0.622,0.189,1.349-0.39,2.16   c-0.557,0.779-1.325,1.191-2.22,1.191c-1.216,0-2.632-0.768-4.211-2.285c-2.837,0.593-6.15,1.651-8.828,2.822   c-0.836,1.774-1.637,3.203-2.383,4.251C21.273,32.654,20.389,33.324,19.514,33.324z M22.176,28.198   c-2.137,1.201-3.008,2.188-3.071,2.744c-0.01,0.092-0.037,0.334,0.431,0.692C19.685,31.587,20.555,31.19,22.176,28.198z    M35.813,23.756c0.815,0.627,1.014,0.944,1.547,0.944c0.234,0,0.901-0.01,1.21-0.441c0.149-0.209,0.207-0.343,0.23-0.415   c-0.123-0.065-0.286-0.197-1.175-0.197C37.12,23.648,36.485,23.67,35.813,23.756z M28.343,17.174   c-0.715,2.474-1.659,5.145-2.674,7.564c2.09-0.811,4.362-1.519,6.496-2.02C30.815,21.15,29.466,19.192,28.343,17.174z    M27.736,8.712c-0.098,0.033-1.33,1.757,0.096,3.216C28.781,9.813,27.779,8.698,27.736,8.712z"/><path style="fill:#CC4B4C;" d="M48.037,56H7.963C7.155,56,6.5,55.345,6.5,54.537V39h43v15.537C49.5,55.345,48.845,56,48.037,56z"/><g><path style="fill:#FFFFFF;" d="M17.385,53h-1.641V42.924h2.898c0.428,0,0.852,0.068,1.271,0.205    c0.419,0.137,0.795,0.342,1.128,0.615c0.333,0.273,0.602,0.604,0.807,0.991s0.308,0.822,0.308,1.306    c0,0.511-0.087,0.973-0.26,1.388c-0.173,0.415-0.415,0.764-0.725,1.046c-0.31,0.282-0.684,0.501-1.121,0.656    s-0.921,0.232-1.449,0.232h-1.217V53z M17.385,44.168v3.992h1.504c0.2,0,0.398-0.034,0.595-0.103    c0.196-0.068,0.376-0.18,0.54-0.335c0.164-0.155,0.296-0.371,0.396-0.649c0.1-0.278,0.15-0.622,0.15-1.032    c0-0.164-0.023-0.354-0.068-0.567c-0.046-0.214-0.139-0.419-0.28-0.615c-0.142-0.196-0.34-0.36-0.595-0.492    c-0.255-0.132-0.593-0.198-1.012-0.198H17.385z"/><path style="fill:#FFFFFF;" d="M32.219,47.682c0,0.829-0.089,1.538-0.267,2.126s-0.403,1.08-0.677,1.477s-0.581,0.709-0.923,0.937    s-0.672,0.398-0.991,0.513c-0.319,0.114-0.611,0.187-0.875,0.219C28.222,52.984,28.026,53,27.898,53h-3.814V42.924h3.035    c0.848,0,1.593,0.135,2.235,0.403s1.176,0.627,1.6,1.073s0.74,0.955,0.95,1.524C32.114,46.494,32.219,47.08,32.219,47.682z     M27.352,51.797c1.112,0,1.914-0.355,2.406-1.066s0.738-1.741,0.738-3.09c0-0.419-0.05-0.834-0.15-1.244    c-0.101-0.41-0.294-0.781-0.581-1.114s-0.677-0.602-1.169-0.807s-1.13-0.308-1.914-0.308h-0.957v7.629H27.352z"/><path style="fill:#FFFFFF;" d="M36.266,44.168v3.172h4.211v1.121h-4.211V53h-1.668V42.924H40.9v1.244H36.266z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
								</svg>*/
							echo '
					<div class="file">
						<div>
							<svg width="46.140167" height="44.892979"> 
								<defs id="defs7018"> <linearGradient id="linearGradient7722"> <stop id="stop7724" offset="0" style="stop-color:#96afee;stop-opacity:1;"/><stop id="stop7726" offset="1" style="stop-color:#0042a1;stop-opacity:1;"/></linearGradient>	 <linearGradient id="linearGradient7704"><stop style="stop-color:#ffffff;stop-opacity:1;" offset="0" id="stop7706"/> <stop style="stop-color:#d8d8d8;stop-opacity:1;" offset="1" id="stop7708"/> </linearGradient> <linearGradient id="linearGradient7696"> <stop style="stop-color:#7496e9;stop-opacity:1;" offset="0" id="stop7698"/> <stop style="stop-color:#003177;stop-opacity:1;" offset="1" id="stop7700"/> </linearGradient> <linearGradient id="linearGradient7684"> <stop style="stop-color:#98b7e4;stop-opacity:1;" offset="0" id="stop7686"/> <stop style="stop-color:#ffffff;stop-opacity:1;" offset="1" id="stop7688"/> </linearGradient> <linearGradient id="linearGradient7676"> <stop style="stop-color:#aacaf3;stop-opacity:1;" offset="0" id="stop7678"/> <stop style="stop-color:#81a1ce;stop-opacity:1;" offset="1" id="stop7680"/> </linearGradient> <linearGradient id="linearGradient7666"> <stop style="stop-color:#e4eeff;stop-opacity:1;" offset="0" id="stop7668"/> <stop style="stop-color:#81a1ce;stop-opacity:1;" offset="1" id="stop7670"/> </linearGradient> <linearGradient id="linearGradient7627"> <stop style="stop-color:#659bf4;stop-opacity:1;" offset="0" id="stop7629"/> <stop style="stop-color:#c3ccdf;stop-opacity:1;" offset="1" id="stop7631"/> </linearGradient> <linearGradient id="linearGradient7596"> <stop style="stop-color:#e4eeff;stop-opacity:1;" offset="0" id="stop7598"/> <stop style="stop-color:#abc0e2;stop-opacity:1;" offset="1" id="stop7600"/> </linearGradient> <linearGradient id="linearGradient7573"> <stop style="stop-color:#c4d8f8;stop-opacity:1;" offset="0" id="stop7575"/> <stop style="stop-color:#ffffff;stop-opacity:1;" offset="1" id="stop7577"/> </linearGradient> <linearGradient id="linearGradient7565"> <stop style="stop-color:#b1bfd1;stop-opacity:1;" offset="0" id="stop7567"/> <stop style="stop-color:#364a60;stop-opacity:1;" offset="1" id="stop7569"/> </linearGradient> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7573" id="linearGradient7588" gradientUnits="userSpaceOnUse" x1="393.86124" y1="509.71899" x2="375.51321" y2="488.99655"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7565" id="linearGradient7590" gradientUnits="userSpaceOnUse" x1="363.62335" y1="469.9657" x2="399.72467" y2="513.84235"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7596" id="linearGradient7602" x1="390.823" y1="470.17795" x2="394.45517" y2="479.97153" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7627" id="linearGradient7633" x1="398.77124" y1="512.53027" x2="376.91345" y2="492.91455" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7666" id="linearGradient7672" x1="367.32187" y1="480.50916" x2="394.44479" y2="508.52676" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7676" id="linearGradient7682" x1="388.70154" y1="485.94778" x2="389.17819" y2="486.39633" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7684" id="linearGradient7690" x1="393.2619" y1="489.53629" x2="386.18777" y2="481.38742" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7696" id="linearGradient7702" x1="354.5856" y1="474.98618" x2="374.77124" y2="494.54565" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7704" id="linearGradient7710" x1="354.5856" y1="474.98618" x2="374.77124" y2="494.54565" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7722" id="linearGradient7720" x1="356.10675" y1="476.00568" x2="373.65884" y2="493.60919" gradientUnits="userSpaceOnUse"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7722" id="linearGradient7751" x1="355.24228" y1="475.4635" x2="374.22028" y2="494.44147" gradientUnits="userSpaceOnUse"/> <filter inkscape:collect="always" id="filter7885" color-interpolation-filters="sRGB"> <feGaussianBlur inkscape:collect="always" stdDeviation="0.087522" id="feGaussianBlur7887"/> </filter> <filter inkscape:collect="always" id="filter7889" color-interpolation-filters="sRGB"> <feGaussianBlur inkscape:collect="always" stdDeviation="0.087522" id="feGaussianBlur7891"/> </filter> <filter inkscape:collect="always" id="filter7893" color-interpolation-filters="sRGB"> <feGaussianBlur inkscape:collect="always" stdDeviation="0.087522" id="feGaussianBlur7895"/> </filter> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7722" id="linearGradient7897" gradientUnits="userSpaceOnUse" x1="356.10675" y1="476.00568" x2="373.65884" y2="493.60919"/> <linearGradient inkscape:collect="always" xlink:href="#linearGradient7722" id="linearGradient7899" gradientUnits="userSpaceOnUse" x1="355.24228" y1="475.4635" x2="374.22028" y2="494.44147"/> </defs> <sodipodi:namedview id="base" pagecolor="#ffffff" bordercolor="#666666" borderopacity="1.0" inkscape:pageopacity="0.0" inkscape:pageshadow="2" inkscape:zoom="1" inkscape:cx="23.03302" inkscape:cy="21.919067" inkscape:document-units="px" inkscape:current-layer="layer19" showgrid="false" inkscape:window-width="1152" inkscape:window-height="672" inkscape:window-x="0" inkscape:window-y="0" inkscape:window-maximized="0" showguides="true" inkscape:guide-bbox="true" fit-margin-top="0" fit-margin-left="0" fit-margin-right="0" fit-margin-bottom="0"> <inkscape:grid type="xygrid" id="grid7639" empspacing="5" visible="true" enabled="true" snapvisiblegridlinesonly="true"/> </sodipodi:namedview> <metadata id="metadata7021"> <rdf:RDF> <cc:Work rdf:about=""> <dc:format>image/svg+xml</dc:format> <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/> <dc:title/> </cc:Work> </rdf:RDF> </metadata> <g inkscape:groupmode="layer" id="layer17" inkscape:label="Corner" style="display:inline" transform="translate(-354.10983,-469.38827)"> <path style="fill:url(#linearGradient7602);fill-opacity:1;stroke:#4f657b;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" d="m 399.63695,479.30439 -0.14952,-1.57839 -7.82489,-7.80033 -1.44921,-0.0374 -0.60981,10.59724 z" id="path7594" inkscape:connector-curvature="0" sodipodi:nodetypes="cccccc"/> </g> <g inkscape:groupmode="layer" id="layer16" inkscape:label="Paper" style="display:inline" transform="translate(-354.10983,-469.38827)"> <path style="fill:url(#linearGradient7588);fill-opacity:1;stroke:url(#linearGradient7590);stroke-width:1;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" d="m 363.6875,469.90625 0,43.875 36.0625,0 0,-35.9375 -9.1875,0 c -0.009,0 0,-0.022 0,-0.0312 l 0,-7.90625 -26.875,0 z" id="rect7563" inkscape:connector-curvature="0"/> </g> <g inkscape:groupmode="layer" id="layer18" inkscape:label="Text" style="display:inline" transform="translate(-354.10983,-469.38827)"> <path style="fill:none;stroke:url(#linearGradient7633);stroke-width:1.02199996;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" d="m 365.25,512.8125 33.40625,0 c 0.009,0 0,-0.0219 0,-0.0312 l 0,-33.96875 c 0,-0.009 0.009,-0.0312 0,-0.0312 l -7.46875,-10e-5 0,0" id="rect7604" inkscape:connector-curvature="0" sodipodi:nodetypes="csssccc"/> <path style="fill:none;stroke:url(#linearGradient7672);stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" d="m 367.1875,480.875 15.96875,0 -15.96875,0 z m 0,3 15.96875,0 -15.96875,0 z m 0,3 15.96875,0 -15.96875,0 z m 0,3 15.96875,0 -15.96875,0 z m 0,3 26.96875,0 -26.96875,0 z m 0,2.96875 26.96875,0 -26.96875,0 z m 0,3 26.96875,0 -26.96875,0 z m 0,3.03125 26.96875,0 -26.96875,0 z m 0,2.96875 26.96875,0 -26.96875,0 z m 0,3 26.96875,0 -26.96875,0 z" id="path7635" inkscape:connector-curvature="0"/> <rect style="fill:url(#linearGradient7690);fill-opacity:1;stroke:url(#linearGradient7682);stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" id="rect7674" width="8.0367203" height="9.0336618" x="385.65042" y="480.87024" ry="0"/> </g> <g inkscape:groupmode="layer" id="layer19" inkscape:label="W" style="display:inline" transform="translate(-354.10983,-469.38827)"> <rect style="fill:url(#linearGradient7710);fill-opacity:1;stroke:url(#linearGradient7702);stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" id="rect7692" width="20.084324" height="19.981697" x="354.60983" y="474.8808"/> <g id="g7753"> <rect y="475.77466" x="355.60342" height="18.118425" width="18.082977" id="rect7712" style="fill:#ffffff;fill-opacity:1;stroke:url(#linearGradient7897);stroke-width:1.00213432;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"/> <path inkscape:connector-curvature="0" id="path7728" d="m 357.09251,479.32253 6.13216,0 0,2.06168 -2.04985,1.18348 0,1.82973 2.89566,-3.01321 0,-2.00881 3.06608,0 0,5.07489 2.11454,-3.11894 -1.11013,0 0,-1.95595 5.18062,0 0,2.00881 -0.79296,0 -5.07489,9.99119 -3.22466,0 0,-3.96476 -3.85904,3.91189 -2.22026,0 0,-10.04405 -0.95154,0 z" style="fill:url(#linearGradient7899);fill-opacity:1;stroke:none"/> </g> <path style="opacity:0.32242988;fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;filter:url(#filter7889)" d="m 371.78855,481.64853 -4.70485,9.30396 -1.48018,0" id="path7759" inkscape:connector-curvature="0"/> <path style="opacity:0.32242988;fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;filter:url(#filter7893)" d="m 369.46256,481.70139 -2.326,3.3304 -0.53287,-0.14279 0.10997,-5.09069 -1.05727,0 -4.07049,5.23348 -0.79295,0 0,1.21585 0,-3.48898" id="path7761" inkscape:connector-curvature="0"/> <path style="opacity:0.32242988;fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;filter:url(#filter7885)" d="m 364.01762,487.14632 -3.8326,3.8326 -0.55506,0" id="path7763" inkscape:connector-curvature="0"/> </g>
							</svg>;
						</div>
						<div>
							<a>' . $r['FILENAME'] . '</a>' . $mark . '
						</div>
						<div>
							<a class="btn btn-tiny btn-download" href="' . set_url($r['FILELINK']) . '">D</a>
							<button class="btn btn-tiny ' . $respon . '" ' . $prop . ' onclick="readResponseLaporan(\'' . $r['USRKEY'] . '\', \'' . $r['FILENAME'] . '\')" style="min-width: 26px;"> R </button>
						</div>
					</div>';
						}
						if ($r = 0) {
							echo "<a>Tidak Ada Laporan</a>";
						}
						?>
					</div>
				</div>
			<?php } ?>
		</div>
		<div id="modal" class="modal">
			<div class="modal-centered" style="width: 450px;">
				<div class="animate">
					<div class="content">
						<div class="title">
							<h1>Respons Laporan
								<span class="action-right">
									<a onclick="document.getElementById('modal').style.display='none'" class="btn btn-tiny btn-danger btn-close" title="Close Modal" style="float: right;"></a>
								</span>
							</h1>
							</h1>
						</div>
						<div class="container" id="contain">
							<div class="field">
								<div id=response></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			function readResponseLaporan(npm, skl) {
				var xhttp = new XMLHttpRequest();
				document.getElementById('modal').style.display = "block";
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("response").innerHTML = this.responseText;
					}
				};
				xhttp.open("GET", "<?= set_url('api/get_response') ?>?id=" + npm + "&object=" + skl, true);
				xhttp.send();
			}
		</script>
	</section>
</content>