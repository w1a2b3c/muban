<?php
/**
 * 作用：验证码
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_code
{
    private $charset;
    private $code;
    private $codelen=4;
    private $width=100;
    private $height=40;
    private $img;
    private $font;
    private $fontsize=18;
    private $fontcolor;

    function __construct()
    {
        $this->font=SYS_PATH.'/public/fonts/elephant.ttf';
    }

    private function createCode()
    {
        $this->charset=(mt_rand(0,100)<50)?'0123456789':'abcdefghijklmnopqrstuvwxyz';
        $_len=strlen($this->charset)-1;
        for($i=0;$i<$this->codelen;$i++)
        {
            $this->code.=$this->charset[mt_rand(0,$_len)];
        }
    }

    private function createBg()
    {
        $this->img=imagecreatetruecolor($this->width,$this->height);
        $color=imagecolorallocate($this->img,255,255,255);
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
    }

    private function createFont()
    {
        $_x=$this->width/$this->codelen;
        $color=[[59,29,131],[247,35,140],[41,128,226],[100,60,251]];
        for($i=0;$i<$this->codelen;$i++)
        {
            $this->fontcolor=imagecolorallocate($this->img,$color[$i][0],$color[$i][1],$color[$i][2]);
            imagettftext($this->img,$this->fontsize,mt_rand(-30,30),intval($_x*$i+mt_rand(1,5)),intval($this->height/1.4),$this->fontcolor,$this->font,$this->code[$i]);
        }
    }

    private function createLine() 
    {
        for($i=0;$i<5;$i++)
        {
            $color=imagecolorallocate($this->img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        for($i=0;$i<5;$i++)
        {
            $color=imagecolorallocate($this->img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }

    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    function doimg()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    function getCode()
    {
        return strtolower(md5($this->code));
    }

}