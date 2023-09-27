<?php

declare(strict_types=1);

namespace Gso\Ws\Web\Helper;

use Psr\Http\Message\ServerRequestInterface;

final class Pagination
{
    /**
     * @param ServerRequestInterface $request
     * @param string $sql
     * @param int $itemsPerPage
     * @return string
     *
     * A CLASSE DEVER SER IMPLEMENTADA NA INTERFACE DO REPOSITORIO DESTE MODO PODERA SER USADO EM QUALQUER
     * CONTROLLER QUE CHAMA O METODO, POIS A CLASSE PRECISA USAR O REQUEST COMO PARAMETRO
     */
    public function paginate(
        ServerRequestInterface $request,
        string $sql,
        int $itemsPerPage
    ): string {
        if (empty($request->getQueryParams()["page"])) {
            return $sql;
        }
        $page = $request->getQueryParams()["page"];
        $start = ($page * $itemsPerPage) - $itemsPerPage;
        $sql .= " LIMIT {$start}, {$itemsPerPage} ";
        return $sql;
    }
}
