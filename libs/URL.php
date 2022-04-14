<?php
class URL {
    public static function createLink($module,$controller,$action,$params = null,$route = null) {
        if($route != null) return ROOT_URL .  $route;
        $strLink = '';
        if(!empty($params)) {
            foreach($params as $key => $value) {
                $strLink .= '&'.$key.'='.$value.'';
            }
        }
        $link = ROOT_URL . 'index.php?module='.$module.'&controller='.$controller.'&action='.$action.'' . $strLink;
        return $link;
    }

    public static function redirect($link) {
        header('location: '.$link.'');
        exit();
    }

    
    private function replaceSpace($value){
		$value = trim($value);
		$value = str_replace(' ', '-', $value);
		$value = str_replace('(', '', $value);
		$value = str_replace(')', '', $value);
		$value = preg_replace('#(-)+#', '-', $value);
		return $value;
	}
	
	private function removeCircumflex($value){
		
		$value		= strtolower($value);

		$characterA	= '#(a|à|ả|ã|á|ạ|ă|ằ|ẳ|ắ|ẵ|ặ|â|ầ|ẩ|ấ|ẫ|ậ)#imsU';
		$replaceA	= 'a';
		$value = preg_replace($characterA, $replaceA, $value);
		
		
		$characterD	= '#(đ|Đ)#imsU';
		$replaceD	= 'd';
		$value = preg_replace($characterD, $replaceD, $value);
		
		$characterE	= '#(è|ẻ|ẽ|é|ẹ|ê|ề|ể|ễ|ế|ệ)#imsU';
		$replaceE	= 'e';
		$value = preg_replace($characterE, $replaceE, $value);
		
		$characterI	= '#(ì|ỉ|ĩ|í|ị)#imsU';
		$replaceI	= 'i';
		$value = preg_replace($characterI, $replaceI, $value);
		
		$charaterO = '#(ò|ỏ|õ|ó|ọ|ô|ồ|ổ|ỗ|ố|ộ|ơ|ờ|ở|ỡ|ớ|ợ)#imsU';
		$replaceCharaterO = 'o';
		$value = preg_replace($charaterO,$replaceCharaterO,$value);
		
		$charaterU = '#(ù|ủ|ũ|ú|ụ|ư|ừ|ử|ữ|ứ|ự)#imsU';
		$replaceCharaterU = 'u';
		$value = preg_replace($charaterU,$replaceCharaterU,$value);
		
		$charaterY = '#(ỳ|ỷ|ỹ|ý)#imsU';
		$replaceCharaterY = 'y';
		$value = preg_replace($charaterY,$replaceCharaterY,$value);
		
		$charaterSpecial = '#(,|$)#imsU';
		$replaceSpecial = '';
		$value = preg_replace($charaterSpecial,$replaceSpecial,$value);
		
		
		return $value;
		
	}
	
	
	public static function filterURL($value){
		$value = URL::replaceSpace($value);
		$value = URL::removeCircumflex($value);
		
		return $value;
	}
}