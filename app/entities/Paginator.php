<?php


namespace Entities;


use Core\DbConnection;
use Core\Exceptions\QueryException;
use Core\Request;

class Paginator
{
    const DEFAULT_PER_PAGE = 20;
    const DEFAULT_ORDER = 'desc';

    protected $table;
    protected $rules;
    protected $conn;
    protected $request;

    protected $total;
    protected $perPage;
    protected $pages;
    protected $curPage;
    protected $prev;
    protected $next;
    protected $items = [];

    protected $orderBy;
    protected $order;

    private $proceeded;

    /**
     * Paginator constructor.
     * @param string $table
     * @param string $rules
     * @throws \Exception
     */
    function __construct( string $table, string $rules = '')
    {
        $this->table = $table;
        $this->rules = $rules;
        $this->conn = DbConnection::getConnection();
        $this->request = Request::getFields();
    }

    function setOrder( array $availableOrdering ) {
        $orderBy = $this->request[ 'orderBy' ] ?? false;
        $order = $this->request[ 'order' ] ?? false;
        if ( in_array( $orderBy, $availableOrdering ) ) {
            $this->orderBy = $orderBy;
            $this->order = ( in_array( $order, [ 'asc', 'desc' ] ) ) ? $order : self::DEFAULT_ORDER;
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function proceed() {
        $this->countTotal();
        $this->fetchPerPage();
        $this->calculatePages();
        $this->fetchCurrentPage();
        $this->calculatePrevNext();
        $this->fetchItems();

        $this->proceeded = true;
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    function getData(): array {
        if ( !$this->proceeded ) throw new \Exception( 'Firstly proceed data' );

        return [
            'total'         => $this->total,
            'perPage'       => $this->perPage,
            'pages'         => $this->pages,
            'currentPage'   => $this->curPage,
            'next'          => $this->next,
            'prev'          => $this->prev,
            'items'         => $this->items
        ];
    }

    /**
     * @throws \Exception
     */
    private function countTotal() {
        $query = "select count(*) as total from $this->table $this->rules";
        if ( !$resp = $this->conn->query( $query ) ) {
            throw new QueryException( $this->conn->error, $query );
        }
        $this->total = (int)$resp->fetch_object()->total;
    }

    private function fetchPerPage() {
        $this->perPage = (
            !empty( $this->request[ 'perPage' ] ) &&
            !is_int( $this->request[ 'perPage' ] ) &&
            $this->request[ 'perPage' ] >= 1
        ) ? (int)$this->request[ 'perPage' ] : self::DEFAULT_PER_PAGE;
    }

    private function calculatePages() {
        $this->pages = ceil( $this->total / $this->perPage );
    }

    private function fetchCurrentPage() {
        $this->curPage = (
            !empty( $this->request[ 'page' ] ) &&
            !is_int( $this->request[ 'page' ] ) &&
            $this->request[ 'page' ] >= 1 &&
            $this->request[ 'page' ] <= $this->pages
        ) ? (int)$this->request[ 'page' ] : 1;
    }

    private function calculatePrevNext() {
        $this->prev = ( $this->curPage > 1 ) ? $this->curPage - 1 : null;
        $this->next = ( $this->pages > $this->curPage ) ? $this->curPage + 1 : null;
    }

    /**
     * @throws QueryException
     */
    private function fetchItems() {
        $query = "select * from $this->table $this->rules";
        if ( $this->orderBy ) {
            $query .= " order by $this->orderBy $this->order";
        }
        $offset = ( $this->curPage - 1 ) * $this->perPage;
        $query .= " limit $this->perPage offset $offset";
        $result = $this->conn->query( $query );
        if ( !$result ) {
            throw new QueryException( $this->conn->error, $query );
        }
        while ( $row = $result->fetch_assoc() ) {
            array_push( $this->items, $row );
        }
    }
}
