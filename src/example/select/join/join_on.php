<?php

include __DIR__ . '/../../../setup.php';

use Redstraw\Hooch\Query\Repository\Table\Table;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Field;

$query = $driver->select()
    ->cols([
        Field::column("b.*"),
        Field::column("a.first_name"),
        Field::column("a.last_name")
    ])
    ->from(Table::make($driver)->setName("book")->setAlias("b"))
    ->join(Sql::JOIN, Table::make($driver)->setName("author")->setAlias("a"))
    ->onFilter(function(OnFilterInterface $f) {
        $f->on(Field::column('a.id'), $f->operator()->field()->eq(Field::column('b.author_id')));
    })
    ->build();

header('Content-Type: application/json');

echo json_encode([
    "query"         =>  $query->queryString(),
    "parameters"    =>  $query->parameters(),
    "result"        =>  $driver->fetchAll($query)
]);
