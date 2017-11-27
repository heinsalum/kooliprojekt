<?php
	class Photoupload {
		/*public $publicTest;
		public $privateTest;*/
		private $tempName;
		private $imageFileType;
		private $textToImage;
		public $exifToImage;
		private $myTempImage;
		private $myImage;
		private $marginRight;
		private $marginBottom;
		
		
		function __construct($fileToUpload, $fileType){
			/*$this->publicTest = "Täitsa avalik!";
			$this->privateTest = $x;*/
			$this->tempName = $fileToUpload;
			$this->imageFileType = $fileType;
			$this->marginRight = 10;
			$this->marginBottom = 10;
		}
		
		public function readExif(){
			//loeme EXIF infot, millal pilt tehti
			@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
			//var_dump($exif);
			if(!empty($exif["DateTimeOriginal"])){
				$this->exifToImage = "Pilt tehti: " .$exif["DateTimeOriginal"];
			} else {
				$this->exifToImage = "Pildistamise aeg teadmata!";
			}
		}
		
		private function createImage(){
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}			
		}
		
		public function resizeImage($width, $height){
			$this->createImage();
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			$sizeRatio = 1;
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			$this->myImage = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, round($imageWidth /$sizeRatio), round($imageHeight / $sizeRatio));		
		}

		private function resize_image($image, $origW, $origH, $w, $h){
			$dst = imagecreatetruecolor($w, $h);
			//asendame musta värvi pikslid läbipaistvatega, et säilitada vajadusel png piltide läbipaistvus
			imagesavealpha($dst, true);
			$transColor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
			imagefill($dst, 0, 0, $transColor);
			imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
			return $dst;
		}

		public function savePhoto($directory, $fileName){
			var_dump($directory);
			echo getcwd();
			$target_file = $directory . "/".$fileName;
			var_dump($target_file);
			$notice = "";
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			if($this->imageFileType == "gif"){
				if(imagegif($this->myImage, $target_file, 95)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
		}	
		
		public function clearImages(){
			imagedestroy($this->myImage);
			imagedestroy($this->myTempImage);	
		}	
		
	}//class lõppeb
?>