<?php

namespace cotcotTest\component\dataInput;

class PgsqlDatabaseTest extends \PHPUnit_Framework_TestCase {

    /** @var \cotcot\component\database\pgsql\Database */
    private $database;

    public function setUp() {
        //sudo -u postgres psql postgres
        //CREATE USER cotcot_unit_test WITH PASSWORD 'cotcot';
        //CREATE DATABASE cotcot_unit_test OWNER cotcot_unit_test ENCODING 'utf8';
        $params = new \cotcot\component\database\ConnectionParams();
        $params->encoding = 'utf-8';
        $params->host = null;
        $params->user = 'cotcot_unit_test';
        $params->password = 'cotcot';
        $params->databaseName = 'cotcot_unit_test';
        $this->database = new \cotcot\component\database\pgsql\Database();
        $this->database->params = $params;
        $this->database->connect();
        try {
            $this->database->query('DROP TABLE item')->close();
        } catch (\Exception $ex) {
            //Nothing to do
        }
        $this->database->query('CREATE TABLE item (id BIGSERIAL PRIMARY KEY,title TEXT,value INTEGER)')->close();
    }

    public function tearDown() {
        if ($this->database->isConnected()) {
            $this->database->disconnect();
        }
    }

    public function test_insertQuery() {
        $query = new \cotcot\component\database\InsertQuery();
        $query->database = $this->database;
        $query->into('item')->values(array('title' => 'coucou', 'value' => 1));

        $this->assertEquals('INSERT INTO item (title,value) VALUES (\'coucou\',\'1\')', $query->toSql());

        $res = $query->execute();
        $this->assertTrue($res instanceof \cotcot\component\database\pgsql\ResultSet);
        $this->assertEquals(1, $res->getAffectedRows());
        $res->close();

        $query = $this->database->insert('test');
        $this->assertTrue($query instanceof \cotcot\component\database\InsertQuery);
        $this->assertEquals('INSERT INTO test', $query->toSql());
    }

    public function test_bindValues() {
        $query = new \cotcot\component\database\RawQuery();
        $query->database = $this->database;
        $query->sql(':aA_1 :b');
        $this->assertEquals("'coucou' :b", $query->bindValues(array(':aA_1' => 'coucou'))->toSql());

        $query->sql(':a');
        $this->assertEquals("'coucou'", $query->bindValues(array(':a' => 'coucou'))->toSql());
        $this->assertEquals("'10'", $query->bindValues(array(':a' => 10))->toSql());
        $this->assertEquals("'10'", $query->bindValues(array(':a' => '10'))->toSql());
        $this->assertEquals("NULL", $query->bindValues(array(':a' => null))->toSql());
        $this->assertEquals("'a','10',NULL", $query->bindValues(array(':a' => array('a', '10', null)))->toSql());
    }

