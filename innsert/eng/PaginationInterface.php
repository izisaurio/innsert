<?php

namespace innsert\eng;
use innsert\db\DBMapper;

/**
 * Innsert PHP MVC Framework
 *
 * Interface for Pagination classes
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
interface PaginationInterface
{
    /**
    * Executes the mapper search
    *
    * @access  public
    * @param   DBMapper    $mapper     DBMapper where the search will be made
    */
    public function paginate(DBMapper $mapper);

    /**
     * Returns the result of a serach
     * 
     * @access	public
     * @return	Result
     */
    public function result();
}