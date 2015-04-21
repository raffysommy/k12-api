<?php

namespace Application\Mapper;

use Application\Entity\Score;
use Sorus\StdMapper;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;

class ScoreMapper extends StdMapper
{
	protected $tableGateway;
	protected $tableName = 'scores';
	protected $accessTokenTableName = 'oauth_access_tokens';
	
	public function fetchTotalsByAccessToken($accessToken)
	{
		$this->tableGateway = new TableGateway($this->tableName, $this->getAdapter());
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('totals' => new \Zend\Db\Sql\Expression('COUNT(*)'), 'result'), false)
			   ->join($this->accessTokenTableName, 'user=user_id')
			   ->where(array('access_token' => $accessToken))
			   ->group('result');
		return $this->tableGateway->selectWith($select);
	}
	
	public function getTableGateway()
	{
		if ($this->tableGateway instanceof TableGateway)
			return $this->tableGateway;
		else {
			$this->tableGateway = new TableGateway(
					$this->tableName,
					$this->getAdapter(),
					null,
					new HydratingResultSet(
							new ClassMethods(),
							new Score()
					)
			);
			return $this->tableGateway;
		}
	}
	
	public function setTableGateway(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
		return $this;
	}
}