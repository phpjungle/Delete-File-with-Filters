<?php
error_reporting(E_ERROR);
/**
 * 移除某个目录下面所有$filter文件目录
 * 
 * @author PHPJungle
 * @since 15.01.21 周三
 * @param 根目录 $path
 * @param 需要删除的文件夹名字 $filter
 * @return NULL|boolean
 */
function del_dir_filter($path,$filter='.svn'){
	if(!is_dir($path)){
		return null;
	}
	
	$fh = opendir($path);
	while(($row = readdir($fh)) !== false){ //Read entry from directory handle=> string the filename on success&return.falseforfailure;. 
		//过滤掉虚拟目录
		if($row == '.' || $row == '..'){
			continue;
		}
		
		$sub_path=$path.'/'.$row;
		
		// 过滤文件
		if(!is_dir($sub_path)){
			continue;
		}
		
		// 找到目标文件夹
		if($row===$filter){  
			del_dir($sub_path); // 整个删除
			continue;
		}
		// 过滤完剩下的目录继续递归
		del_dir_filter($sub_path);
	}
	
	//关闭目录句柄，否则出Permission denied
	closedir($fh);	
	return true;
}

/**
 * 删除指定文件夹（包括所有子文件和文件夹）
 * 
 * @author PHPJungle
 * @since 15.01.21 周三
 * @param string $path        	
 * @return NULL boolean
 */
function del_dir($path) {
	//给定的目录不是一个文件夹
	if(!is_dir($path)){
		return null;
	}

	$fh = opendir($path);
	while(($row = readdir($fh)) !== false){
		//过滤掉虚拟目录
		if($row == '.' || $row == '..'){
			continue;
		}
		$file_path=$path.'/'.$row;
		if(!is_dir($file_path)){
			($status_del=unlink($file_path)) or print('file:'.$file_path.' no permission to delete<hr>');
			!$status_del or print('file:'.$file_path.' has been deleted!<hr>');
			continue;
		}
		del_dir($file_path); // 如果是文件就没必要递归进去了
	}
	//关闭目录句柄，否则出Permission denied
	closedir($fh);
	//删除文件之后再删除自身
	if(!rmdir($path)){
		echo 'directory:',$path,' no permission to delete<hr>';
	}else{
		echo 'directory:',$path,' has been deleted!<hr>';
	}
	return true;
}

# Demo
$del=__DIR__.'/www/'; // 删除xxx目录下包含.svn的文件夹所有内容
del_dir_filter($del,'.svn');
