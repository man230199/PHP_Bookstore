<?php
class FrontendPagination
{

	private $totalItems;					// Tổng số phần tử
	private $totalItemsPerPage		= 1;	// Tổng số phần tử xuất hiện trên một trang
	private $pageRange				= 5;	// Số trang xuất hiện
	private $totalPage;						// Tổng số trang
	private $currentPage			= 1;	// Trang hiện tại

	public function __construct($totalItems, $pagination)
	{
		$this->totalItems			= $totalItems;
		$this->totalItemsPerPage	= $pagination['totalItemsPerPage'];

		if ($pagination['pageRange'] % 2 == 0) $pagination['pageRange'] = $pagination['pageRange'] + 1;

		$this->pageRange			= $pagination['pageRange'];
		$this->currentPage			= $pagination['currentPage'];
		$this->totalPage			= ceil($totalItems / $pagination['totalItemsPerPage']);
	}

	public function showPagination($link = null)
	{
		// Pagination
		$params = array_merge($_POST, $_GET);
		$queries = [];
		$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		parse_str($query, $queries);
		unset($queries['page']);
		if (empty($params)) {
			$link = 'index.php?' . http_build_query($queries);
		} else {
			$link = 'index.php?module=frontend&controller=book&action=list';
		}
		$paginationHTML = '';
		if ($this->totalPage > 1) {
			$start 	= '<li class="page-item disabled"><a class="page-link" href="#">Start</a></li>';
			$prev 	= '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-left"></i></a></li>';
			if ($this->currentPage > 1) {
				$start 	= '<li class="page-item"><a class="page-link" href="' . $link . '&page=1">Start</a></li>';
				$prev 	= '<li class="page-item"><a class="page-link" href="' . $link . '&page=' . ($this->currentPage - 1) . '"><i class="fas fa-angle-double-left"></i></a></li>';
			}
			$next 	= '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a></li>';
			$end 	= '<li class="page-item disabled"><a class="page-link" href="#">End</a></li>';
			if ($this->currentPage < $this->totalPage) {
				$next 	= '<li class="page-item"><a class="page-link" href="' . $link . '&page=' . ($this->currentPage + 1) . '"><i class="fas fa-angle-double-right"></i></a></li>';
				$end 	= '<li class="page-item"><a class="page-link" href="' . $link . '&page=' . $this->totalPage . '">End</a></li>';
			}

			if ($this->pageRange < $this->totalPage) {
				if ($this->currentPage == 1) {
					$startPage 	= 1;
					$endPage 	= $this->pageRange;
				} else if ($this->currentPage == $this->totalPage) {
					$startPage		= $this->totalPage - $this->pageRange + 1;
					$endPage		= $this->totalPage;
				} else {
					$startPage		= $this->currentPage - ($this->pageRange - 1) / 2;
					$endPage		= $this->currentPage + ($this->pageRange - 1) / 2;

					if ($startPage < 1) {
						$endPage	= $endPage + 1;
						$startPage = 1;
					}

					if ($endPage > $this->totalPage) {
						$endPage	= $this->totalPage;
						$startPage 	= $endPage - $this->pageRange + 1;
					}
				}
			} else {
				$startPage		= 1;
				$endPage		= $this->totalPage;
			}

			$listPages = '';
			for ($i = $startPage; $i <= $endPage; $i++) {
				if ($i == $this->currentPage) {
					$listPages .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
				} else {
					$listPages .= '<li class="page-item"><a class="page-link" href="' . $link . '&page=' . $i . '">' . $i . '</a></li>';
				}
			}


			$paginationHTML = '<div class="card-footer clearfix">
			<ul class="pagination m-0 float-right">' . $start . $prev . $listPages . $next . $end . '</ul></div>';
		}
		return $paginationHTML;
	}
}
