<?php
/**
 * 作用：上传类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_upload
{
	public $msg;
	public $state;
	private $file;
	private $ext;
	public $oldname;
	private $newname;
	private $filesize;
	private $fileext;
	private $filepath;
	private $file_thumb;
	private $file_water;
	private $filetype;
	public $fileinfo;
	public $size;
	public $total;
	public $totalsize;
	public $index;
	public $root;

	function __construct($data)
	{
		$type=$data['type'];
		$file_thumb=getint((isset($data['thumb'])?$data['thumb']:0));
		$file_water=getint((isset($data['water'])?$data['water']:0));
		$this->size=getint((isset($data['size'])?$data['total']:0));
		$this->total=getint((isset($data['total'])?$data['total']:0));
		$this->index=getint((isset($data['index'])?$data['index']:0));
		$this->totalsize=getint((isset($data['totalsize'])?$data['totalsize']:0));
		$this->user=getint((isset($data['user'])?$data['user']:0));
		$this->face=getint((isset($data['face'])?$data['face']:0));
		$this->min=getint((isset($data['min'])?$data['min']:120));
		$this->islocal=getint((isset($data['islocal'])?$data['islocal']:0));
		$this->old=getint((isset($data['old'])?$data['old']:0));
		$this->favicon=getint((isset($data['favicon'])?$data['favicon']:0));
		$this->file='file';
		$this->fileinfo=[];
		$this->filetype=4;
		switch($type)
		{
			case 1:
				$this->ext=[".gif",".jpg",".jpeg",".png",".webp"];
				break;
			case 2:
				$this->ext=[".mp3",".m4a"];
				break;
			case 3:
				$this->ext=[".mp4"];
				break;
			case 5:
				$this->ext=[".ico"];
				break;
			default:
				$this->ext=[".gif",".jpg",".jpeg",".png",".webp",
					".mp3",".m4a",".mp4",
					".doc",".docx",".xls",".xlsx",".ppt",".pptx",
					".rar",".zip",".7z",".gz",".tar",
					".apk",".iso",".pdf",".txt",".pem",".crt",".ico"];
				break;
		}
		$this->file_thumb=$file_thumb;
		$this->file_water=$file_water;
		
		$this->root=$this->getRoot(0);
		$this->state='error';
		$this->upfile();
	}

	function upfile()
	{
		$isdemo=false;
		$admindir=S('admindir');
		if(M_NAME=='plug')
		{
			$isdemo=APP_DEMO;
		}
		if($admindir!='' && M_NAME==$admindir)
		{
			$isdemo=APP_DEMO;
		}
		if($isdemo)
		{
			$this->msg='/upfile/pic.jpg';
			$this->state='success';
			return;
		}
		if(!isset($_FILES[$this->file]))
		{
			$this->msg='来源错误(可能是空间禁止了上传)';
			return;
		}
		$file=$_FILES[$this->file];
		if(!$file)
		{
			$this->msg='没有文件上传（获取不到数据）';
			return;
		}
		if($file['error'])
		{
			$this->msg=$this->getError($file['error']);
			return;
		}
		if(!file_exists($file['tmp_name']))
		{
			$this->msg='找不到临时文件';
			return;
		}
		if(!is_uploaded_file($file['tmp_name']))
		{
			$this->msg='非法上传';
			return;
		}
		#本地文件名
		$this->oldname=$file['name'];
		#文件大小
		$this->filesize=$file['size'];
		#文件后缀
		$this->fileext=strtolower(strrchr($this->oldname,'.'));
		#新文件名
		$this->newname=time().mt_rand(1000,9999).$this->fileext;
		#使用原文件名
		if($this->old==1)
		{
			$this->newname=enhtml($this->oldname);
		}
		if($this->favicon==1)
		{
			$this->newname="favicon.ico";
		}
		$this->filepath=$this->getRoot(1,$this->user,$this->face,$this->favicon);
		$name='';
		if($this->size>0 && $this->total>0)
		{
			$this->filepath=$this->root;
			$name=md5($this->totalsize.$this->total).'_';
			$this->newname=$name.$this->index.$this->fileext;
		}
		#检查文件大小
		$max=config('upload_max')*1024*1024;
		if($this->filesize>$max || $this->size>$max || ($this->total*$this->size)>$max)
		{
			$this->msg='文件超出大小限制';
			return;
		}
		#检查文件类型
		if(in_array($this->fileext,['.php','.asp','.aspx','.jsp']))
		{
			$this->msg='非法文件类型';
			return;
		}
		if(!in_array($this->fileext,$this->ext))
		{
			$this->msg='文件类型错误';
			return;
		}
		if(in_array($this->fileext,array('.jpg','.gif','.jpeg','.png','.webp')))
		{
			if($this->size==0 && $this->total==0)
			{
				$imginfo=getimagesize($file['tmp_name']);
				if(empty($imginfo) || ($this->fileext=='.gif' && empty($imginfo['bits'])))
				{
					$this->msg='非法图像文件';
					return;
				}
			}
		}
		$tmp=file_get_contents($file['tmp_name']);

		filter_even($tmp);

		$num=preg_match_all("/(<script|<iframe|alert\(|eval\(|expression\(|prompt\(|base64\(|vbscript\(|msgbox\(|unescape\(|location.href|error_reporting\(|base64_decode\(|set_time_limit\(|str_replace\(|function_exists\(|\<\?php|;goto)/Ui",$tmp,$match);
		if($num>0)
		{
			$this->msg='非法文件';
			return;
		}
		#文件夹不存在时
		if(!is_dir($this->filepath) && $this->favicon==0)
		{
			#创建文件夹
			if(!mkfolder($this->filepath))
			{
				$this->msg='文件夹创建失败';
				return;
			}
		}
		#如果是图像文件
		if(preg_match('/^image\//i',$file['type']))
		{
			$this->filetype=1;
			if($this->size==0)
			{
				$image=new cms_image();
				#压缩
				if(getint(config('thumb_open'))==1 && $this->file_thumb==1 && $this->face==0)
		        {
		            $image->create_thumb($file['tmp_name'],config('thumb_min'));
		        }
		        #水印
		        if(getint(config('water_open'))==1 && $this->file_water==1)
		        {
		            $image->watermark($file['tmp_name']);
		        }
		        #头像处理
		        if($this->face>0 && config('upload_way')=='local')
		        {
		        	$image->create_thumb($file['tmp_name'],$this->min);
		        }
			}
		}
		
		$filename=$this->filepath.$this->newname;
		$fileway=config('upload_way');
		if($this->islocal==1)
		{
			$fileway='local';
		}
		$newroot=$this->getRoot();
		$class='upload_'.$fileway;
		cms::load($fileway,($fileway=='local')?'upload':'plug');
		if($fileway=='local' && ($this->index+1)==$this->total)
		{
			if(!is_dir($newroot))
            {
                #创建文件夹
                if(!mkfolder($newroot))
                {
                    $this->msg='文件夹创建失败';
                    return;
                }
            }
		}
		if(in_array($this->fileext,['.jpg','.jpeg','.png','.gif','.webp']))
		{
			$this->filetype=1;
		}
		$up=new $class();
		$result=$up->upload($file,$filename,$this->totalsize,$this->index,$this->total,$this->root,$newroot,$this->fileext,$name,$this->filetype,$this->file_thumb,$this->file_water);
		if($result)
		{
			$this->msg=$up->backurl;
			if($this->filetype>1)
			{
				if(in_array($this->fileext,[".mp3",".m4a"]))
				{
					$this->filetype=2;
				}
				if(in_array($this->fileext,[".mp4"]))
				{
					$this->filetype=3;
				}
			}
			$filesize=($this->totalsize>0)?(($this->totalsize*1024*1024)):$this->filesize;
			$this->fileinfo=['file_url'=>$this->msg,'file_name'=>enhtml($this->oldname),'file_ext'=>strtolower($this->fileext),'file_size'=>$filesize,'file_local'=>$fileway,'file_type'=>$this->filetype,'file_tmp'=>str_replace("\\","/", $file['tmp_name']),'file_update'=>time(),'file_ip'=>getip()];
			$this->state='success';
		}
		else
		{
			$this->msg='上传失败：'.$up->msg;
		}
	}

	function showmsg()
	{
		return E(['state'=>$this->state,'msg'=>$this->msg,'name'=>$this->oldname]);
	}

	private function getError($errorNo)
	{
        switch($errorNo)
        {
            case 1:
                return '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！';
                break;
            case 2:
                return '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！';
                break;
            case 3:
                return '文件只有部分被上传！';
                break;
            case 4:
                return '没有文件被上传！';
                break;
            case 6:
                return '找不到临时文件夹！';
                break;
            case 7:
                return '文件写入失败！';
                break;
            default:
                return '未知上传错误！';
        }
    }

    private function getRoot($type=1,$user=0,$face=0,$favicon=0)
    {
    	if($favicon==1)
    	{
    		return '';
    	}
    	$type=($type==1)?config('upload_file_folder'):0;
    	$root='upfile/';
    	if($user>0)
		{
			$root.='user/';
		}
    	if($face>0)
		{
			$root.='face/';
			return $root;
		}
    	switch($type)
		{
			case '0':
				$root.='temp/';
				break;
			case '1':
				$root.=date("Y").'/';
				break;
			case '2':
				$root.=date("Y").'/'.date("m").'/';
				break;
			case '3':
				$root.=date("Y").'/'.date("m").'/'.date("d").'/';
				break;
			default:
				$root.=date("Ym").'/';
				break;
		}
		return $root;
    }
}