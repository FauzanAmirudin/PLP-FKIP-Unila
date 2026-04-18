<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
/**
*	
*/

?>
<html lang="en">
	<head>
	<style>
	.galery{
		display:inline-block;
		}

	.galery .frame {
		background-color:#A6029B;
		float:left;
		width: 300px;
		height: 200px;
		padding:4px;
		margin:5px;
		box-shadow: 3px 2px 3px rgba(0,0,0, 0.20), 1px 2px 3px rgba(0,0,0, 0.29);
	}

	.caption {
		position: absolute;
		z-index: 21;
	}

	.thumbnail {
		height: 100%;
		overflow-y: hidden;
	}

	.galery img {
				width: 100%;
				}

	.galery .btn{
		position: relative;
		z-index: 222;
		float:right;
		display: block;
		text-align:center;
		width:50px;
		padding: 1px 12px 2px 8px;
		background-color: #f01e1e;
		text-decoration: none;
		color: #ffffff;
		border-radius: 2px;
		margin: 5px;
	}


	@media screen and (max-width:769px){
		.galery img {
			z-index: 20;
			max-height:none;
	 		max-width: 100%;
		}
		.galery .frame {
			width:auto;
			height:auto;
		}
	}
	</style>
	</head>

	<body>
<div id="stripHeader">
		<a><stong>Gallery</strong></a>
</div>
<div id="fieldContent">
	<div class="galery">
		 <?php
		 //scan folder and display them accordingly
		$folderGellery = "uploads/gallery";
		$results = scandir($folderGellery);
		foreach ($results as $result) {
				if ($result === '.' or $result === '..') continue;
				if (is_dir($folderGellery . '/' . $result)) {
					echo '
					<div class="frame">
						<div class="caption">
							<a href="galleryremove.php?name='.$result.'" class="btn" role="button">'.$result.'</a>
					</div>
				</div>';
				}
				if (is_file($folderGellery . DIRECTORY_SEPARATOR . $result) && $result != "index.html") {
						echo '
						<div class="frame">
							<div class="caption">
								<a>'.$result.'</a>
						</div>
							<div class="thumbnail">
									<img src="'.$folderGellery . '/' . $result.'" alt="'.$result.'">
						</div>
					</div>'.$result.'';
			 }
		 }
		 ?>
	</div>
</div> <!-- /container -->
