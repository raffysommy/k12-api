<?php

namespace Application\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Mapper\ScoreMapper;

class ScoreController extends AbstractActionController
{
    public function totalAction()
    {
    	$accessToken = $this->params()->fromPost('access_token');
        $mapper = new ScoreMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $totals = $mapper->fetchTotalsByAccessToken($accessToken);
        $totalsArray = array();
        while ($result = $totals->current()) {
        	switch ($result->result) {
        		case -1:
        			$totalsArray['helped'] = $result->totals;
        			break;
        		case 0:
        			$totalsArray['wrong'] = $result->totals;
        			break;
        		case 1:
        			$totalsArray['correct'] = $result->totals;
        			$totalsArray['answered'] = $result->totals;
        			break;
        	}        		
        	$totals->next();
        }
        return new JsonModel($totalsArray);
    }
}

?>