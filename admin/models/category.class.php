<?php
	class Category {
		function formselect($name="pid", $value="0") {
			$html = '<select name="'.$name.'" class="inputselect">';
			$html .= '<option value="0">==顶级分类==</option>';
		
			//获取所有分类
			$allcats = $this->select();

			//处理分类
			$cats = CatTree::getList($allcats);

			foreach($cats as $v) {
				$selected = ($v['id']==$value) ? "selected" : "";
				$html .= '<option '.$selected.' value="'.$v['id'].'">'.str_repeat('|&nbsp;&nbsp;&nbsp;',$v['level']).'|-'.$v['catname'].'</option>';
			}

			$html .='<select>';

			return $html;
		}

		function byIdName() {
			//获取所有分类
			$allcats = $this->select();

			$tmp = array();

			foreach($allcats as $v) {
				$tmp[$v['id']] = $v['catname'];	
			}

			return $tmp;

		}
	}
