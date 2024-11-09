<?php
/**
 * 作用：本地上传类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class upload_local
{
    public $backurl='';
    public $msg='';
    
    function upload($file,$filename,$totalsize,$index,$total,$root,$newroot,$fileext,$name,$filetype,$file_thumb,$file_water)
    {
        $state=move_uploaded_file($file['tmp_name'],SYS_PATH.$filename);
        if($state)
        {
            $this->backurl=WEB_ROOT.$filename;
        }
        else
        {
            $this->msg='上传失败';
        }
        if(($index+1)==$total)
        {
            $this->backurl=self::merge($index,$total,$root,$newroot,$fileext,$name,$filetype,$file_thumb,$file_water);
        }
        return $state;
    }

    /*合并文件*/
    function merge($index,$total,$root,$newroot,$fileext,$name,$filetype,$file_thumb,$file_water)
    {
        
        $filename=$newroot.time().mt_rand(1000,9999).$fileext;
        
        $file_handle=fopen($filename,'a');
        for($i=0;$i<$total;$i++)
        {
            $result=fwrite($file_handle,file_get_contents($root.'/'.$name.$i.$fileext));
        }
        fclose($file_handle);
        for($i=0;$i<$total;$i++)
        {
            unlink($root.'/'.$name.$i.$fileext);
        }
        if($filetype==1)
        {
            $image=new cms_image();
            #压缩
            if(getint(config('thumb_open'))==1 && $file_thumb==1)
            {
                $image->create_thumb($filename,config('thumb_min'));
            }
            #水印
            if(getint(config('water_open'))==1 && $file_water==1)
            {
                $image->watermark($filename);
            }
        }
        return WEB_ROOT.$filename;
    }

}