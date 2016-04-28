<?php
/**
 * File: function.php 
 * Date: 2016/1/4
 * Time: 16:32
 */
function C($key){
    if (strpos($key, '.')) {
        $key = explode('.', $key);
        if (isset($key[2])) {
            return $GLOBALS['setting_config'][$key[0]][$key[1]][$key[2]];
        } else {
            return $GLOBALS['setting_config'][$key[0]][$key[1]];
        }
    } else {
        return $GLOBALS['setting_config'][$key];
    }
}
/**
 * 商城会员中心使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @param string $store_domian 店铺二级域名
 * @return string
 */
function urlShop($act = '', $op = '', $args = array(), $store_domain = ''){
    // 开启店铺二级域名
    if (intval(C('enabled_subdomain')) == 1 && !empty($store_domain)) {
        return 'http://' . $store_domain . '.' . SUBDOMAIN_SUFFIX . '/';
    }
    // 默认标志为不开启伪静态
    $rewrite_flag = false;
    // 如果平台开启伪静态开关，并且为伪静态模块，修改标志为开启伪静态
    $rewrite_item = array('goods:index', 'goods:comments_list', 'search:index', 'show_store:index', 'show_store:goods_all', 'article:show',
        'article:article', 'document:index', 'brand:list', 'brand:index', 'show_groupbuy:index', 'show_groupbuy:groupbuy_soon', 'show_groupbuy:groupbuy_history',
        'show_groupbuy:groupbuy_detail', 'pointprod:index', 'pointvoucher:index', 'pointprod:pinfo', 'pointprod:plist');
    if (URL_MODEL && in_array($act . ':' . $op, $rewrite_item)) {
        $rewrite_flag = true;
        $tpl_args = array(); // url参数临时数组
        switch ($act . ':' . $op) {
            case 'search:index':
                if (isset($args['keyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['cate_id'] = empty($args['cate_id']) ? 0 : $args['cate_id'];
                $tpl_args['b_id'] = empty($args['b_id']) || intval($args['b_id']) == 0 ? 0 : $args['b_id'];
                $tpl_args['a_id'] = empty($args['a_id']) || intval($args['a_id']) == 0 ? 0 : $args['a_id'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'show_store:goods_all':
                if (isset($args['keyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['store_id'] = empty($args['store_id']) ? 0 : $args['store_id'];
                $tpl_args['stc_id'] = empty($args['stc_id']) ? 0 : $args['stc_id'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'brand:list':
                $tpl_args['brand'] = empty($args['brand']) ? 0 : $args['brand'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'show_groupbuy:index':
            case 'show_groupbuy:groupbuy_soon':
            case 'show_groupbuy:groupbuy_history':
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['groupbuy_class'] = empty($args['groupbuy_class']) ? 0 : $args['groupbuy_class'];
                $tpl_args['groupbuy_price'] = empty($args['groupbuy_price']) ? 0 : $args['groupbuy_price'];
                $tpl_args['groupbuy_order_key'] = empty($args['groupbuy_order_key']) ? 0 : $args['groupbuy_order_key'];
                $tpl_args['groupbuy_order'] = empty($args['groupbuy_order']) ? 0 : $args['groupbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'goods:comments_list':
                $tpl_args['goods_id'] = empty($args['goods_id']) ? 0 : $args['goods_id'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            default:
                break;
        }
    }

    return url($act, $op, $args, $rewrite_flag, SHOP_SITE_URL);
}
/**
 * 拼接动态URL，参数需要小写
 *
 * 调用示例
 *
 * 若指向网站首页，可以传空:
 * url() => 表示act和op均为index，返回当前站点网址
 *
 * url('search,'index','array('cate_id'=>2)); 实际指向 index.php?act=search&op=index&cate_id=2
 * 传递数组参数时，若act（或op）值为index,则可以省略
 * 上面示例等同于
 * url('search','',array('act'=>'search','cate_id'=>2));
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @param boolean $model 默认取当前系统配置
 * @param string $site_url 生成链接的网址，默认取当前网址
 * @return string
 */
function url($act = '', $op = '', $args = array(), $model = false, $site_url = '')
{
    //伪静态文件扩展名
    $ext = '.html';
    //入口文件名
    $file = 'index.php';
//    $site_url = empty($site_url) ? SHOP_SITE_URL : $site_url;
    $act = trim($act);
    $op = trim($op);
    $args = !is_array($args) ? array() : $args;
    //定义变量存放返回url
    $url_string = '';
    if (empty($act) && empty($op) && empty($args)) {
        return $site_url;
    }
    $act = !empty($act) ? $act : 'index';
    $op = !empty($op) ? $op : 'index';

    $model = $model ? URL_MODEL : $model;

    if ($model) {
        //伪静态模式
        $url_perfix = "{$act}-{$op}";
        if (!empty($args)) {
            $url_perfix .= '-';
        }
        $url_string = $url_perfix . http_build_query($args, '', '-') . $ext;
        $url_string = str_replace('=', '-', $url_string);
    } else {
        //默认路由模式
        $url_perfix = "act={$act}&op={$op}";
        if (!empty($args)) {
            $url_perfix .= '&';
        }
        $url_string = $file . '?' . $url_perfix . http_build_query($args);
    }
    //将商品、店铺、分类、品牌、文章自动生成的伪静态URL使用短URL代替
    $reg_match_from = array(
        '/^goods-index-goods_id-(\d+)\.html$/',
        '/^show_store-index-store_id-(\d+)\.html$/',
        '/^show_store-goods_all-store_id-(\d+)-stc_id-(\d+)-key-([0-5])-order-([0-2])-curpage-(\d+)\.html$/',
        '/^article-show-article_id-(\d+)\.html$/',
        '/^article-article-ac_id-(\d+)\.html$/',
        '/^document-index-code-([a-z_]+)\.html$/',
        '/^search-index-cate_id-(\d+)-b_id-([0-9_]+)-a_id-([0-9_]+)-key-([0-3])-order-([0-2])-type-([0-2])-area_id-(\d+)-curpage-(\d+)\.html$/',
        '/^brand-list-brand-(\d+)-key-([0-3])-order-([0-2])-type-([0-2])-area_id-(\d+)-curpage-(\d+)\.html$/',
        '/^brand-index\.html$/',
        '/^show_groupbuy-index-area_id-(\d+)-groupbuy_class-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-groupbuy_soon-area_id-(\d+)-groupbuy_class-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-groupbuy_history-area_id-(\d+)-groupbuy_class-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-groupbuy_detail-group_id-(\d+).html$/',
        '/^pointprod-index.html$/',
        '/^pointprod-plist.html$/',
        '/^pointprod-pinfo-id-(\d+).html$/',
        '/^pointvoucher-index.html$/',
        '/^goods-comments_list-goods_id-(\d+)-type-([0-3])-curpage-(\d+).html$/'
    );
    $reg_match_to = array(
        'item-\\1.html',
        'shop-\\1.html',
        'shop_view-\\1-\\2-\\3-\\4-\\5.html',
        'article-\\1.html',
        'article_cate-\\1.html',
        'document-\\1.html',
        'cate-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'brand-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'brand.html',
        'groupbuy-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'groupbuy_soon-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'groupbuy_history-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'groupbuy_detail-\\1.html',
        'integral.html',
        'integral_list.html',
        'integral_item-\\1.html',
        'voucher.html',
        'comments-\\1-\\2-\\3.html'
    );
    $url_string = preg_replace($reg_match_from, $reg_match_to, $url_string);
    return rtrim($site_url, '/') . '/' . $url_string;
}
/**
 * 取得商品缩略图的完整URL路径，接收商品信息数组，返回所需的商品缩略图的完整URL
 *
 * @param array $goods 商品信息数组
 * @param string $type 缩略图类型  值为60,160,240,310,1280
 * @return string
 */
function thumb($goods = array(), $type = '')
{
	$type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
	if (!in_array($type, $type_array)) {
		$type = '240';
	}
	if (empty($goods)) {
		return UPLOAD_SITE_URL . '/' . defaultGoodsImage($type);
	}
	if (array_key_exists('apic_cover', $goods)) {
		$goods['goods_image'] = $goods['apic_cover'];
	}
	if (empty($goods['goods_image'])) {
		return UPLOAD_SITE_URL . '/' . defaultGoodsImage($type);
	}
	$search_array = explode(',', GOODS_IMAGES_EXT);
	$file = str_ireplace($search_array, '', $goods['goods_image']);
	$fname = basename($file);
	//取店铺ID
	if (preg_match('/^(\d+_)/', $fname)) {
		$store_id = substr($fname, 0, strpos($fname, '_'));
	} else {
		$store_id = $goods['store_id'];
	}
	$file = $type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file);
	if (!file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . '/' . $file)) {
		return UPLOAD_SITE_URL . '/' . defaultGoodsImage($type);
	}
	$thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
	return $thumb_host . '/' . $store_id . '/' . $file;

}
/**
 * 取得商品缩略图的完整URL路径，接收图片名称与店铺ID
 *
 * @param string $file 图片名称
 * @param string $type 缩略图尺寸类型，值为60,160,240,310,1280
 * @param mixed $store_id 店铺ID 如果传入，则返回图片完整URL,如果为假，返回系统默认图
 * @return string
 */
function cthumb($file, $type = '', $store_id = false)
{
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($file)) {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage($type);
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array, '', $file);
    $fname = basename($file);
    // 取店铺ID
    if ($store_id === false || !is_numeric($store_id)) {
        $store_id = substr($fname, 0, strpos($fname, '_'));
    }
    // 本地存储时，增加判断文件是否存在，用默认图代替
    if (!file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file)))) {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
    return $thumb_host . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
}
/**
 * 取得商品默认大小图片
 */
function defaultGoodsImage($key){
    $file = str_ireplace('.', '_' . $key . '.','default_goods_image.gif');
    return ATTACH_COMMON . DS . $file;
}
/**
 * 价格格式化
 */
function ncPriceFormat($price)
{
    $price_format = number_format($price, 2, '.', '');
    return $price_format;
}
/**
 * 返回以原数组某个值为下标的新数据
 *
 * @param array $array
 * @param string $key
 * @param int $type 1一维数组2二维数组
 * @return array
 */
function array_under_reset($array, $key, $type = 1)
{
	if (is_array($array)) {
		$tmp = array();
		foreach ($array as $v) {
			if ($type === 1) {
				$tmp[$v[$key]] = $v;
			} elseif ($type === 2) {
				$tmp[$v[$key]][] = $v;
			}
		}
		return $tmp;
	} else {
		return $array;
	}
}
function P($str)
{
	$str = func_get_args($str);
	if(is_scalar($str)){
		foreach ($str as $v) {
			echo '<pre style="border:1px dashed silver;padding:1em;">'. print_r($v, 1) . '</pre>';
		}
	} else if(!empty($str)){
		echo '<pre style="border:1px dashed silver;padding:1em;">'. print_r($str, 1) . '</pre>';
	} else{
		var_dump($str);
	}
}
function getResizedPictureName($picture,$size){
	if(empty($picture) || empty($size))
		return "";
		$arr = explode(".", $picture);
		$new_name = $arr[0]."_".$size.".".$arr[count($arr)-1];
		return $new_name;
}

function getStoreResizedPictureName($picture,$size){
	if(empty($picture) || empty($size))
		return "";
		$arr = explode(".", $picture);
		$arr2 = explode("_", $arr[0]);
		$store_id = $arr2[0];
		$new_name = "$store_id/".$arr[0]."_".$size.".".$arr[count($arr)-1];
		return $new_name;
}