    public function test_selectQuery() {
        $query = new \cotcot\component\database\SelectQuery();
        $query->database = $this->database;
        $query->bindValues(array(':t' => 'coucou', ':v1' => 1, ':v2' => 2));
        $query->select('a,b');
        $query->from('item');
        $this->assertEquals("SELECT a,b\nFROM item", $query->toSql());
        $query->select(array('a', 'b'));
        $this->assertEquals("SELECT a,b\nFROM item", $query->toSql());
        $query->select();
        $this->assertEquals("SELECT *\nFROM item", $query->toSql());
        $query->where('title=:t');
        $this->assertEquals("SELECT *\nFROM item\nWHERE title='coucou'", $query->toSql());
        $query->andWhere('value=:v1');
        $this->assertEquals("SELECT *\nFROM item\nWHERE title='coucou' AND value='1'", $query->toSql());
        $query->orWhere('value=:v2');
        $this->assertEquals("SELECT *\nFROM item\nWHERE title='coucou' AND value='1' OR value='2'", $query->toSql());
        $query->having(array('value>2'));
        $this->assertEquals("SELECT *\nFROM item\nHAVING value>2\nWHERE title='coucou' AND value='1' OR value='2'", $query->toSql());
        $query->groupBy(array('value'));
        $this->assertEquals("SELECT *\nFROM item\nHAVING value>2\nWHERE title='coucou' AND value='1' OR value='2'\nGROUP BY value", $query->toSql());
        $query->orderBy(array('value'));
        $this->assertEquals("SELECT *\nFROM item\nHAVING value>2\nWHERE title='coucou' AND value='1' OR value='2'\nGROUP BY value\nORDER BY value", $query->toSql());
        $query->limit(1, 2);
        $this->assertEquals("SELECT *\nFROM item\nHAVING value>2\nWHERE title='coucou' AND value='1' OR value='2'\nGROUP BY value\nORDER BY value\nLIMIT 2 OFFSET 1", $query->toSql());
        $query->join('other', 'other.id_item=item.id');
        $this->assertEquals("SELECT *\nFROM item\nINNER JOIN other ON other.id_item=item.id\nHAVING value>2\nWHERE title='coucou' AND value='1' OR value='2'\nGROUP BY value\nORDER BY value\nLIMIT 2 OFFSET 1", $query->toSql());

        $query = new \cotcot\component\database\SelectQuery();
        $query->database = $this->database;
        $query->select('*')
                ->from('item')
                ->where('a=1')
                ->andWhere('b=2')
                ->andWhere('(')
                ->andWhere('c=3')
                ->orWhere('d=4')
                ->where(')')
                ->andWhere('e=5');
        $this->assertEquals("SELECT *\nFROM item\nWHERE a=1 AND b=2 AND ( c=3 OR d=4 ) AND e=5", $query->toSql());

        $query = new \cotcot\component\database\SelectQuery();
        $query->database = $this->database;
        $query->select('*')
                ->from('item')
                ->where('(')
                ->andWhere('c=3')
                ->orWhere('d=4')
                ->where(')')
                ->andWhere('e=5');
        $this->assertEquals("SELECT *\nFROM item\nWHERE ( c=3 OR d=4 ) AND e=5", $query->toSql());
        
        $query = new \cotcot\component\database\InsertQuery();
        $query->database = $this->database;
        $query->into('item')->values(array('title' => 'coucou2', 'value' => 10))->execute()->close();
        $query->into('item')->values(array('title' => 'coucou2', 'value' => 20))->execute()->close();
        $query->into('item')->values(array('title' => 'coucou2', 'value' => 30))->execute()->close();
        $query = new \cotcot\component\database\SelectQuery();
        $query->database = $this->database;
        $query->select(array('title', 'value'))->from('item')->andWhere('title=\'coucou2\'');
        $res = $query->execute();
        $this->assertEquals(3, $query->count());
        $n = 0;
        $res = $query->execute();
        foreach ($res as $itemRes) {
            $this->assertEquals('coucou2', $itemRes['title']);
            $n++;
        }
        $this->assertEquals(3, $n);
        $res->close();

        $res = $query->execute();
        $res = $res->fetchAll();
        $this->assertEquals(3, count($res));
        foreach ($res as $itemRes) {
            $this->assertEquals('coucou2', $itemRes['title']);
        }

        $res = $query->execute()->fetchRow();
        $this->assertEquals('coucou2', $res['title']);

        $res = $query->execute()->fetchScalar();
        $this->assertEquals('coucou2', $res);

        $res = $query->execute()->fetchCol();
        $this->assertEquals(3, count($res));
        $this->assertEquals('coucou2', $res[0]);
        $this->assertEquals('coucou2', $res[1]);

        $res = $query->execute();
        $item = $res->fetch();
        $res->close();
        $this->assertEquals('coucou2', $item['title']);

        $query = $this->database->select(array('*'))->from('test');
        $this->assertTrue($query instanceof \cotcot\component\database\SelectQuery);
        $this->assertEquals("SELECT *\nFROM test", $query->toSql());

        $query = new \cotcot\component\database\SelectQuery();
        $query->database = $this->database;
        $query->from('item');
        $query->whereId(10);
        $this->assertEquals("SELECT *\nFROM item\nWHERE id='10'", $query->toSql());
    }

    public function test_deleteQuery() {
        $query = new \cotcot\component\database\InsertQuery();
        $query->database = $this->database;
        $query->into('item')->values(array('title' => 'coucou3', 'value' => 1))->execute()->close();

        $query = new \cotcot\component\database\DeleteQuery();
        $query->database = $this->database;
        $query->from('item')->andWhere('title=:t')->bindValue(':t', 'coucou3');
        $this->assertEquals("DELETE FROM item\nWHERE title='coucou3'", $query->toSql());
        $res = $query->execute();
        $this->assertEquals(1, $res->getAffectedRows());
        $res->close();

        $query = $this->database->delete('test');
        $this->assertTrue($query instanceof \cotcot\component\database\DeleteQuery);
        $this->assertEquals("DELETE FROM test", $query->toSql());
    }

    public function test_updateQuery() {
        $query = new \cotcot\component\database\InsertQuery();
        $query->database = $this->database;
        $query->into('item')->values(array('title' => 'coucou4', 'value' => 1))->execute()->close();

        $query = new \cotcot\component\database\UpdateQuery();
        $query->database = $this->database;
        $query->update('item')->andWhere('title=:t')->bindValue(':t', 'coucou4')->values(array('value' => '2', 'title' => 'Boum'));
        $this->assertEquals("UPDATE item\nSET value='2',title='Boum'\nWHERE title='coucou4'", $query->toSql());
        $res = $query->execute();
        $this->assertEquals(1, $res->getAffectedRows());
        $res->close();

        $query = $this->database->update('test');
        $this->assertTrue($query instanceof \cotcot\component\database\UpdateQuery);
        $this->assertEquals('UPDATE test', $query->toSql());
    }

