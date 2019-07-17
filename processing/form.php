<?php

//require 'form/val.php';
class Form{
	/**Common form manipulation methods
	*Reduces redundant code
	*Less code is better code :)
	*/

	/** 
	*@param $currentItem stores current item
	*/
	private $currentItem = null;

	/**
	*@param $fileUrl url of file in upload
	*/
	private $fileURL = null;

	private $_postData = array();

	private $_error = array();

	private $val;

	public function __construct(){

		//$this->val = new Val();

	}

	/**
	*Run $_POST
	*@param String $field name of the post to be fetched
	*@param boolean $uppercase Whether field should be converted to uppercase
	*@return form context
	*/

	public function post($field, $upperCase=false, $hash=false){
		$post = $_POST[$field];
		if($upperCase)
			$post = strtoupper($post);
        if($hash)
            $post = sha1(md5($post));
		$this->_postData[$field] = $post;
		$this->currentItem = $field;

		return $this;
	}

	/**
	*fetch form data
	*@param String field Optional name of field to be returned
	*@return Array if no $field is passed. Otherwise String is returned
	*/ 
	public function fetch($field = false){
		if(isset($this->_postData[$field]))
			return $this->_postData[$field];
		else
			return $this->_postData;
	}

	/**
	*Validate form data
	*@param String $typeOfValidator the validation check to be performed
	*@param type $arg argument/restrictions to be used when validating data
	*For example $arg = 3. 3 can be use to mean minimum number of characters
	*/
	public function val($typeOfValidator, $arg = null){
		if($arg == null)
			$result = $this->val->{$typeOfValidator}($this->_postData[$this->currentItem]);
		else
			$result = $this->val->{$typeOfValidator}($this->_postData[$this->currentItem], $arg);

		if($result){
			$this->_error[$this->currentItem] = $result;
			print_r($this->_error);
		}

		return $this;
	}

	/**
	*Submits data if validation is successful
	*@return true if submit is successful.
	*@return Exception if submit fails
	*/
	public function submit(){
		if(empty($this->_error))
			return true;
		else{
			foreach ($this->_error as $key => $value) {
				$str .= $key."->".$value."\n";
			}
			throw new Exception($str);
		}
			
	}

	/**
	*function to upload file
	*@param $field actual file in upload
	*@return String Url of uploade file
	*/
	public function uploadImage(){
		$target_dir = "./public/images/";
		$target_file = $target_dir . basename($_FILES['image']["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		$this->fileURL = URL. "public/images/". basename($_FILES['image']["name"]);
		// Check if image file is a actual image or fake image
	    $check = getimagesize($_FILES['image']["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = 1;
	    } else {
	        $uploadOk = 0;
	    }
		// Check if file already exists
		if (file_exists($target_file)) {
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded. Please check that you uploaded an actual image.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
		        $this->_postData["image"] = $this->fileURL;
		        $this->currentItem = $this->fileURL;
		        return $this;
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
	}
}