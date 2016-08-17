<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 *
	 * 缓存管理
	 */
	class Cache {
		//缓存管理 
		function index() {
			$cache = new FileCache();

			//数据库缓存
			$info_list['category']['cache_name'] = '商品分类信息';
			$info_list['category']['cache_text'] = '网站【商品分类】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['category']['cache_size'] = @round(filesize($cache->get_cache_file_name('category'))/1024, 1);

			//数据库缓存
			$info_list['class']['cache_name'] = '文章分类信息';
			$info_list['class']['cache_text'] = '网站【文章分类】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['class']['cache_size'] = @round(filesize($cache->get_cache_file_name('class'))/1024, 1);

			$info_list['page']['cache_name'] = '单页信息';
			$info_list['page']['cache_text'] = '网站【单页列表】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['page']['cache_size'] =  @round(filesize($cache->get_cache_file_name('page'))/1024, 1);

			$info_list['setting']['cache_name'] = '网站信息';
			$info_list['setting']['cache_text'] = '网站【基本信息】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['setting']['cache_size'] =  @round(filesize($cache->get_cache_file_name('setting'))/1024, 1);

			$info_list['payway']['cache_name'] = '支付信息';
			$info_list['payway']['cache_text'] = '网站【支付方式】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['payway']['cache_size'] =  @round(filesize($cache->get_cache_file_name('payway'))/1024, 1);

			$info_list['link']['cache_name'] = '友链信息';
			$info_list['link']['cache_text'] = '网站【友情链接】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['link']['cache_size'] =  @round(filesize($cache->get_cache_file_name('link'))/1024, 1);
			
			$info_list['ad']['cache_name'] = '广告信息';
			$info_list['ad']['cache_text'] = '网站【广告列表】里信息显示错乱或不显示时，可尝试更新此项。';
			$info_list['ad']['cache_size'] = @round(filesize($cache->get_cache_file_name('ad'))/1024, 1);

			//数据缓存
			$info_list['template']['cache_name'] = '模板信息';
			$info_list['template']['cache_text'] = '网站页面显示错乱或不显示时，可尝试更新此项。';
			$info_list['template']['cache_size'] = @round(bro_dirsize("./runtime/comps/")/1024, 1);

			$info_list['table']['cache_name'] = '数据表信息';
			$info_list['table']['cache_text'] = '如果表结构有改变，需要重新获取表结构，可更新此项。';
			$info_list['table']['cache_size'] = @round(bro_dirsize("./runtime/data")/1024, 1);

			$info_list['thumb']['cache_name'] = '缩略图信息';
			$info_list['thumb']['cache_text'] = '缩略图缓存占用过多空间时，可更新此项。';
			$info_list['thumb']['cache_size'] = @round(bro_dirsize("./runtime/thumb")/1024, 1);
		 

			$this->assign("info_list", $info_list);
		
			$this->assign("title", '缓存管理');
			$this->assign("menumark", "cache");
			$this->display();
		}

		//更新缓存
		function update() {
					
		
			switch($_GET['cache']) {
				case 'template':
					bro_dirdel('./runtime/comps/');
					break;
				case 'table':
					bro_dirdel('./runtime/data/');
					break;
				case 'thumb':
					bro_dirdel('./runtime/thumb/');
					break;
				case 'all':
					bro_dirdel('./runtime/comps/');
					bro_dirdel('./runtime/data/');
					bro_dirdel('./runtime/thumb/');
					$cache = new FileCache();
					$cache -> flush();
				default:
					$cache = new FileCache();
					$cache -> delete($_GET['cache']);
			}

			$this->success("缓存更新成功!");
 
		}
	}
