<?php

/**
*
* Vidoza Class
* @author JM <https://t.me/httd1>
* @link https://github.com/vidozaphp
* @link https://vidoza.net/api
*
**/

class Vidoza
{
	
	# Token authorization
	private $token;
	
	# URL-Base for requests
	const BASE_URL='https://api.vidoza.net';
	
	function __construct ($token)
	{
		$this->token=$token;
		}
		
	/**
	*
	* Upload a file
	*
	* @param string $file - File for upload
	* @param array $addOption - Optional parameters
	*
	**/
	
	public function uploadFile ($file, $addOption=null)
	{
		
		$data=$this->requestUrl ();
		
		// URL for upload
		$urlUpload=$data ['data']['upload_url'];
		
		// parameters for upload
		$param=$data ['data']['upload_params'];

		if (function_exists('curl_file_create')) {
			$param ['file']=curl_file_create($file);
			}else {
				$param ['file']='@'.realpath($file);
				}
				
		if ($addOption != null && is_array ($addOption)){
			$param+=$addOption;
			}
				
		$requestUpload=$this->request ($urlUpload, 'POST', $param);
			
			return $requestUpload;
		
		}
		
	/**
	*
	* Check whether your file is active or deleted.
	*
	* @param array $ids - List IDs of files.
	*
	**/
		
	public function statusFiles (array $ids)
	{
		
		$query ['f']=$ids;
		$query=http_build_query ($query);
		
		$getStatus=$this->request (self::BASE_URL.'/v1/files/check?'.$query, 'GET', null, true);
		
			return $getStatus;
		
		}
		
	/**
	*
	* Shows the list of your folders or a epecific folder.
	* @param int $id_folder - ID of folder.
	* @param int $page - ID page of results.
	*
	**/
		
	public function listFolders ($id_folder=null, $page=1)
	{
		
		if ($id_folder == null){
			
			$getFolders=$this->request (self::BASE_URL.'/v1/folders?page='.$page, 'GET', null, true);
			
			}elseif ($id_folder != null && is_int ($id_folder)){
				
				$getFolders=$this->request (self::BASE_URL.'/v1/folders/'.$id_folder, 'GET', null, true);
				
				}
					
			return $getFolders;
		
		}
	
	/**
	*
	* Rename folder.
	*
	* @param int $id_folder - ID folder.
	* @param string $name - New name folder.
	*
	**/
	
	public function renameFolder ($id_folder, $name)
	{
		
		$renameFolder=$this->request (self::BASE_URL.'/v1/folders/'.$id_folder.'?name='.$name, 'PUT', null, true);
		
			return $renameFolder;
		
		}
		
	/**
	*
	* Create new folder.
	*
	* @param string $name - Name folder.
	* @param int $id_folder - ID folder.
	*
	**/
		
	public function createFolder ($name, $id_folder=0)
	{
		
		$createFolder=$this->request (self::BASE_URL.'/v1/folders?name='.$name.'&parent_id='.$id_folder, 'POST', null, true);
		
			return $createFolder;
		
		}
		
	private function requestUrl ()
	{
		
		$getUrl=$this->request (self::BASE_URL.'/v1/upload/http/server', 'GET', null, true);
		
			return $getUrl;
			
		}
		
	private function request ($url, $method='GET', $data=null, $header=false)
	{

	$connect=curl_init ();

	curl_setopt ($connect, CURLOPT_URL, $url);
	curl_setopt ($connect, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($connect, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt ($connect, CURLOPT_SSL_VERIFYHOST, false);

	if ($header === true){
		
		$header=[
		'Authorization: Bearer '.$this->token,
		];
		
		curl_setopt ($connect, CURLOPT_HTTPHEADER, $header);
		
		}
	
	if ($method == 'POST'){
		curl_setopt ($connect, CURLOPT_POST, true);
		curl_setopt ($connect, CURLOPT_POSTFIELDS, $data);
			}else if ($method == 'PUT'){
				curl_setopt ($connect, CURLOPT_PUT, true);
				}
				
	$request=curl_exec ($connect);
	
	if ($request == false){
		return 'Error: '.curl_error ($connect);
		}
	
		return json_decode ($request, true);

	}
	
}