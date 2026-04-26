<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *  Page untuk memvalidasi data berkas perserta
 */

?>
	<div class="content">
		<div class="field">
			<h1>Sejarah Versi</h1>
			<div class="timeline">
				<div class="timeline__items  padding-top-25 padding-bottom-30">
					<div class="timeline__item">
						<span class="timeline__item-title">v2.3</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Hot Fix:
							<ul>
								<li>Memperbaiki error dikarenakan penggunaan tanda baca kutip pada nama.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v2.2</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Hot Fix:
							<ul>
								<li>Memperbaiki urutan daftar laporan di halaman periksa laporan.</li>
								<li>Memperbaiki di mana peserta tidak dihitung karena tidak menyelesaikan pendaftaran di halaman monitor.</li>
								<li>Memperbaiki peserta yang tidak tampil di peserta yang valid.</li>
								<li>Memperbaiki formulir pendaftaran.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v2.1</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Bug Fix and Improve SQL Structure:
							<ul>
								<li>Mengubah kedalam fungsi untuk skrip yang berulang dengan tugas yang sama pada pembungkus SQL</li>
								<li>Menghapus kode yang tidak perlu</li>
								<li>Hapus  PHP Documentor dalam  alat pengembangan komposer</li>
								<li>Perbaiki bug, pencarian NPM tidak bekerja di halaman validasi peserta </li>
								<li>Ubah permintaan basis data dalam cekLaporan dan daftar peserta dengan pembungkus SQL baru</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v2.0</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Improve scalability, stability, and add application functionality:
							<ul>
							Kerangka
									<ul>
										<li>Remap file halaman untuk meningkatkan kompabilitas.</li>
										<li>Buat lingkungan perawatan. *</li>
										<li>Buat kompiler dan penginstal aplikasi agar mudah dipasang. *</li>
										<li>Meningkatkan kemampuan SQL wrapper. *</li>
										<li>Perbaiki masalah tanda kutip dalam pembungkus SQL. *</li>
										<li>Menambahkan suport plugin commposer.</li>
										<li>Menggunakan GULP sebagai manajer aset. *</li>
										<li>Menambahkan formulir tag metadata tajuk untuk meningkatkan kompabilitas.</li>
										<li>membangun browserconfig dan manifes untuk meningkatkan kompabilitas.</li>
										<li>Menambahkan ikonpaket untuk meningkatkan kompabilitas.</li>
									</ul>
								System
									<ul>
										<li>Redesigning the system and database to use a single table.</li>
										<li>Delete the database field that is not used.</li>
										<li>Create permission administrator (support login and level). *</li>
										<li>Create a global configuration database controller to create user configurations that can be changed.</li>
										<li>Redesign how valid data is stored in the database, including history of who and when it changed.</li>
										<li>Fix error handlers that appear when data is not found.</li>
										<li>Secures the user's pass using new encryption.</li>
										<li>Redesign the printing registration form.</li>
										<li>Change all the words "PPL" to "PLP".</li>
										<li>Build a data monitoring page for participant recap  each year. *</li>
										<li>Build a verification page for operators. *</li>
										<li>Build a site configuration page for the operator. *</li>
										<li>Build report monitoring page to check all reports. *</li>
										<li>Stores additional data in the download form requirements to improve user experience. *</li>
										<li>Adds PRODI selection to and database to the accommodation request form. *</li>
										<li>Adds monitor and operator roles. *</li>
										<li>Adds the previous response column in the report response window. *</li>
										<li>Fix the name of the DPL and the admin doesn't appear.</li>
										<li>Fix downloading all reports.</li>
										<li>Fix registration form download handler.</li>
										<li>Fix check check in student reports.</li>
										<li>Update the PDF Plugin to version 7.0.0.</li>
										<li>Fix report response to use database access and new system structure.</li>
										<li>Migrate data to new database.</li>
									</ul>
								UI
									<ul>
										<li>Migrate to SASS for easier development.</li>
										<li>Redesign the page layout style for easier to expand.</li>
										<li>Redesign the column style for stability and universal use.</li>
										<li>Redesign the modal close button function for universal use.</li>
										<li>Fixes the input file style to improve the view.</li>
										<li>Change the size of the modal close button on the small display for easier to touch.</li>
										<li>Reconstruct form page to use new column styles and increase stability.</li>
										<li>Fix all pages to use the new UI style.</li>
										<li>Redesign the function script for the field action button to work globally use.</li>
										<li>Enhance visual design field action buttons to enhance user experience.</li>
										<li>Adds a Zoom in button to increase readability in the field action button. *</li>
										<li>Add a checkbox style. *</li>
										<li>Adds a pie and bar chart. *</li>
										<li>Adds a print appearance for multiple pages. *</li>
										<li>Adds file status notifications in loby. *</li>
										<li>Adds Catholicism to the list of religions.</li>
										<li>Clean the local page style.</li>
									</ul>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v1.8</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Create basic framework:
							<ul>
								<li>Beberapa perbaikan bug.</li>
								<li>Ubah database awalan kerja ke tahun ini.</li>
								<li>Gunakan konstanta sebagai jangkar jalur.</li>
								<li>Merekonstruksi inti aplikasi, untuk membuat kerangka kerja dasar.</li>
								<li>Merekonstruksi file aplikasi struktur.</li>
								<li>Merekonstruksi file konfigurasi.</li>
								<li>Merekonstruksi file basis data.</li>
								<li>Coba bungkus basis data prototipe.</li>
								<li>Perbaiki gaya modal masuk.</li>
								<li>Perbaiki pengendali Unduhan.</li>
								<li>Tambahkan data cek lengkap dalam formulir bio-data.</li>
								<li>menghapus id dari mengatur ulang kata sandi untuk memperbaiki kesalahan di konsol.</li>
								<li>Hapus UCWORD dan perbaiki struktur dalam bentuk bio-data.</li>
								<li>Buat atur ulang kata sandi pengguna.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v1.6</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Unofficial update:
							<ul>
								<li>Small bug fix.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v1.5</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Improve admin role:
							<ul>
								<li>Small bug fix.</li>
								<li>Add DPL cek on Mahasiswa Report and leave a note.</li>
								<li>Remove unnesesery code.</li>
								<li>Build database SQL wraper to improve Scalability app.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v1.3</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Hot fix:
							<ul>
								<li>Small bug fix.</li>
								<li>Add DPL cek on Mahasiswa Report and leave a note.</li>
								<li>Remove unnesesery code.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v1.0</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text timeline__item-text--bold">Last chelk for public relelease:
							<ul>
								<li>Bug fix in notification page system.</li>
								<li>Small tweak.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">v0.8</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text">Impove Scalability :
							<ul>
								<li>Improve and change made in style.</li>
								<li>Improve material design.</li>
								<li>Add some content in some pages.</li>
								<li>Remove unused files.</li>
								<li>Remove some code that for testing purpose.</li>
							</ul>
						</div>
						<div class="list-pics list-pics--sm padding-l-20">
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">Beta</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text padding-top-5">Improving website fiture:
							<ul>
								<li>Add admin page.</li>
								<li>Add Cek Laporan page for DPL.</li>
								<li>Add Jadwal page.</li>
								<li>Add Setting page.</li>
								<li>Add bulk download system.</li>
								<li>Improve material design.</li>
								<li>Add 404 pages.</li>
								<li>Try to add chart.</li>
								<li>Tidy up same data handler.</li>
								<li>Tidy up asset files.</li>
							</ul>
						</div>
					</div>
					<div class="timeline__item">
						<span class="timeline__item-title">Alpha</span>
						<div class="timeline__item-cricle"></div>
						<div class="timeline__item-text padding-top-5">Migrating from old Website:
							<ul>
								<li>Clean the code.</li>
								<li>Tidy up files.</li>
								<li>Remove unnesesery code.</li>
								<li>Remove unnesesery pages.</li>
								<li>Improve the structure.</li>
								<li>Improve system login and data handling.</li>
								<li>Improve the style material design.</li>
								<li>Improve the loby page.</li>
								<li>Improve the menu and sidebar.</li>
								<li>Add upload laporan page.</li>
								<li>Add lokasi penepatan page.</li>
								<li>Add mobile layout design.</li>
								<li>.etc.</li>
							</ul
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
