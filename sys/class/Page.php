<?php
class Page{
    var $pageSize;
    var $totalRecord;
    var $totalPage;
    var $page;
    var $offset;
    var $end;
    var $getUrl;
    var $lenList;
    var $wrapList	= 'li';
    var $wrapAll	= 'ul';

    var $textPageFirst	= "&lt;&lt;";
    var $textPageLast	= "&gt;&gt;";
	/*
    var $textPageFirst	= "<span style='font-family:Webdings;'>9</span>";
    var $textPageLast	= "<span style='font-family:Webdings;'>:</span>";
    var $textPagePre	= "<span style='font-family:Webdings;'>3</span>";
    var $textPageNex	= "<span style='font-family:Webdings;'>4</span>";
	*/

    var $textPagePre	= '<img src="/images/search/blog/le_arrow_03.gif">';
    var $textPageNex	= '<img src="/images/search/blog/ri_arrow_02.gif">';

    var $titlePageFirst = "首頁";
    var $titlePageLast	= "最後一頁";
    var $titlePagePre	= "上一頁";
    var $titlePageNex	= "下一頁";
   function page($page,$pageSize,$totalRecord,$getUrl,$lenList=4){
        $this->pageSize = $pageSize;
        $this->totalRecord  = $totalRecord;
        $this->getUrl  = $getUrl;
        $this->totalPage    = ceil($this->totalRecord/$this->pageSize);
        if($page < 1){
            $this->page = 1;
        }
        elseif($page > $this->totalPage){
            $this->page = $this->totalPage;
        }
        else{
            $this->page = $page;
        }
		$this->lenList = $lenList;

		$this->offset = $this->getOffset($this->page,$this->pageSize);
		
 		$end = $this->offset + $this->pageSize;
		$end = $end > $totalRecord ? $totalRecord:$end;
		$this->end = $end;
    }

	function getHtmlPage($page,$text,$title='',$class=''){
		
		if($this->wrapList){
			$classLink		= '';
			$classElement	= "class='".$class."'";
		}
		else{
			$classLink		= "class='".$class."'";
			$classElement	= '';
		}
		
		if(!$page)
			$html = $text;
		else
			$html = "<a href='" . $this->getUrl . "=" . $page . "&total=". $this->totalRecord ."' title='".$title."' ".$classLink.">".$text."</a>";

		if($this->wrapList) $html = "<".$this->wrapList." ".$classElement.">".$html."</".$this->wrapList.">";
		//debug($html);
		
		return $html;
	}
	function getHtml($showFirstLast=false,$glue="\n"){
		
		$rt = false;

        if($showFirstLast && $this->page > $this->lenList + 1){
			$listHead[] = $this->getHtmlPage(1,$this->textPageFirst,$this->titlePageFirst,'special');
        }
        if($this->page > 1){
            $pagePre = $this->page - 1;
			$listHead[] = $this->getHtmlPage($pagePre,$this->textPagePre,$this->titlePagePre,'special');
        }

        if($this->page <= $this->lenList + 1){
			$pageStart = 1;
			$pageEnd = 2 * $this->lenList + 1;
			if($pageEnd > $this->totalPage){
				$pageEnd = $this->totalPage;
			}
        }
        else{
			$pageEnd = $this->page + $this->lenList;
			$pageStart = $this->page - $this->lenList;

			if($pageEnd > $this->totalPage){
				$pageEnd = $this->totalPage;
				$pageStart = $this->totalPage - 2 * $this->lenList - 1;
			}

			if($pageStart < 1){
				$pageStart = 1;
			}
        }
		
		for($i = $pageStart ; $i <= $pageEnd ; $i++){
			if($i == $this->page){
				$list[] = $this->getHtmlPage(false,$i,'','current');
			}
			else{
 				$list[] = $this->getHtmlPage($i,$i,'','main');
			}
		}

        if($this->page != $this->totalPage && $this->totalPage != 0){
            $page_nex = $this->page + 1;
 			$listEnd[] = $this->getHtmlPage($page_nex,$this->textPageNex,$this->titlePageNex,'special');
        }
		
        if($showFirstLast && $this->page < $this->totalPage-$this->lenList){
 			$listEnd[] = $this->getHtmlPage($this->totalPage,$this->textPageLast,$this->titlePageLast,'special');
        }

		if(is_array($list)){
			if(is_array($listHead)) $rt .= join('',$listHead);
			$rt .= join($glue,$list);
			if(is_array($listEnd )) $rt .= join('',$listEnd);
			
			if($this->wrapAll){
				$rt = "<".$this->wrapAll.">".$rt."</".$this->wrapAll.">";
			}
		}

		return $rt;
    }

	function pages($showFirstLast=false){
		
		$rt = false;

        if($showFirstLast && $this->page > $this->lenList + 1){
			$rt[] = array(
				'page'	=>	1,
				'type'	=>	'first',
			);
        }

        if($this->page > 1){
			$rt[] = array(
				'page'	=>	$this->page - 1,
				'type'	=>	'pre',
			);
        }

        if($this->page <= $this->lenList + 1){
			$pageStart = 1;
			$pageEnd = 2 * $this->lenList + 1;
			if($pageEnd > $this->totalPage){
				$pageEnd = $this->totalPage;
			}
        }
        else{
			$pageEnd = $this->page + $this->lenList;
			$pageStart = $this->page - $this->lenList;

			if($pageEnd > $this->totalPage){
				$pageEnd = $this->totalPage;
				$pageStart = $this->totalPage - 2 * $this->lenList - 1;
			}

			if($pageStart < 1){
				$pageStart = 1;
			}
        }
		
		for($i = $pageStart ; $i <= $pageEnd ; $i++){
			
			$type = $i == $this->page ? 'current':'main';
			if($i == $pageEnd) $type .= ' last';

			$rt[] = array(
				'page'	=>	$i,
				'type'	=>	$type,
			);
		}

        if($this->page != $this->totalPage && $this->totalPage != 0){
			$rt[] = array(
				'page'	=>	$this->page + 1,
				'type'	=>	'nex',
			);
        }
		
        if($showFirstLast && $this->page < $this->totalPage-$this->lenList){
			$rt[] = array(
				'page'	=>	$this->totalPage,
				'type'	=>	'last',
			);
        }
		return $rt;
    }

	function getTip(){
		return   $this->totalRecord . "條  " . $this->page . "/" . $this->totalPage . "頁 ";
	}
	
	function getOffset($page,$size){
		$offset = $size * ($page - 1);
		$offset = $offset < 0 ? 0:$offset;
		return $offset;
	}
}

?>