<?php
/*
 * File
 */
namespace Common\Common;
class File {
   
   static $FORMAT=array('jpg','jpeg','gif','png','bmp','swf','rar','txt');

   /*
	*删除文件
	*/
	public function processDeleFiles($files){
		if(!$files){
			return ;
		}
		//字符串格式
		if(!is_array($files)){
			$files=C('CACHE_DIR').$files;
			if(file_exists($files)){
				unlink($files);
			}
			return ;
		}
		//数组格式
		foreach($files as $file){
			$file=C('CACHE_DIR').$file;
			if(file_exists($file)){
				unlink($file);
			}
		}
	}

	/*
	 *删除从内容中匹配的文件
	 *一般在删除文章的场景下使用
	 */
	public function processDeleFilesForContent($content){
		if(!$content){
			return ;
		}
		$preg='/[\/|\w]+\.('.join('|',self::$FORMAT).')/i';
		//匹配旧内容 没有文件匹配则跳出函数
		$matches=array();
		if(preg_match_all($preg,$content,$matches)){
			$this->processDeleFiles($matches[0]);
		}
	}

	/*
	 *移动临时文件夹的图片到正式图片存储目录中
	 *$file1 新的内容
	 *$file2 旧的内容
	 */
	public function processMoveFilesFromTmpsToStorage($file1,$file2='',$image_size=''){
		if(!$file1){
			//新内容关联文件为空则只需要删除旧内容关联文件
			$this->processDeleFiles($file2);
		}elseif(!$file2){
			//旧内容关联文件为空则移动新内容关联文件
			$file1=$this->moveFilesFromTmpsToStorage($file1,$image_size);
		}elseif($file1!=$file2){
			$file1=$this->moveFilesFromTmpsToStorage($file1,$image_size);
			$this->processDeleFiles($file2);
		}
		return $file1;
	}
	
	/*
	 *移动临时文件夹的文件到正式文件夹中
	 *$file 多个文件约定用逗号分隔
	 */
	private function moveFilesFromTmpsToStorage($file_param){
		if(!$file_param){
			return ;
		}
		$files=explode(',',$file_param);
		foreach($files as $file){
			$file=C('CACHE_DIR').$file;
			/*
			 * 非通过上传方式的图片 $image路径经过（$image=  dirname(C('UPLOAD')).$image;）处理后file_exists函数会返回false
			 * 可以放心使用 不会删除本站引用的图片
			 */
			if(file_exists($file)){
				//replace text
				$toFile=str_replace('Tmps', 'System', $file);
				$toFileDir=pathinfo($toFile);
				if(!file_exists($toFileDir['dirname'])){
					mkdir($toFileDir['dirname'],0755,true);
				}
				rename($file, $toFile);
			}
		}
		return preg_replace('/\/+Uploads\/+Tmps/i', '/Uploads/System', $file_param);
	}

	/*
	 *处理详细内容中的图片
	 *$content1 新的内容
	 *$content2 旧的内容
	 */
	public function processMoveFilesFromTmpsToStorageForContent($content1,$content2=''){
		if($content2){
			$this->processContentDiffFiles($content1,$content2);
		}
		$this->moveFilesFromTmpsToStorageForContent($content1);
		$content1=preg_replace('/\/+Uploads\/+Tmps/i', '/Uploads/System', $content1);
		return $content1;
	}

	/*
	 *对比并处理新旧内容中图片差异
	 *$content1 新内容
	 *$content2 旧内容
	 */
	private function processContentDiffFiles($content1,$content2=''){
		//旧内容为空则跳出函数
		if(!$content2){
			return ;
		}
		$preg='/[\/|\w]+\.('.join('|',self::$FORMAT).')/i';
		//匹配旧内容 没有图片匹配则跳出函数
		$matches_o=array();
		if(!preg_match_all($preg, $content2,$matches_o)){
			return ;
		}
		//匹配新内容 
		$matches_n=array();
		if(!preg_match_all($preg, $content1,$matches_n)){
			//新的内容中没有图片 则删除所有旧内容中的图片
			if(!$matches_o[0]){
				return ;
			}
			$this->processDeleFiles($matches_o[0]);
			return ;
		}

		//检查旧内容中的图片在新内容中是否存在 若不存在则删除
		foreach($matches_o[0] as $file){
			if(!in_array($file, $matches_n[0])){
				$this->processDeleFiles($file);
			}
		}

	}

	/*
	 *移动临时文件到正式存储文件夹 临时文件夹会被定时清空
	 */
	private function moveFilesFromTmpsToStorageForContent($content){
		if(!$content){
			return ;
		}
		if(preg_match_all('/[\/|\w]+\.('.join('|',self::$FORMAT).')/i', $content,$matches)){
			foreach($matches[0] as $file){
				$file=C('CACHE_DIR').$file;
				/*
				 * 非通过上传方式的图片 $image路径经过（$image=  dirname(C('UPLOAD')).$image;）处理后file_exists函数会返回false
				 * 可以放心使用 不会删除本站引用的图片
				 */
				if(file_exists($file)){
					//replace text
					$toFile=str_replace('Tmps', 'System', $file);
					$toFileDir=pathinfo($toFile);
					if(!file_exists($toFileDir['dirname'])){
						mkdir($toFileDir['dirname'],0755,true);
					}
					rename($file, $toFile);
				}
			}
		}
	}

}