    public function test_rawQuery() {
        $query = new \cotcot\component\database\RawQuery();
        $query->database = $this->database;
        $this->assertRegexp('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.*$/', $query->sql('SELECT NOW()')->execute()->fetchScalar());

        $query = $this->database->raw('SELECT NOW()');
        $this->assertTrue($query instanceof \cotcot\component\database\RawQuery);
        $this->assertEquals('SELECT NOW()', $query->toSql());

        $this->assertEquals(1, $this->database->raw('SELECT NOW()')->count());
    }

    public function test_isSafe() {
        $this->assertTrue($this->database->isPartialQuerySafe('une_fonction(\'a;b;c--/*\')'));
        $this->assertTrue($this->database->isPartialQuerySafe('\'le fond de l\\\'étang\''));
        $this->assertFalse($this->database->isPartialQuerySafe('test--Un commentaire'));
        $this->assertFalse($this->database->isPartialQuerySafe('test/*Un commentaire'));
        $this->assertFalse($this->database->isPartialQuerySafe('test;aaa'));
        $this->assertTrue($this->database->isPartialQuerySafe('t\\est'));
        $this->assertTrue($this->database->isPartialQuerySafe('aaa/aaaa'));
        $this->assertTrue($this->database->isPartialQuerySafe('aaa-aaaa'));

        $this->assertFalse($this->database->raw('SELECT NOW();SELECT NOW()')->isSafe());
        $this->assertTrue($this->database->raw('SELECT NOW()')->isSafe());
    }

    public function test_isConnected() {
        $this->database->disconnect();
        $this->assertFalse($this->database->isConnected());
        $this->database->connect();
        $this->assertTrue($this->database->isConnected());
    }

    public function test_quote() {
        $this->assertEquals('\'10\'', $this->database->quote(10));
        $this->assertEquals('\'10\'', $this->database->quote('10'));
        $this->assertEquals('\'salut\'', $this->database->quote('salut'));
        $this->assertEquals('\'salut l\'\'ami\'', $this->database->quote('salut l\'ami'));
        $this->assertEquals('NULL', $this->database->quote(null));
    }

    public function test_arrayQuote() {
        $res = $this->database->arrayQuote(array(
            10,
            '10',
            'salut',
            'salut l\'ami',
            null
        ));
        $this->assertEquals('\'10\'', $res[0]);
        $this->assertEquals('\'10\'', $res[1]);
        $this->assertEquals('\'salut\'', $res[2]);
        $this->assertEquals('\'salut l\'\'ami\'', $res[3]);
        $this->assertEquals('NULL', $res[4]);
    }

    public function test_getNextId() {
        $id = $this->database->getNextId('item');
        $this->assertNotNull($id);
        $this->assertRegexp('/^[0-9]*$/', $id);
    }

    public function test_getNextSequenceValue() {
        $id = $this->database->getNextSequenceValue('item_id_seq');
        $this->assertNotNull($id);
        $this->assertRegexp('/^[0-9]*$/', $id);
    }

    public function test_getTransaction() {
        $query = new \cotcot\component\database\SelectQuery();
        $query->database = $this->database;
        $query->select()->from('item');

        $transaction = $this->database->getTransaction();
        $this->assertEquals(0, $query->count());
        $this->database->insert('item')->values(array('title' => 'aaa'))->execute()->close();
        $this->assertEquals(1, $query->count());
        $transaction->rollback();
        $this->assertEquals(0, $query->count());

        $transaction = $this->database->getTransaction();
        $this->database->insert('item')->values(array('title' => 'aaa'))->execute()->close();
        $this->assertEquals(1, $query->count());
        $transaction->commit();
        $this->assertEquals(1, $query->count());

        $transaction = $this->database->getTransaction();
        $this->database->insert('item')->values(array('title' => 'aaa'))->execute()->close();
        $this->assertEquals(2, $query->count());
        $transaction = $this->database->getTransaction();
        $this->database->insert('item')->values(array('title' => 'aaa'))->execute()->close();
        $this->assertEquals(3, $query->count());
        $transaction->rollback();
        $this->assertEquals(2, $query->count());
        $transaction = $this->database->getTransaction();
        try {
            $this->database->raw('very bad query...')->execute()->close();
            $this->fail('exception must be thrown');
        } catch (\Exception $ex) {
            //Do nothing...
        }
        $transaction->rollback();
        $this->assertEquals(2, $query->count());
        $this->database->insert('item')->values(array('title' => 'aaa'))->execute()->close();
        $this->assertEquals(3, $query->count());
        $transaction->commit();
        $this->assertEquals(3, $query->count());
        $this->database->insert('item')->values(array('title' => 'aaa'))->executeAndClose();
        $this->assertEquals(4, $query->count());
    }

}
