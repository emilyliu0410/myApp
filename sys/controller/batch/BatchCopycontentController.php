<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class BatchCopycontentController extends UController
{
	public $useMasterDb = true;					// connect master database for insert/update
	
	function actionIndex()
	{
		
		if($_GET['exe']=='start'){
			$this->batch();
		}
		
		exit();		
	}
	function batch()
	{
			// get page rating for the following page types:
			// 1. Event, 2.Topic, 3.Tour, 4.Spots
			$sql = "SELECT	pagetype_id, pagetype_name, table_name, pkey_name, alias
					FROM	tbl_pagetypes
					WHERE 	pagetype_id = 1
					ORDER BY pagetype_id ASC";
			$rs = uDb()->findList($sql);
			foreach($rs as $key=>$value){
				$pagetype_id 	= $value->pagetype_id;
				$pagetype_name 	= $value->pagetype_name;
				$table_name 	= $value->table_name;
				$pkey_name 		= $value->pkey_name;
				$alias 			= $value->alias;
				
				if($pagetype_id==1){	//1. Event
					$sql_select = ", $table_name.title, $table_name.subtitle AS title2 ";
				}else if($pagetype_id==2){	//2.Topic
					$sql_select = ", $table_name.title1 AS title, $table_name.title2, $table_name.content ";
				}else if($pagetype_id==3){	//3.Tour
					$sql_select = ", $table_name.title, $table_name.content ";
				}else if($pagetype_id==4){	//4.Spots
					$sql_select = ", $table_name.spot_name AS title, $table_name.tagline AS title2, $table_name.content ";
				}
				
				// select all content of each pagetype
				$sql_t = "SELECT	$table_name.$pkey_name, $table_name.author, $table_name.tags, 
						$table_name.published, $table_name.publish_date, $table_name.create_date,
						$table_name.update_date, ".$table_name."_photo.photo_name, 
						$table_name.hits_all, $table_name.hits_past_day, $table_name.hits_past_week, $table_name.hits_past_month
						$sql_select
						FROM	$table_name
						LEFT JOIN ".$table_name."_photo ON (".$table_name."_photo.$pkey_name = $table_name.$pkey_name AND ".$table_name."_photo.is_cover = 1)
						GROUP BY $table_name.$pkey_name
						ORDER BY $pkey_name ASC
						LIMIT 0, 5000";
				$rs_t = uDb()->findList($sql_t);
				foreach($rs_t as $key_t=>$value_t){
//if($key_t==0){				
//if($pagetype_id==4){
					$content_cat = "";
					$content_district = "";
					
					$page_id = $value_t->$pkey_name;
					$title = $value_t->title;
					$author = $value_t->author;
					$cover_photo = $value_t->photo_name;
					$published = $value_t->published;
					$publish_date = $value_t->publish_date;
					$create_date = $value_t->create_date;
					$update_date = $value_t->update_date;
					$tags = $value_t->tags;
					$hits_all = $value_t->hits_all;
					$hits_past_day = $value_t->hits_past_day;
					$hits_past_week = $value_t->hits_past_week;
					$hits_past_month = $value_t->hits_past_month;
					
					if($pagetype_id==3){
						$title2 = "";
					}else{
						$title2 = $value_t->title2;
					}

					if($pagetype_id==1){
						$all_content = "";
						$sql_content = "SELECT content FROM ".$table_name."_content WHERE $pkey_name = $page_id ORDER BY ordering";
						$rs_content = uDb()->findList($sql_content);
						foreach($rs_content as $key_content=>$value_content){
							$all_content .= trim_html_text($value_content->content)."\n";
						}
						$content = $all_content;
					}else{
						$content = trim_html_text($value_t->content);
					}

					//category
					$sql_category = "SELECT tbl_category.cat_id, tbl_category.category_name
									FROM ".$table_name."_category 
									JOIN tbl_category ON (tbl_category.cat_id = ".$table_name."_category.category_id)
									WHERE ".$table_name."_category.$pkey_name = $page_id 
									ORDER BY tbl_category.ordering";
					$rs_category = uDb()->findList($sql_category);
					$catids_arr = array();
					$cats_arr = array();
					foreach($rs_category as $key_category=>$value_category){
						$catids_arr[] = $value_category->cat_id;
						$cats_arr[] = $value_category->category_name;
					}
					$content_cat = implode(",",$cats_arr);
//debug("$pkey_name | $page_id",false);					
//debug($catids_arr,false);					
					
					//districts
					$sql_district = "SELECT DISTINCT tbl_district.district_name
									FROM ".$table_name."_district
									JOIN tbl_district ON tbl_district.district_id = ".$table_name."_district.district_id
									WHERE ".$table_name."_district.$pkey_name = $page_id 
									ORDER BY tbl_district.ordering";
					$rs_district = uDb()->findList($sql_district);
					$districts_arr = array();
					foreach($rs_district as $key_district=>$value_district){
						$districts_arr[] = $value_district->district_name;
					}
					//areas
					$sql_area = "SELECT DISTINCT tbl_district_area.area_name
								FROM ".$table_name."_district
								JOIN tbl_district_area ON tbl_district_area.area_id = ".$table_name."_district.area_id
								WHERE ".$table_name."_district.$pkey_name = $page_id 
								ORDER BY tbl_district_area.ordering";
					$rs_area = uDb()->findList($sql_area);
					foreach($rs_area as $key_area=>$value_area){
						$districts_arr[] = $value_area->area_name;
					}
					$content_district = implode(",",$districts_arr);
					
					$content_id = 0;
					$query = "SELECT content_id FROM tbl_content WHERE pagetype_id = ".$pagetype_id." AND page_id = ".$page_id;
					$rs_search = uDb()->findList($query);
					$content_found = 0;

					foreach($rs_search as $key_search=>$value_search){
						if($key_search==0){
							$content_id = $value_search->content_id;
							$sql_upd = "UPDATE tbl_content 
										SET title='".addslashes($title)."',
											title2='".addslashes($title2)."',
											author='".addslashes($author)."',
											content='".addslashes($content)."',
											cover_photo='".addslashes($cover_photo)."',
											tags='".addslashes($tags)."',
											category='".addslashes($content_cat)."',
											district='".addslashes($content_district)."',
											published='".$published."',
											publish_date='".$publish_date."',
											create_date='".$create_date."',
											update_date='".$update_date."',
											hits_all='".$hits_all."',
											hits_past_day='".$hits_past_day."',
											hits_past_week='".$hits_past_week."',
											hits_past_month='".$hits_past_month."'
										WHERE pagetype_id = $pagetype_id AND page_id = $page_id";
							uDb()->query($sql_upd);
							$content_found++;
							
debug($alias."|$page_id UPDATE",false);							
						}
					}
//					if($content_found==0 && $pagetype_id==1){
					if($content_found==0){
						$sql_insert_content = "INSERT INTO tbl_content 
												(pagetype_id,page_id,title,title2,author,content,cover_photo,tags,category,district,published,publish_date,create_date,update_date,hits_all,hits_past_day,hits_past_week,hits_past_month) 
												 VALUES 
												($pagetype_id,$page_id,'".addslashes($title)."','".addslashes($title2)."','".addslashes($author)."','".addslashes($content)."','".addslashes($cover_photo)."','".addslashes($tags)."','".addslashes($content_cat)."','".addslashes($content_district)."','".$published."','".$publish_date."','".$create_date."','".$update_date."','".$hits_all."','".$hits_past_day."','".$hits_past_week."','".$hits_past_month."')";
						uDb()->query($sql_insert_content);
						$content_id = mysql_insert_id();
						
debug($alias."|$page_id INSERT",false);						
					}

//					if($content_id>0 && $pagetype_id==1){
					if($content_id>0){
						//category
//						$sql_del_cat = "DELETE FROM tbl_content_category WHERE ".(count($catids_arr) ? "category_id not in (".join(',',$catids_arr).") AND " : '')."content_id=".$content_id;
						$sql_del_cat = "DELETE FROM tbl_content_category WHERE content_id=".$content_id;
						uDb()->query($sql_del_cat);
						foreach($catids_arr as $k => $v)
						{
							$sql_insert_cat = "INSERT INTO tbl_content_category (category_id,content_id,ordering) VALUES ('".$v."','".$content_id."','".($k+1)."')";
							uDb()->query($sql_insert_cat);
//debug($sql_insert_cat,false);
						}
						//debug($catids_arr);
						
						
						
						//districts
						$sql_del_dist = "DELETE FROM tbl_content_district WHERE content_id=".$content_id;
						uDb()->query($sql_del_dist);
						
						$sql_dist = "SELECT ".$table_name."_district.district_id, ".$table_name."_district.area_id, ".$table_name."_district.ordering
									FROM ".$table_name."_district
									JOIN tbl_district ON tbl_district.district_id = ".$table_name."_district.district_id
									WHERE ".$table_name."_district.$pkey_name = $page_id 
									ORDER BY ".$table_name."_district.ordering";
						$rs_dist = uDb()->findList($sql_dist);
						foreach($rs_dist as $key_dist=>$value_dist){
//							$dists_arr[] = $value_dist->district_id;
							$sql_insert_dist = "INSERT INTO tbl_content_district (content_id, district_id, area_id, ordering) VALUES ('".$content_id."','".$value_dist->district_id."','".$value_dist->area_id."','".$value_dist->ordering."')";
							uDb()->query($sql_insert_dist);
//debug($sql_insert_dist,false);
						}
						
						
						
						//tags
						$sql_del_tag = "DELETE FROM tbl_content_tags WHERE content_id=".$content_id;
						uDb()->query($sql_del_tag);
						
						$sql_tag = "SELECT ".$table_name."_tags.tag_id, ".$table_name."_tags.ordering
									FROM ".$table_name."_tags
									JOIN tbl_tags ON tbl_tags.tag_id = ".$table_name."_tags.tag_id
									WHERE ".$table_name."_tags.$pkey_name = $page_id 
									ORDER BY ".$table_name."_tags.ordering";
						$rs_tag = uDb()->findList($sql_tag);
						foreach($rs_tag as $key_tag=>$value_tag){
							$sql_insert_tag = "INSERT INTO tbl_content_tags (content_id, tag_id, ordering) VALUES ('".$content_id."','".$value_tag->tag_id."','".$value_tag->ordering."')";
							uDb()->query($sql_insert_tag);
//debug($sql_insert_tag,false);
						}
					}
//}					
				}
			}
	}
}