<?php

class Database
{
    private $link;
    private $databaseName;
    function __construct($databaseName)
    {
        $this->databaseName = $databaseName;
        $this->link = mysqli_connect('localhost', 'root', 'Stiofan10', $databaseName);
    }
    function getTablesFromDatabase()
    {
        $this->link->select_db('INFORMATION_SCHEMA');
        $tableTitles = $this->getTableRows('INNODB_SYS_TABLES', ['NAME'], ['NAME' => $this->databaseName . '/']);
        $formattedTableTitles = [];
        foreach($tableTitles as $aTableTitle)
            foreach($aTableTitle as $currentTableTitle)
                array_push($formattedTableTitles, substr($currentTableTitle, strpos($currentTableTitle, '/') + 1));
        $this->link->select_db($this->databaseName);
        return $formattedTableTitles;
    }
    function getTableRows($tableName, $desiredColumnTitles = [], $desiredParameterValues = [], $columnForSorting = '')
    {
        $commandText = 'select ';
        if(count($desiredColumnTitles) == 0)
            $commandText .= '*';
        else
        {
            foreach ($desiredColumnTitles as $aDesiredColumnTitle)
                $commandText .= $aDesiredColumnTitle . ', ';
            $commandText = substr($commandText, 0, strlen($commandText) - 2);
        }
        //echo 'DESIRED PARAMETER VALUES: ' . var_dump($desiredParameterValues);
        $commandText .= ' from ' . $tableName . $this->addConstraints($desiredParameterValues);
        if(strlen($columnForSorting) > 0)
            $commandText .= ' order by ' . $columnForSorting . ' ASC';
        /*if(count($desiredParameterValues) > 0)
        {
            $commandText .= ' where ';
            $parameterIdentifiers = array_keys($desiredParameterValues);
            foreach ($parameterIdentifiers as $aParameterIdentifier)
            {
                if(is_numeric($desiredParameterValues[$aParameterIdentifier]))
                    $commandText .= $aParameterIdentifier . ' = ' . $desiredParameterValues[$aParameterIdentifier] . ' and ';
                else if(is_string($desiredParameterValues[$aParameterIdentifier]))
                    $commandText .= $aParameterIdentifier  . ' like \'%' . $desiredParameterValues[$aParameterIdentifier] . '%\'' . ' and ';
            }
            $commandText = substr($commandText, 0, strlen($commandText) - 5);
        }*/
        //echo 'COMMAND TEXT: ' . $commandText . '<br/>';
        $results = $this->link->query($commandText);
        return $results;
    }
    private function addConstraints($constraints)
    {
        $commandText = '';
        if(count($constraints) > 0)
        {
            $commandText .= ' where ';
            $constraintIdentifiers = array_keys($constraints);
            foreach ($constraintIdentifiers as $aConstraintIdentifier)
            {
                if(is_numeric($constraints[$aConstraintIdentifier]) || is_bool($constraints[$aConstraintIdentifier]))
                    $commandText .= $aConstraintIdentifier . ' = ' . $constraints[$aConstraintIdentifier] . ' and ';
                else if(is_string($constraints[$aConstraintIdentifier]))
                    $commandText .= $aConstraintIdentifier  . ' like \'%' . $constraints[$aConstraintIdentifier] . '%\'' . ' and ';
            }
            $commandText = substr($commandText, 0, strlen($commandText) - 5);
        }
        return $commandText;
    }
    function getTableRowTitles($tableTitle)
    {
        $this->link->select_db('INFORMATION_SCHEMA');
        $tableID = $this->getTableRows('INNODB_SYS_TABLES', ['TABLE_ID'], ['NAME' => $this->databaseName . '/' . $tableTitle]);
        $currentTable = 0;
        foreach($tableID as $aTableID)
            foreach($aTableID as $currentTableID)
                $currentTable = $currentTableID;
        $tableRowTitles = $this->getTableRows('INNODB_SYS_COLUMNS', ['NAME'], ['TABLE_ID' => $currentTable]);
        $formattedTableRowTitles = [];
        foreach($tableRowTitles as $aTableRowTitle)
            foreach($aTableRowTitle as $currentRowTitle)
                array_push($formattedTableRowTitles, $currentRowTitle);
        $this->link->select_db($this->databaseName);
        return $formattedTableRowTitles;
    }
    function getMaxValueOfColumn($tableName, $columnTitle)
    {
        $commandText = 'select max(' . $columnTitle . ') from ' . $tableName;
        $results = $this->link->query($commandText);
        $formattedResult = 0;
        foreach($results as $aResult)
            foreach($aResult as $aValue)
                $formattedResult = $aValue;
        return $formattedResult;
    }
    function insertTableRow($tableName, $values = [])
    {
        /*$tableColumnTitles = $this->getTableRowTitles($tableName);
        $commandText = 'insert into ' . $tableName . ' (';
        foreach($tableColumnTitles as $aTableColumnTitle)
            $commandText .= $aTableColumnTitle . ', ';
        $commandText = substr($commandText, 0, strlen($commandText) - 2) . ') values (';*/
        //echo 'TABLE INSERTION VALUES:<br/>' . var_dump($values). '<br/>';
        $commandText = 'insert into ' . $tableName . ' values(';
        foreach($values as $aValue)
        {
            if(is_numeric($aValue))
                $commandText .= $aValue . ', ';
            else if(is_string($aValue))
                $commandText .= '\'' . $aValue . '\', ';
            else if(is_bool($aValue))
            {
                if($aValue == false)
                    $commandText .= 'false, ';
                else if($aValue == true)
                    $commandText .= 'true, ';
                else
                    $commandText .= 'false, ';
            }
        }
        $commandText = substr($commandText, 0, strlen($commandText) - 2) . ');';
        //echo 'TABLE INSERTION TEXT:<br/> ' . $commandText . '<br/>';
        $this->link->query($commandText);
    }
    function updateTableRow($tableName, $updatedValues, $constraints)
    {
        $commandText = 'update ' . $tableName . ' ';
        if(count($updatedValues) > 0)
        {
            $commandText .= 'set ';
            $updatedValuesIdentifiers = array_keys($updatedValues);
            foreach($updatedValuesIdentifiers as $anUpdatedValueIdentifier)
            {
                if(is_numeric($updatedValues[$anUpdatedValueIdentifier]) || is_bool($updatedValues[$anUpdatedValueIdentifier]))
                    $commandText .= $anUpdatedValueIdentifier . ' = ' . $updatedValues[$anUpdatedValueIdentifier] . ', ';
                else if(is_string($updatedValues[$anUpdatedValueIdentifier]))
                    $commandText .= $anUpdatedValueIdentifier . ' = \'' . $updatedValues[$anUpdatedValueIdentifier] . '\', ';
            }
            $commandText = substr($commandText, 0, strlen($commandText) - 2) . $this->addConstraints($constraints);
        }
        $this->link->query($commandText);
    }
    function removeTableRow($tableName, $desiredParameterValues)
    {
        $commandText = 'delete from ' . $tableName . $this->addConstraints($desiredParameterValues);
        $this->link->query($commandText);
    }
}