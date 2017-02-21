<?php

class MetroComponent
{
    private $componentContent;
    function __construct()
    {
        $this->componentContent = new DOMDocument('1.0', 'UTF-8');
    }
    protected function addElement($title, $value, $elementLocation = [], $elementAttributes = [])
    {
        $componentNode = $this->componentContent->createElement($title, $value);
        $elementAttributesIdentifiers = array_keys($elementAttributes);
        foreach($elementAttributesIdentifiers as $anElementAttributeIdentifier)
            if(strlen($elementAttributes[$anElementAttributeIdentifier]) > 0)
                $componentNode->setAttribute($anElementAttributeIdentifier, $elementAttributes[$anElementAttributeIdentifier]);
        /*if(count($elementLocation) > 0)
            $this->traverseDocument($elementLocation)->appendChild($componentNode);
        else
            $this->componentContent->appendChild($componentNode);*/
        $this->traverseDocument($elementLocation)->appendChild($componentNode);
    }
    protected function addControl(MetroComponent $aControl, $elementLocation = [])
    {
        $nodesInControl = $aControl->getDocument()->childNodes;
        for($counter = 0; $counter < $nodesInControl->length; $counter++)
        {
            $currentNodeInControl = $nodesInControl->item($counter);
            $currentNodeInControl = $this->componentContent->importNode($currentNodeInControl, true);
            $this->traverseDocument($elementLocation)->appendChild($currentNodeInControl);
        }
        /*echo 'STARTING TO LOCATE NODE';
        $newLocation = $this->traverseDocument($elementLocation);
        $newChild = $aControl->getDocument()->firstChild;
        echo 'LOCATED NODE IN DOCUMENT: ' . var_dump($newLocation);
        echo 'NEW CHILD: ' . var_dump($newChild);*/
        //$newLocation->appendChild($newChild);
        //$this->componentContent->appendChild($newChild);

        /*$componentNode = $this->componentContent->createDocumentFragment();
        $children = $aControl->getDocument()->childNodes;
        for($counter = 0; $counter < $children->length; $counter++)
        {
            $aChild = $children->item($counter)->cloneNode(true);
            $componentNode->appendChild($aChild);
        }
        $this->traverseDocument($elementLocation)->appendChild($componentNode);*/


        //echo 'COMPONENT TEXT: <br/><br/>' . $aControl->getDocument()->textContent . '<br/><br/>';
        //$this->addNodesToDocument($aControl, $elementLocation);

        /*$childNodes = $aControl->getDocument()->childNodes;
        for($counter = 0; $counter < $childNodes->length; $counter++)
        {
            $currentNode = $childNodes->item($counter);
            $aNode = $this->componentContent->createElement($currentNode->nodeName, $currentNode->nodeValue);
            //$aNode->childNodes = $currentNode->childNodes;
            for($index = 0; $index < count($currentNode->attributes->length); $index++)
                $aNode->setAttribute($currentNode->attributes->item($index)->nodeName, $currentNode->attributes->item($index)->nodeValue);
            //$aNode->attributes = $currentNode->attributes;
            $this->traverseDocument($elementLocation)->appendChild($aNode);
        }*/

        //$componentNode = $this->componentContent->createDocumentFragment();
        /*$children = $aControl->getDocument()->childNodes;
        for($counter = 0; $counter < $children->length; $counter++)
            $componentNode->appendChild($children->item($counter));*/
        //$componentNode->appendXML($aControl->getDocument()->saveHTML());
        //$this->traverseDocument($elementLocation)->appendChild($componentNode);
        //$element = $this->componentContent->createElement('div');
        //$this->appendHTML($element, $aControl->getDocument()->saveHTML());
        //$this->traverseDocument($elementLocation)->appendChild($aControl->getDocument()->firstChild);
        //echo 'SUCCESSFULLY ADDED CHILD';
    }
    /*function appendHTML(DOMNode $parent, $source)
    {
        $tmpDoc = new DOMDocument();
        $tmpDoc->loadHTML($source);
        foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node)
        {
            $node = $parent->ownerDocument->importNode($node);
            $parent->appendChild($node);
        }
    }*/
    private function addHTML(DOMNode $parent, $html)
    {
        $currentDocument = new DOMDocument();
        $currentDocument->loadHTML($html);
        foreach($currentDocument->getElementsByTagName('body')->item(0)->childNodes as $node)
        {
            $node = $parent->ownerDocument->importNode($node);
            $parent->appendChild($node);
        }
    }
    private function addNodesToDocument(MetroComponent $aControl, $elementLocation, $childNodes = null)
    {
        if($childNodes == null)
            $childNodes = $aControl->getDocument()->childNodes;
        for($counter = 0; $counter < $childNodes->length; $counter++)
        {
            $currentNode = $childNodes->item($counter);
            echo 'NODE NAME: ' . $currentNode->nodeName . '<br/>';
            echo 'NODE VALUE: ' . $currentNode->nodeValue . '<br/>';
            echo 'NODE TYPE: ' . $currentNode->nodeType . '<br/>';
            $aNode = $this->componentContent->createElement($currentNode->nodeName, $currentNode->nodeValue);
            for ($index = 0; $index < count($currentNode->attributes->length); $index++)
                $aNode->setAttribute($currentNode->attributes->item($index)->nodeName, $currentNode->attributes->item($index)->nodeValue);
            $this->traverseDocument($elementLocation)->appendChild($aNode);
            if ($currentNode->childNodes->length > 0)
            {
                $aValidNode = false;
                for($index = 0; $index < $currentNode->childNodes->length; $index++)
                    if($currentNode->childNodes->item($index)->nodeName != '#text')
                        $aValidNode = true;
                if($aValidNode == true)
                {
                    $updatedElementLocation = $elementLocation;
                    array_push($updatedElementLocation, $counter);
                    echo 'ELEMENT LOCATIONS: <br/><br/>' . var_dump($elementLocation);
                    for ($counter = 0; $counter < $currentNode->childNodes->length; $counter++)
                    {
                        echo 'NODE NAME: ' . $currentNode->childNodes->item($counter)->nodeName . '<br/>';
                        echo 'NODE VALUE: ' . $currentNode->childNodes->item($counter)->nodeValue . '<br/>';
                    }
                    $this->addNodesToDocument($aControl, $updatedElementLocation, $currentNode->childNodes);
                }
            }
        }
    }
    protected function getNumberOfElements($elementLocation = [])
    {
        return $this->traverseDocument($elementLocation)->childNodes->length - 1;
    }
    private function traverseDocument($elementLocation = [])
    {
        $nodesAvailableAtSelectedLevel = $this->componentContent;
        foreach ($elementLocation as $anElementLocation)
            $nodesAvailableAtSelectedLevel = $nodesAvailableAtSelectedLevel->childNodes->item($anElementLocation);
        return $nodesAvailableAtSelectedLevel;
    }
    private function getDocument()
    {
        return $this->componentContent;
    }
    public function HTML()
    {
        return $this->componentContent->saveHTML();
    }
}