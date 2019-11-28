<?php
// 类库名称：GD库生成海报
// +----------------------------------------------------------------------
// | PHP version 5.6+
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.myzy.com.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 阶级娃儿 <262877348@qq.com> 群：304104682
// +----------------------------------------------------------------------
namespace think;
use think\facade\Env;

class ImageLib
{
	public $im;
	public $width;
	public $height;
	public $dir_path;

	function __construct($width, $height, $background = '255, 254, 253')
	{
		$this->width    = $width;
		$this->height   = $height;
		$this->dir_path = Env::get('root_path');
		$this->im       = imagecreatetruecolor($this->width, $this->height);
	}

	public function printImage($image, $new_url = '')
	{
		// 创建颜色
		// 黑色
		$black      = imagecolorallocate($this->im, 0, 0, 0);
		// 绿色
		$green      = imagecolorallocate($this->im, 0, 100, 0);
		// 白色
		$white      = imagecolorallocate($this->im, 255, 255, 255);
		// 底色
		$color      = imagecolorallocate($this->im, 255, 254, 253);
		// 红色
		$red        = imagecolorallocate($this->im, 255, 0, 0);
		// 灰色
		$grey       = imagecolorallocate($this->im, 47, 79, 79);
		// 黄色
		$orange     = imagecolorallocate($this->im, 255, 165, 0);
		// 淡灰色
		$gray       = imagecolorallocate($this->im, 177, 177, 177);
		// 淡黑色
		$gray_black = imagecolorallocate($this->im, 124, 124, 124);

		imagefill($this->im, 0, 0, $color);

		$font_file_msyhbd = $this->dir_path.'public/static/fonts/msyhbd.ttc';
		$font_file_msyh   = $this->dir_path.'public/static/fonts/msyh.ttc';
		$font_file_apple  = $this->dir_path.'public/static/fonts/apple.ttf';
		$font_file_jianti = $this->dir_path.'public/static/fonts/jianti.ttf';

		// 商品图片
		$goods = $image['url'];
		list($goods_w,$goods_h) = getimagesize($goods);
		$goods_res = imagecreatefromjpeg($goods);
		imagecopyresized($this->im, $goods_res, 10, 10, 0, 0, 340, 340, $goods_w, $goods_h);

		// 价格
		$price = $this->dir_path.'public/base//images/price.png';
		list($price_w,$price_h) = getimagesize($price);
		$price_res = imagecreatefrompng($price);
		imagecopyresized($this->im, $price_res, 231, 280, 0, 0, $price_w, $price_h, $price_w, $price_h);
		imagettftext($this->im, 16,0, 290, 315, $orange, $font_file_apple, $image['price']);
		imagettftext($this->im, 16,0, 291, 317, $white, $font_file_apple, $image['price']);

		// 图标
		if ($image['mall_type']) {
			$logo           = $this->dir_path.'public/base//images/tmall.png';
			$mall_type_mame = '天猫价';
		} else {
			$logo           = $this->dir_path.'public/base//images/taobao.png';
			$mall_type_mame = '淘宝价';
		}

		list($logo_w,$logo_h) = getimagesize($logo);
		$logo_res = imagecreatefrompng($logo);
		imagecopyresized($this->im, $logo_res, 10, 360, 0, 0, 15, 15, $logo_w, $logo_h);
		imagettftext($this->im, 10, 0, 30, 373, $black, $font_file_msyh, $this->cut_text($image['title'], 23));
		imagettftext($this->im, 10, 0, 10, 410, $red, $font_file_msyh, "券后价￥");
		imagettftext($this->im, 16,0, 61, 410, $orange, $font_file_apple, $image['price']);
		imagettftext($this->im, 16,0, 63, 411, $red, $font_file_apple, $image['price']);
		imagettftext($this->im, 10,0, 140, 410, $gray, $font_file_apple, $mall_type_mame.$image['size']);
		imagettftext($this->im, 10,0, 148, 409, $gray, $font_file_apple, '----------');
		imagettftext($this->im, 10,0, 10, 430, $gray_black, $font_file_apple, $image['format_volume']);

		// 二维码
		$code                 = $image['user_code'];
		list($code_w,$code_h) = getimagesize($code);
		$code_res             = imagecreatefromjpeg($code);
		imagecopyresized($this->im, $code_res, 30, 480, 0, 0, 110, 110, $code_w, $code_h);

		// 指纹
		$zhiwen                   = $this->dir_path.'public/base//images/zhiwen.jpg';
		list($zhiwen_w,$zhiwen_h) = getimagesize($zhiwen);
		$zhiwen_res               = imagecreatefromjpeg($zhiwen);
		imagecopyresized($this->im, $zhiwen_res, 230, 490, 0, 0, 43, 43, $zhiwen_w, $zhiwen_h);
		imagettftext($this->im, 10, 0, 180, 560, $black, $font_file_apple, '"'.$image['nickname'].'" 分享好物');
		imagettftext($this->im, 8, 0, 217, 580, $black, $font_file_apple, '指纹长按识别');
		imagettftext($this->im, 8, 0, 60, 625, $grey, $font_file_jianti, '本活动有效期截至'.$image['expiry_time'].'日，请详细阅读');

		ob_clean();
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header("content-type: image/png");

		if (empty($new_url)) {
			imagejpeg($this->im);
		} else {
			imagepng($this->im, $new_url);
		}

		imagedestroy($this->im);
	}

	/**
	 * [subtext 截取字符串,带中文,多余的省略号代替]
	 * @param  [type] $text   [字符串]
	 * @param  [type] $length [长度]
	 */
	public function cut_text($text, $length = 4, $suffix = '...')
	{
	    if(mb_strlen($text, 'utf8') > $length) {
	        return mb_substr($text, 0, $length, 'utf8').$suffix;
	    } else {
	        return $text;
	    }
	}
}